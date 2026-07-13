<?php

namespace App\Jobs;

use App\Mail\EventNotificationMail;
use App\Models\EventNotificationLog;
use App\Models\EventNotificationRecipient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Throwable;

class SendEventNotificationEmail implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [60, 300];

    public int $uniqueFor = 3600;

    public function __construct(public int $recipientId) {}

    public function uniqueId(): string
    {
        return (string) $this->recipientId;
    }

    public function handle(): void
    {
        $recipient = EventNotificationRecipient::with(['batch', 'event'])->findOrFail($this->recipientId);

        if ($recipient->status === 'sent') {
            return;
        }

        $batch = $recipient->batch;
        if ($batch->failed_recipients > 0
            || $batch->valid_recipients !== $batch->total_recipients
            || ! in_array($batch->status, ['queued', 'processing'], true)) {
            throw new RuntimeException('Batch notifikasi tidak memenuhi syarat pengiriman.');
        }

        $recipient->update(['status' => 'processing', 'failure_reason' => null]);
        $batch->update(['status' => 'processing']);

        Mail::to($recipient->email)->send(new EventNotificationMail($recipient));

        $recipient->update(['status' => 'sent', 'sent_at' => now()]);
        $this->syncBatch($recipient);
    }

    public function failed(Throwable $exception): void
    {
        $recipient = EventNotificationRecipient::with('batch')->find($this->recipientId);
        if (! $recipient || $recipient->status === 'sent') {
            return;
        }

        $recipient->update([
            'status' => 'failed',
            'failure_reason' => mb_substr($exception->getMessage(), 0, 2000),
        ]);
        $this->syncBatch($recipient);

        EventNotificationLog::create([
            'batch_id' => $recipient->batch_id,
            'event_id' => $recipient->event_id,
            'action' => 'delivery_failed',
            'metadata' => ['recipient_id' => $recipient->id, 'email' => $recipient->email],
        ]);
    }

    private function syncBatch(EventNotificationRecipient $recipient): void
    {
        $batch = $recipient->batch;
        $pending = $batch->recipients()->whereIn('status', ['verified', 'queued', 'processing'])->exists();
        $failed = $batch->recipients()->where('status', 'failed')->count();

        $batch->update([
            'status' => $pending ? 'processing' : ($failed > 0 ? 'failed' : 'sent'),
            'failed_recipients' => $failed,
            'sent_at' => ! $pending && $failed === 0 ? now() : null,
            'last_error' => $failed > 0 ? 'Terdapat '.$failed.' email yang gagal dikirim.' : null,
        ]);

        if (! $pending && $failed === 0) {
            EventNotificationLog::create([
                'batch_id' => $batch->id,
                'event_id' => $batch->event_id,
                'action' => 'sending_completed',
                'metadata' => ['total_recipients' => $batch->total_recipients],
            ]);
        }
    }
}

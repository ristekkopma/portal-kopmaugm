<?php

namespace App\Jobs;

use App\Models\AnnouncementRecipient;
use App\Services\WahaClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class AnnouncementSendOneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;         // retry maksimal
    public int $backoff = 10;      // detik

    public function __construct(public int $recipientId) {}

    public function handle(WahaClient $waha): void
    {
        $rec = AnnouncementRecipient::with('announcement')->findOrFail($this->recipientId);
        $ann = $rec->announcement;

        $chatId = $waha->toChatId($rec->whatsapp);
        if (!$chatId) {
            $rec->update([
                'status' => 'failed',
                'attempts' => $rec->attempts + 1,
                'last_error' => 'Invalid whatsapp number',
            ]);
            return;
        }

        $text = "*{$ann->title}*\n\n{$ann->message}";

        $res = $waha->sendText($chatId, $text);

        $updates = ['attempts' => $rec->attempts + 1, 'waha_raw' => $res];

        if (!$res['ok']) {
            $updates['status'] = 'failed';
            $updates['last_error'] = is_array($res['body']) ? json_encode($res['body']) : (string) $res['body'];
        } else {
            $updates['status'] = 'sent';
            $updates['sent_at'] = Carbon::now();
            // jika response mengandung id pesan, simpan
            $updates['waha_message_id'] = data_get($res, 'body.id') ?? data_get($res, 'body.messageId');
        }

        $rec->update($updates);
    }

    public function failed(\Throwable $e): void
    {
        $rec = AnnouncementRecipient::find($this->recipientId);
        if ($rec) {
            $rec->update([
                'status' => 'failed',
                'last_error' => $e->getMessage(),
            ]);
        }
    }
}
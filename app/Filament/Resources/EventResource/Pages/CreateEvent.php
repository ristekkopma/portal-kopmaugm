<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Jobs\SendEventNotificationEmail;
use App\Models\EventNotificationBatch;
use App\Models\EventNotificationLog;
use App\Services\EventRecipientVerifier;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected static bool $canCreateAnother = false;

    public array $recipientVerification = [];

    public ?string $recipientVerificationFingerprint = null;

    public bool $verificationInvalidated = false;

    public bool $sendNotificationsAfterCreate = false;

    public function verifyRecipients(EventRecipientVerifier $verifier): void
    {
        $this->authorize('send_event_notifications');
        $url = trim((string) data_get($this->data, 'spreadsheet_url'));

        if ($url === '') {
            $this->addError('data.spreadsheet_url', 'URL Google Spreadsheet / CSV wajib diisi.');

            return;
        }

        $this->recipientVerification = $verifier->verifyUrl($url);
        $this->recipientVerificationFingerprint = $this->notificationFingerprint();
        $this->verificationInvalidated = false;

        $hasErrors = ($this->recipientVerification['failed'] + $this->recipientVerification['duplicate']) > 0;
        Notification::make()
            ->title($hasErrors ? 'Verifikasi belum berhasil' : 'Seluruh penerima valid')
            ->body($hasErrors
                ? 'Pengiriman ditahan. Perbaiki data gagal atau duplikat, lalu verifikasi ulang.'
                : $this->recipientVerification['valid'].' penerima siap dimasukkan ke antrean setelah Event tersimpan.')
            ->color($hasErrors ? 'danger' : 'success')
            ->send();
    }

    public function invalidateRecipientVerification(): void
    {
        if ($this->recipientVerification !== []) {
            $this->verificationInvalidated = true;
            $this->recipientVerificationFingerprint = null;
        }
    }

    public function isRecipientVerificationCurrent(): bool
    {
        return (bool) data_get($this->data, 'notify_members')
            && $this->recipientVerification !== []
            && $this->recipientVerification['valid'] > 0
            && $this->recipientVerification['failed'] === 0
            && $this->recipientVerification['duplicate'] === 0
            && $this->recipientVerificationFingerprint !== null
            && hash_equals($this->recipientVerificationFingerprint, $this->notificationFingerprint());
    }

    public function createAndSendNotifications(): void
    {
        $this->authorize('send_event_notifications');

        if (! $this->isRecipientVerificationCurrent()) {
            Notification::make()
                ->title('Verifikasi penerima belum valid')
                ->body('Verifikasi ulang spreadsheet setelah seluruh data Event selesai diubah.')
                ->danger()
                ->send();

            return;
        }

        $this->sendNotificationsAfterCreate = true;
        try {
            $this->create();
        } finally {
            $this->sendNotificationsAfterCreate = false;
        }
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Simpan Event'),
            Action::make('saveAndNotify')
                ->label('Simpan dan Kirim Notifikasi')
                ->icon('heroicon-o-envelope')
                ->action('createAndSendNotifications')
                ->disabled(fn (): bool => ! $this->isRecipientVerificationCurrent())
                ->visible(fn (): bool => (bool) data_get($this->data, 'notify_members') && auth()->user()->can('send_event_notifications')),
            $this->getCancelFormAction()->label('Batal'),
        ];
    }

    protected function afterCreate(): void
    {
        if (! $this->sendNotificationsAfterCreate || ! $this->isRecipientVerificationCurrent()) {
            return;
        }

        $rows = collect($this->recipientVerification['rows'])->where('status', 'valid');
        $batch = EventNotificationBatch::create([
            'event_id' => $this->record->id,
            'spreadsheet_url' => data_get($this->data, 'spreadsheet_url'),
            'message' => data_get($this->data, 'notification_summary')
                ?: Str::limit(strip_tags((string) $this->record->description), 600),
            'status' => 'verified',
            'total_recipients' => $rows->count(),
            'valid_recipients' => $rows->count(),
            'failed_recipients' => 0,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        foreach ($rows as $row) {
            $batch->recipients()->create([
                'event_id' => $this->record->id,
                'user_id' => $row['user_id'],
                'name' => $row['database_name'],
                'email' => $row['database_email'],
                'status' => 'queued',
            ]);
        }

        abort_unless($batch->recipients()->count() === $batch->total_recipients, 409, 'Snapshot penerima tidak lengkap.');

        $batch->update(['status' => 'queued']);
        foreach (['spreadsheet_verified', 'event_created', 'sending_started'] as $action) {
            EventNotificationLog::create([
                'batch_id' => $batch->id,
                'event_id' => $this->record->id,
                'user_id' => auth()->id(),
                'action' => $action,
                'metadata' => ['total_recipients' => $batch->total_recipients],
            ]);
        }

        foreach ($batch->recipients as $recipient) {
            SendEventNotificationEmail::dispatch($recipient->id)->afterCommit();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    private function notificationFingerprint(): string
    {
        return hash('sha256', json_encode([
            'spreadsheet_url' => trim((string) data_get($this->data, 'spreadsheet_url')),
            'title' => trim((string) data_get($this->data, 'title')),
            'event_date' => data_get($this->data, 'event_date'),
            'start_time' => data_get($this->data, 'start_time'),
            'end_time' => data_get($this->data, 'end_time'),
            'location' => trim((string) data_get($this->data, 'location')),
            'registration_url' => trim((string) data_get($this->data, 'registration_url')),
            'notification_summary' => trim((string) data_get($this->data, 'notification_summary')),
        ], JSON_UNESCAPED_UNICODE));
    }
}

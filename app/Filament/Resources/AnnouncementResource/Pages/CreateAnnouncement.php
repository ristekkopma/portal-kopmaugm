<?php

namespace App\Filament\Resources\AnnouncementResource\Pages;

use App\Filament\Resources\AnnouncementResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Jobs\AnnouncementDispatchJob;

class CreateAnnouncement extends CreateRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function afterCreate(): void
    {
        $ann = $this->record;
        $ann->update(['status' => 'queued']);

        dispatch(new AnnouncementDispatchJob($ann->id));

        Notification::make()
            ->title('Pengumuman diantrikan untuk dikirim.')
            ->success()
            ->send();
    }
}
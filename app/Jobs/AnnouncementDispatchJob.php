<?php

namespace App\Jobs;

use App\Models\Announcement;
use App\Models\AnnouncementRecipient;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnnouncementDispatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $announcementId) {}

    public function handle(): void
{
    $ann = Announcement::findOrFail($this->announcementId);

    // Ambil user berdasarkan target atau broadcast
    $users = $ann->is_broadcast
        ? User::query()->whereNotNull('phone')->get()
        : User::query()
            ->whereIn('id', $ann->target_user_ids ?? [])
            ->whereNotNull('phone')
            ->get();

    // Buat recipients
    foreach ($users as $u) {
        // Gunakan phone sebagai nomor WhatsApp
        $rec = AnnouncementRecipient::firstOrCreate(
            ['announcement_id' => $ann->id, 'user_id' => $u->id],
            ['whatsapp' => $u->phone, 'status' => 'pending']
        );

        // Kirimkan job pengiriman individu
        dispatch(new AnnouncementSendOneJob($rec->id));
    }

    // Update status pengumuman
    $ann->update(['status' => 'sending']);
}
}

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventFollower;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventFollowerController extends Controller
{
    public function export(Request $request): StreamedResponse
    {
        $event = $request->integer('event_id')
            ? Event::findOrFail($request->integer('event_id'))
            : null;

        $followers = EventFollower::query()
            ->with(['user.profile', 'event'])
            ->when($event, fn ($query) => $query->whereBelongsTo($event))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->oldest()
            ->get();

        $filename = $event
            ? 'daftar-peminat-' . $event->slug . '.csv'
            : 'daftar-peminat-event-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($followers): void {
            $stream = fopen('php://output', 'w');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, [
                'No',
                'Nama',
                'Email',
                'Nomor Telepon',
                'Instansi/Divisi',
                'Nama Event',
                'Status',
                'Tanggal Menyatakan Minat',
            ]);

            foreach ($followers as $index => $follower) {
                fputcsv($stream, [
                    $index + 1,
                    $follower->user->name,
                    $follower->user->email,
                    $follower->user->phone,
                    $follower->user->profile?->instance ?: $follower->user->profile?->faculty,
                    $follower->event->title,
                    EventFollower::statuses()[$follower->status] ?? $follower->status,
                    $follower->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($stream);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}

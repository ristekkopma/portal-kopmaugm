<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function show(Event $event): RedirectResponse
    {
        abort_if($event->status === 'draft', 404);

        return redirect()->route('filament.portal.resources.events.view', ['record' => $event]);
    }

    public function toggleFollow(Request $request, Event $event): RedirectResponse
    {
        DB::transaction(function () use ($request, $event): void {
            $follower = $event->followers()
                ->withTrashed()
                ->where('user_id', $request->user()->id)
                ->first();

            if ($follower) {
                if ($follower->trashed()) {
                    $follower->restore();
                    $follower->update(['status' => 'interested']);
                } else {
                    $follower->update([
                        'status' => $follower->status === 'cancelled' ? 'interested' : 'cancelled',
                    ]);
                }
            } else {
                $event->followers()->create([
                    'user_id' => $request->user()->id,
                    'status' => 'interested',
                ]);
            }
        });

        return back()->with('success', 'Status keikutsertaan berhasil diperbarui.');
    }

    public function saveReview(Request $request, Event $event): RedirectResponse
    {
        abort_unless($event->schedule_end?->isPast() || $event->status === 'completed', 403);

        $request->merge(['review' => trim((string) $request->input('review'))]);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'review' => ['required', 'string', 'min:2', 'max:2000'],
        ]);

        DB::transaction(function () use ($request, $event, $validated): void {
            $event->reviews()->updateOrCreate(
                ['user_id' => $request->user()->id],
                $validated,
            );
        });

        return back()->with('success', 'Review berhasil disimpan.');
    }

}

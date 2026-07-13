<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Menampilkan daftar event.
     */
    public function index(Request $request): View
    {
        $isAdmin = $this->isAdmin($request);

        $events = Event::query()
            ->withCount(['activeFollowers as followers_count', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->when(! $isAdmin, fn ($query) => $query
                ->where('status', '!=', 'draft')
                ->where(function ($query) {
                    $query->whereNotNull('published_at')
                        ->orWhere('status', 'published')
                        ->orWhere('status', 'ongoing')
                        ->orWhere('status', 'completed')
                        ->orWhere('status', 'cancelled');
                }))
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->latest('published_at')
            ->latest('event_date')
            ->paginate(9)
            ->withQueryString();

        return view('events.index', [
            'events' => $events,
            'categories' => Event::categories(),
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Menampilkan detail event.
     * Untuk saat ini diarahkan kembali ke daftar event.
     */
    public function show(Request $request, Event $event): View
    {
        abort_if($event->status === 'draft' && ! $this->isAdmin($request), 404);

        $event->loadCount(['activeFollowers as followers_count', 'reviews'])
            ->loadAvg('reviews', 'rating')
            ->load(['reviews' => fn ($query) => $query->with('user')->latest()]);

        return view('events.show', [
            'event' => $event,
            'isAdmin' => $this->isAdmin($request),
            'isFollowing' => $event->activeFollowers()->where('user_id', $request->user()->id)->exists(),
            'userReview' => $event->reviews->firstWhere('user_id', $request->user()->id),
            'canReview' => $event->schedule_end?->isPast() || $event->status === 'completed',
        ]);
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

    private function isAdmin(Request $request): bool
    {
        return in_array($request->user()?->role, [
            UserRole::SuperAdmin,
            UserRole::Admin,
        ], true);
    }
}

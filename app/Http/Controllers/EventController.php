<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    /**
     * Menampilkan daftar event.
     */
    public function index(Request $request)
    {
        $events = Event::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->latest('opened_at')
            ->paginate(10)
            ->withQueryString();

        return view('events.index', [
            'events' => $events,
            'categories' => Event::categories(),
        ]);
    }

    /**
     * Menampilkan halaman tambah event.
     */
    public function create()
    {
        return view('events.create', [
            'categories' => Event::categories(),
        ]);
    }

    /**
     * Menyimpan event baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'url' => ['nullable', 'url', 'max:255'],
            'category' => [
                'required',
                Rule::in(array_keys(Event::categories())),
            ],
            'opened_at' => ['required', 'date'],
            'closed_at' => ['required', 'date', 'after_or_equal:opened_at'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('events', 'public');
        }

        Event::create($validated);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail event.
     * Untuk saat ini diarahkan kembali ke daftar event.
     */
    public function show(Event $event)
    {
        return redirect()->route('events.index');
    }

    /**
     * Menampilkan halaman edit event.
     */
    public function edit(Event $event)
    {
        return view('events.edit', [
            'event' => $event,
            'categories' => Event::categories(),
        ]);
    }

    /**
     * Memperbarui event.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'url' => ['nullable', 'url', 'max:255'],
            'category' => [
                'required',
                Rule::in(array_keys(Event::categories())),
            ],
            'opened_at' => ['required', 'date'],
            'closed_at' => ['required', 'date', 'after_or_equal:opened_at'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }

            $validated['image'] = $request->file('image')
                ->store('events', 'public');
        }

        $event->update($validated);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Menghapus event.
     */
    public function destroy(Event $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
@extends('events.layout')

@section('title', 'Events')

@section('content')
<main class="page-shell">
    <div class="page-heading">
        <div>
            <p class="eyebrow">Agenda Kopma UGM</p>
            <h1>Temukan Event Terbaru</h1>
            <p class="subheading">Ikuti kegiatan, pelatihan, dan agenda Kopma UGM yang menarik untukmu.</p>
        </div>
        @if($isAdmin)
            <a href="{{ route('filament.admin.resources.events.create') }}" class="btn btn-primary">＋ Tambah Event</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('events.index') }}" class="filters">
        <input class="field" type="search" name="search" value="{{ request('search') }}" placeholder="Cari judul atau keterangan event...">
        <select class="field" name="category" aria-label="Filter kategori">
            <option value="">Semua kategori</option>
            @foreach($categories as $value => $label)
                <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary" type="submit">Cari Event</button>
    </form>

    <section class="event-grid" aria-label="Daftar event">
        @forelse($events as $event)
            <article class="event-card">
                <a href="{{ route('events.show', $event) }}" class="card-link" aria-label="Buka detail {{ $event->title }}"></a>
                <img class="card-image" src="{{ $event->display_image }}" alt="Banner {{ $event->title }}" loading="lazy">

                <div class="card-menu">
                    <button class="menu-button" type="button" aria-label="Menu event" onclick="toggleCardMenu(event, this)">•••</button>
                    <div class="menu-popover">
                        <a href="{{ route('events.show', $event) }}">Lihat Detail</a>
                        @if($isAdmin)
                            <a href="{{ route('filament.admin.resources.events.edit', $event) }}">Edit Event</a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="card-meta">
                        <span class="badge">{{ $categories[$event->category] ?? ucfirst($event->category) }}</span>
                        <span class="badge badge-{{ $event->status_color }}">{{ $event->status_label }}</span>
                    </div>
                    <h2 class="card-title">{{ $event->title }}</h2>
                    <div class="organizer-row">
                        <img src="{{ $event->display_organizer_logo }}" alt="Logo {{ $event->organizer_name ?: 'Kopma UGM' }}">
                        <div>
                            <div class="organizer-name">{{ $event->organizer_name ?: 'Kopma UGM' }}</div>
                            <div>{{ optional($event->published_at ?: $event->created_at)->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div class="card-stats">
                        <span>☆ {{ $event->followers_count }} tertarik</span>
                        <span>★ {{ number_format((float) $event->reviews_avg_rating, 1) }} ({{ $event->reviews_count }})</span>
                    </div>
                </div>
            </article>
        @empty
            <div class="empty">
                <h2>Belum ada event</h2>
                <p>Event yang diterbitkan admin akan tampil di halaman ini.</p>
            </div>
        @endforelse
    </section>

    @if($events->hasPages())
        <nav class="pagination" aria-label="Navigasi halaman">
            @if($events->onFirstPage())
                <span class="btn" aria-disabled="true">← Sebelumnya</span>
            @else
                <a class="btn" href="{{ $events->previousPageUrl() }}" rel="prev">← Sebelumnya</a>
            @endif
            <span class="page-info">Halaman {{ $events->currentPage() }} dari {{ $events->lastPage() }}</span>
            @if($events->hasMorePages())
                <a class="btn" href="{{ $events->nextPageUrl() }}" rel="next">Berikutnya →</a>
            @else
                <span class="btn" aria-disabled="true">Berikutnya →</span>
            @endif
        </nav>
    @endif
</main>
@endsection

@push('scripts')
<script>
    function toggleCardMenu(event, button) {
        event.preventDefault();
        event.stopPropagation();
        const menu = button.closest('.card-menu');
        document.querySelectorAll('.card-menu.open').forEach(item => item !== menu && item.classList.remove('open'));
        menu.classList.toggle('open');
    }
    document.addEventListener('click', () => document.querySelectorAll('.card-menu.open').forEach(item => item.classList.remove('open')));
</script>
@endpush

@extends('events.layout')

@section('title', $event->title)

@push('styles')
    .detail-shell { width:min(1220px,94vw); margin:34px auto 72px; }
    .detail-panel { overflow:hidden; background:white; border:1px solid var(--line); border-radius:22px; box-shadow:0 22px 60px rgba(16,24,40,.12); }
    .detail-header { display:flex; align-items:center; justify-content:space-between; gap:20px; padding:24px 30px; }
    .detail-header h1 { max-width:820px; font-size:clamp(26px,3.3vw,42px); }
    .icon-actions { display:flex; align-items:center; gap:5px; }
    .icon-btn { display:grid; width:44px; height:44px; place-items:center; color:#475467; background:transparent; border:0; border-radius:50%; font-size:23px; cursor:pointer; }
    .icon-btn:hover,.icon-btn.active { color:var(--primary); background:#ecfdf5; }
    .detail-banner { width:100%; max-height:620px; aspect-ratio:16/7; object-fit:cover; background:#e9eef3; }
    .detail-content { padding:30px; }
    .publisher { display:flex; align-items:center; gap:14px; padding-bottom:25px; border-bottom:1px solid var(--line); }
    .publisher-logo { width:58px; height:58px; object-fit:contain; background:#f8fafc; border:1px solid var(--line); border-radius:50%; }
    .publisher-main { flex:1; }
    .publisher-name { margin-bottom:6px; font-size:18px; font-weight:800; }
    .publisher-meta { display:flex; flex-wrap:wrap; align-items:center; gap:8px; color:#667085; font-size:14px; }
    .detail-grid { display:grid; grid-template-columns:minmax(0,1.65fr) minmax(280px,.75fr); gap:38px; padding-top:30px; }
    .prose { color:#344054; font-size:17px; line-height:1.82; }
    .prose h2 { margin:34px 0 12px; color:var(--ink); font-size:23px; line-height:1.35; }
    .prose h2:first-child { margin-top:0; }
    .info-card { position:sticky; top:100px; align-self:start; padding:22px; background:#f8fafc; border:1px solid var(--line); border-radius:16px; }
    .info-card h2 { margin:0 0 18px; font-size:18px; }
    .info-item { display:grid; grid-template-columns:34px 1fr; gap:10px; margin-bottom:17px; }
    .info-icon { display:grid; width:32px; height:32px; place-items:center; color:var(--primary-dark); background:#dff7ec; border-radius:9px; }
    .info-label { color:#667085; font-size:12px; font-weight:750; text-transform:uppercase; letter-spacing:.05em; }
    .info-value { margin-top:2px; color:#344054; font-size:14px; font-weight:700; line-height:1.45; }
    .primary-actions { display:flex; flex-wrap:wrap; gap:10px; margin:26px 0 4px; padding:22px 0; border-top:1px solid var(--line); border-bottom:1px solid var(--line); }
    .follow-btn.following { color:#067647; background:#ecfdf3; border-color:#75e0a7; }
    .action-count { width:100%; color:#667085; font-size:14px; }
    .review-summary { display:flex; align-items:center; gap:14px; margin:40px 0 22px; }
    .rating-number { font-size:40px; font-weight:850; }
    .stars { color:#f79009; letter-spacing:2px; }
    .review-list { display:grid; gap:14px; }
    .review-card { padding:18px; background:#f8fafc; border:1px solid var(--line); border-radius:14px; }
    .review-head { display:flex; justify-content:space-between; gap:12px; margin-bottom:10px; }
    .review-user { font-weight:800; }.review-date { color:#667085; font-size:13px; }
    .review-text { margin:8px 0 0; color:#475467; line-height:1.65; }
    dialog { width:min(540px,calc(100% - 30px)); padding:0; border:0; border-radius:18px; box-shadow:0 25px 70px rgba(16,24,40,.3); }
    dialog::backdrop { background:rgba(15,23,42,.66); backdrop-filter:blur(3px); }
    .dialog-head { display:flex; align-items:center; justify-content:space-between; padding:20px 22px; border-bottom:1px solid var(--line); }
    .dialog-head h2 { margin:0; font-size:21px; }.dialog-body { padding:22px; }
    .rating-input { display:flex; flex-direction:row-reverse; justify-content:flex-end; gap:4px; margin:8px 0 20px; }
    .rating-input input { position:absolute; opacity:0; }.rating-input label { color:#d0d5dd; font-size:34px; cursor:pointer; }
    .rating-input input:checked ~ label,.rating-input label:hover,.rating-input label:hover ~ label { color:#f79009; }
    .textarea { min-height:130px; resize:vertical; }.form-label { display:block; margin-bottom:7px; font-weight:750; }
    .error { margin-top:6px; color:var(--danger); font-size:13px; }
    .mobile-action { display:none; }
    .fullscreen-detail:fullscreen { overflow:auto; background:white; border-radius:0; }
    @media(max-width:850px){ .detail-shell{width:100%;margin:0 auto 70px}.detail-panel{border-width:0;border-radius:0}.detail-header{align-items:flex-start;padding:20px}.icon-actions{gap:0}.detail-header .optional-icon{display:none}.detail-banner{aspect-ratio:16/10}.detail-content{padding:20px}.detail-grid{grid-template-columns:1fr;gap:24px}.info-card{position:static;order:-1}.primary-actions{padding-bottom:90px}.primary-actions .btn{flex:1 1 100%}.mobile-action{position:fixed;z-index:35;right:0;bottom:0;left:0;display:flex;padding:12px 15px;background:rgba(255,255,255,.97);border-top:1px solid var(--line);box-shadow:0 -8px 25px rgba(16,24,40,.1)}.mobile-action .btn{width:100%} }
@endpush

@section('content')
<main class="detail-shell">
    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <article class="detail-panel fullscreen-detail" id="eventDetail">
        <header class="detail-header">
            <h1>{{ $event->title }}</h1>
            <div class="icon-actions">
                <button type="button" class="icon-btn" onclick="shareEvent()" title="Bagikan" aria-label="Bagikan">⇧</button>
                <button type="button" class="icon-btn" id="bookmarkButton" onclick="toggleBookmark()" title="Simpan" aria-label="Simpan">☆</button>
                @if($isAdmin)
                    <a class="icon-btn" href="{{ route('filament.admin.resources.events.edit', $event) }}" title="Edit event" aria-label="Edit event">•••</a>
                @else
                    <button type="button" class="icon-btn" onclick="copyEventLink()" title="Salin tautan" aria-label="Salin tautan">•••</button>
                @endif
                <button type="button" class="icon-btn optional-icon" onclick="toggleFullscreen()" title="Perbesar" aria-label="Perbesar">⛶</button>
                <button type="button" class="icon-btn" onclick="closeDetail()" title="Tutup" aria-label="Tutup">×</button>
            </div>
        </header>

        <img class="detail-banner" src="{{ $event->display_image }}" alt="Banner {{ $event->title }}">

        <div class="detail-content">
            <section class="publisher">
                <img class="publisher-logo" src="{{ $event->display_organizer_logo }}" alt="Logo {{ $event->organizer_name ?: 'Kopma UGM' }}">
                <div class="publisher-main">
                    <div class="publisher-name">{{ $event->organizer_name ?: 'Kopma UGM' }}</div>
                    <div class="publisher-meta">
                        @if($isAdmin)<span class="badge badge-blue">Admin</span>@endif
                        <span class="badge">{{ $event->category ? (\App\Models\Event::categories()[$event->category] ?? ucfirst($event->category)) : 'Event' }}</span>
                        <span>{{ optional($event->published_at ?: $event->created_at)->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
                <span class="badge badge-{{ $event->status_color }}">{{ $event->status_label }}</span>
            </section>

            <div class="detail-grid">
                <div>
                    <div class="prose">
                        <h2>Tentang Event</h2>
                        <div>{!! nl2br(e($event->description ?: 'Informasi lengkap event akan segera diperbarui oleh penyelenggara.')) !!}</div>

                        @if($event->rundown)
                            <h2>Rundown dan Informasi Tambahan</h2>
                            <div>{!! nl2br(e($event->rundown)) !!}</div>
                        @endif

                        @if($event->terms)
                            <h2>Syarat dan Ketentuan</h2>
                            <div>{!! nl2br(e($event->terms)) !!}</div>
                        @endif
                    </div>

                    <div class="primary-actions">
                        <form method="POST" action="{{ route('events.follow', $event) }}">
                            @csrf
                            <button class="btn follow-btn {{ $isFollowing ? 'following' : '' }}" type="submit">
                                {{ $isFollowing ? '✓ Ingin Mengikuti' : '☆ Ingin Mengikuti' }}
                            </button>
                        </form>

                        @if($event->safe_registration_url)
                            <a class="btn btn-primary" href="{{ $event->safe_registration_url }}" target="_blank" rel="noopener noreferrer">Daftar Event ↗</a>
                        @endif

                        <button class="btn" type="button" @if($canReview) onclick="openReviewDialog()" @else disabled title="Review tersedia setelah event selesai" @endif>
                            ★ {{ $userReview ? 'Edit Review' : 'Beri Review' }}
                        </button>
                        <div class="action-count">{{ $event->followers_count }} orang ingin mengikuti</div>
                    </div>

                    <section id="reviews">
                        <div class="review-summary">
                            <div class="rating-number">{{ number_format((float) $event->reviews_avg_rating, 1) }}</div>
                            <div>
                                <div class="stars">{{ str_repeat('★', (int) round($event->reviews_avg_rating ?? 0)) }}{{ str_repeat('☆', 5 - (int) round($event->reviews_avg_rating ?? 0)) }}</div>
                                <div>{{ $event->reviews_count }} review</div>
                            </div>
                        </div>

                        <div class="review-list">
                            @forelse($event->reviews as $review)
                                <article class="review-card">
                                    <div class="review-head">
                                        <div class="review-user">{{ $review->user->name }}</div>
                                        <div class="review-date">{{ $review->updated_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
                                    <p class="review-text">{{ $review->review }}</p>
                                </article>
                            @empty
                                <div class="review-card">Belum ada review untuk event ini.</div>
                            @endforelse
                        </div>
                    </section>
                </div>

                <aside class="info-card">
                    <h2>Informasi Event</h2>
                    <div class="info-item">
                        <span class="info-icon">◷</span>
                        <div><div class="info-label">Tanggal & waktu</div><div class="info-value">{{ optional($event->schedule_start)->translatedFormat('d F Y, H:i') ?: 'Akan diumumkan' }}@if($event->schedule_end)<br>sampai {{ $event->schedule_end->translatedFormat('d F Y, H:i') }}@endif</div></div>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">⌖</span>
                        <div><div class="info-label">Lokasi</div><div class="info-value">{{ ucfirst($event->event_type ?: 'offline') }} · {{ $event->location ?: 'Akan diumumkan' }}</div></div>
                    </div>
                    @if($event->registration_deadline)
                        <div class="info-item">
                            <span class="info-icon">⌛</span>
                            <div><div class="info-label">Batas pendaftaran</div><div class="info-value">{{ $event->registration_deadline->translatedFormat('d F Y, H:i') }}</div></div>
                        </div>
                    @endif
                    @if($event->contact_person)
                        <div class="info-item">
                            <span class="info-icon">☎</span>
                            <div><div class="info-label">Narahubung</div><div class="info-value">{{ $event->contact_person }}</div></div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </article>

    @if($event->safe_registration_url)
        <div class="mobile-action"><a class="btn btn-primary" href="{{ $event->safe_registration_url }}" target="_blank" rel="noopener noreferrer">Daftar Event ↗</a></div>
    @endif
</main>

<dialog id="reviewDialog">
    <div class="dialog-head">
        <h2>{{ $userReview ? 'Edit Review' : 'Beri Review' }}</h2>
        <button class="icon-btn" type="button" onclick="closeReviewDialog()">×</button>
    </div>
    <form method="POST" action="{{ route('events.review', $event) }}" class="dialog-body">
        @csrf
        <div class="form-label">Rating</div>
        <div class="rating-input">
            @for($rating = 5; $rating >= 1; $rating--)
                <input id="rating-{{ $rating }}" type="radio" name="rating" value="{{ $rating }}" @checked((int) old('rating', $userReview?->rating) === $rating) required>
                <label for="rating-{{ $rating }}">★</label>
            @endfor
        </div>
        @error('rating')<div class="error">{{ $message }}</div>@enderror

        <label class="form-label" for="review">Ulasan oleh {{ auth()->user()->name }}</label>
        <textarea class="field textarea" id="review" name="review" maxlength="2000" required>{{ old('review', $userReview?->review) }}</textarea>
        @error('review')<div class="error">{{ $message }}</div>@enderror
        <button class="btn btn-primary" type="submit" style="width:100%;margin-top:18px">Simpan Review</button>
    </form>
</dialog>
@endsection

@push('scripts')
<script>
    const eventUrl = @json(route('events.show', $event));
    const bookmarkKey = 'kopma-event-bookmark-{{ $event->id }}';
    const bookmarkButton = document.getElementById('bookmarkButton');

    function syncBookmark() {
        const active = localStorage.getItem(bookmarkKey) === '1';
        bookmarkButton.textContent = active ? '★' : '☆';
        bookmarkButton.classList.toggle('active', active);
    }
    function toggleBookmark() {
        localStorage.setItem(bookmarkKey, localStorage.getItem(bookmarkKey) === '1' ? '0' : '1');
        syncBookmark();
    }
    async function shareEvent() {
        if (navigator.share) return navigator.share({ title: @json($event->title), url: eventUrl });
        return copyEventLink();
    }
    async function copyEventLink() {
        await navigator.clipboard.writeText(eventUrl);
        alert('Tautan event berhasil disalin.');
    }
    function toggleFullscreen() {
        const panel = document.getElementById('eventDetail');
        if (document.fullscreenElement) document.exitFullscreen(); else panel.requestFullscreen();
    }
    function closeDetail() {
        if (history.length > 1) history.back(); else window.location.href = @json(route('events.index'));
    }
    function openReviewDialog() { document.getElementById('reviewDialog').showModal(); }
    function closeReviewDialog() { document.getElementById('reviewDialog').close(); }
    syncBookmark();
    @if($errors->any()) openReviewDialog(); @endif
</script>
@endpush

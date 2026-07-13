<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Events') | Kopma UGM</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        :root { --primary:#16865f; --primary-dark:#0f684a; --ink:#172033; --muted:#667085; --line:#e4e7ec; --surface:#fff; --canvas:#f7f9fb; --danger:#d92d20; }
        * { box-sizing:border-box; }
        html { scroll-behavior:smooth; }
        body { margin:0; color:var(--ink); background:var(--canvas); font-family:Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        button,input,select,textarea { font:inherit; }
        a { color:inherit; }
        .site-header { position:sticky; top:0; z-index:40; display:flex; align-items:center; justify-content:space-between; min-height:72px; padding:0 5vw; background:rgba(255,255,255,.96); border-bottom:1px solid var(--line); backdrop-filter:blur(12px); }
        .brand img { display:block; width:auto; height:32px; }
        .main-nav { display:flex; align-items:center; gap:8px; }
        .main-nav a { padding:10px 14px; color:#475467; font-size:14px; font-weight:700; text-decoration:none; border-radius:10px; }
        .main-nav a:hover,.main-nav a.active { color:var(--primary-dark); background:#ecfdf5; }
        .user-chip { display:flex; align-items:center; gap:9px; margin-left:8px; padding-left:16px; border-left:1px solid var(--line); }
        .avatar { display:grid; flex:0 0 auto; width:38px; height:38px; place-items:center; overflow:hidden; color:white; background:var(--primary); border-radius:50%; font-size:14px; font-weight:800; }
        .avatar img { width:100%; height:100%; object-fit:cover; }
        .page-shell { width:min(1500px, 92vw); margin:0 auto; padding:44px 0 72px; }
        .page-heading { display:flex; align-items:flex-end; justify-content:space-between; gap:24px; margin-bottom:28px; }
        .eyebrow { margin:0 0 7px; color:var(--primary); font-size:13px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; }
        h1 { margin:0; font-size:clamp(32px,4vw,52px); line-height:1.08; letter-spacing:-.035em; }
        .subheading { max-width:720px; margin:12px 0 0; color:var(--muted); font-size:16px; line-height:1.7; }
        .btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; min-height:44px; padding:10px 16px; color:#344054; background:white; border:1px solid #d0d5dd; border-radius:11px; font-weight:750; text-decoration:none; cursor:pointer; transition:.2s ease; }
        .btn:hover { border-color:#98a2b3; transform:translateY(-1px); box-shadow:0 4px 12px rgba(16,24,40,.08); }
        .btn-primary { color:white; background:var(--primary); border-color:var(--primary); }
        .btn-primary:hover { color:white; background:var(--primary-dark); border-color:var(--primary-dark); }
        .btn-danger { color:var(--danger); }
        .filters { display:grid; grid-template-columns:minmax(220px,1fr) 210px auto; gap:12px; margin-bottom:30px; padding:15px; background:white; border:1px solid var(--line); border-radius:16px; box-shadow:0 8px 24px rgba(16,24,40,.04); }
        .field { width:100%; min-height:46px; padding:10px 13px; color:#344054; background:white; border:1px solid #d0d5dd; border-radius:10px; outline:none; }
        .field:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(22,134,95,.12); }
        .alert { margin-bottom:20px; padding:14px 16px; color:#05603a; background:#ecfdf3; border:1px solid #abefc6; border-radius:12px; }
        .event-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:24px; }
        .event-card { position:relative; display:flex; min-width:0; overflow:hidden; flex-direction:column; background:white; border:1px solid var(--line); border-radius:20px; box-shadow:0 4px 14px rgba(16,24,40,.04); transition:transform .22s ease,box-shadow .22s ease,border-color .22s ease; }
        .event-card:hover { border-color:#c7d0d9; transform:translateY(-5px); box-shadow:0 18px 40px rgba(16,24,40,.1); }
        .card-link { position:absolute; inset:0; z-index:1; border-radius:20px; }
        .card-image { width:100%; aspect-ratio:16/9; object-fit:cover; background:#e9eef3; }
        .card-menu { position:absolute; z-index:4; top:14px; right:14px; }
        .menu-button { display:grid; width:42px; height:42px; place-items:center; color:#344054; background:rgba(255,255,255,.94); border:1px solid rgba(255,255,255,.7); border-radius:12px; box-shadow:0 5px 16px rgba(16,24,40,.16); font-size:23px; line-height:1; cursor:pointer; }
        .menu-popover { position:absolute; top:48px; right:0; display:none; min-width:165px; padding:6px; background:white; border:1px solid var(--line); border-radius:12px; box-shadow:0 12px 30px rgba(16,24,40,.18); }
        .card-menu.open .menu-popover { display:block; }
        .menu-popover a { display:block; padding:9px 11px; color:#344054; border-radius:8px; font-size:14px; font-weight:700; text-decoration:none; }
        .menu-popover a:hover { background:#f2f4f7; }
        .card-body { display:flex; flex:1; flex-direction:column; padding:20px; }
        .card-meta { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:13px; }
        .badge { display:inline-flex; align-items:center; width:max-content; padding:5px 9px; color:#067647; background:#ecfdf3; border-radius:999px; font-size:12px; font-weight:800; }
        .badge-blue { color:#175cd3; background:#eff8ff; }.badge-red { color:#b42318; background:#fef3f2; }.badge-orange { color:#b54708; background:#fffaeb; }.badge-gray { color:#475467; background:#f2f4f7; }.badge-green { color:#067647; background:#ecfdf3; }
        .card-title { margin:0 0 10px; font-size:21px; line-height:1.35; letter-spacing:-.018em; }
        .organizer-row { display:flex; align-items:center; gap:10px; margin-top:auto; padding-top:18px; color:var(--muted); font-size:14px; }
        .organizer-row img { width:34px; height:34px; object-fit:contain; background:#f8fafc; border:1px solid var(--line); border-radius:50%; }
        .organizer-name { color:#344054; font-weight:750; }
        .card-stats { display:flex; gap:14px; margin-top:15px; color:#667085; font-size:13px; }
        .empty { grid-column:1/-1; padding:70px 20px; text-align:center; background:white; border:1px dashed #cfd5dc; border-radius:20px; }
        .pagination { display:flex; align-items:center; justify-content:center; gap:14px; margin-top:30px; }
        .page-info { color:#667085; font-size:14px; font-weight:700; }
        footer { padding:28px 5vw; color:#667085; background:white; border-top:1px solid var(--line); text-align:center; font-size:14px; }
        @media(max-width:1000px){ .event-grid{grid-template-columns:repeat(2,minmax(0,1fr));}.main-nav .nav-optional{display:none;} }
        @media(max-width:720px){ .site-header{padding:0 18px}.brand img{height:27px}.main-nav>a:not(.active),.user-chip span{display:none}.page-shell{width:min(100% - 30px,1500px);padding-top:30px}.page-heading{align-items:flex-start;flex-direction:column}.filters{grid-template-columns:1fr}.event-grid{grid-template-columns:1fr}.event-card{border-radius:16px}.card-link{border-radius:16px} }
        @stack('styles')
    </style>
</head>
<body>
    <header class="site-header">
        <a href="{{ url('/portal') }}" class="brand" aria-label="Portal Kopma UGM">
            <img src="{{ asset('images/kopma-brand.png') }}" alt="Kopma UGM">
        </a>
        <nav class="main-nav" aria-label="Navigasi utama">
            <a href="{{ url('/portal') }}" class="nav-optional">Dashboard</a>
            <a href="{{ route('events.index') }}" class="active">Events</a>
            @if($isAdmin ?? false)
                <a href="{{ route('filament.admin.resources.events.index') }}" class="nav-optional">Kelola Event</a>
            @endif
            <div class="user-chip">
                <div class="avatar">
                    @if(auth()->user()->getFilamentAvatarUrl())
                        <img src="{{ auth()->user()->getFilamentAvatarUrl() }}" alt="{{ auth()->user()->name }}">
                    @else
                        {{ mb_substr(auth()->user()->name, 0, 1) }}
                    @endif
                </div>
                <span>{{ auth()->user()->name }}</span>
            </div>
        </nav>
    </header>

    @yield('content')

    <footer>&copy; {{ now()->year }} Koperasi Mahasiswa Universitas Gadjah Mada</footer>
    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events | Kopma UGM</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #090a0c;
            color: #f4f4f5;
            font-family: Arial, sans-serif;
        }

        .navbar {
            display: flex;
            align-items: center;
            gap: 28px;
            min-height: 76px;
            padding: 0 42px;
            background: #18191d;
            border-bottom: 1px solid #2b2d32;
        }

        .brand {
            margin-right: 20px;
            font-size: 28px;
            font-weight: 700;
            color: #34d399;
            text-decoration: none;
        }

        .brand span {
            color: #38bdf8;
        }

        .nav-link {
            padding: 14px 16px;
            color: #e5e7eb;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 12px;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #34d399;
            background: #24262b;
        }

        .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 34px 38px 60px;
        }

        .breadcrumb {
            margin-bottom: 18px;
            color: #a1a1aa;
            font-size: 15px;
        }

        h1 {
            margin: 0 0 26px;
            font-size: 38px;
        }

        .toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }

        .categories {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .button,
        .category-link {
            display: inline-block;
            padding: 12px 17px;
            color: #f4f4f5;
            background: #23252a;
            border: 1px solid #3f4148;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .button:hover,
        .category-link:hover,
        .category-link.active {
            color: #34d399;
            border-color: #10b981;
        }

        .button-primary {
            color: #062f25;
            background: #34d399;
            border-color: #34d399;
        }

        .button-primary:hover {
            color: #062f25;
            background: #6ee7b7;
        }

        .button-danger {
            color: #fca5a5;
            background: transparent;
            border-color: #7f1d1d;
        }

        .button-danger:hover {
            color: #fee2e2;
            background: #7f1d1d;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-input {
            min-width: 260px;
            padding: 12px 14px;
            color: #f4f4f5;
            background: #1d1f23;
            border: 1px solid #45474e;
            border-radius: 10px;
        }

        .panel {
            overflow: hidden;
            background: #191a1e;
            border: 1px solid #2d2f35;
            border-radius: 16px;
        }

        .event-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(285px, 1fr));
            gap: 18px;
            padding: 20px;
        }

        .event-card {
            overflow: hidden;
            background: #232429;
            border: 1px solid #383a41;
            border-radius: 14px;
        }

        .event-image {
            width: 100%;
            height: 170px;
            object-fit: cover;
            background: #303238;
        }

        .event-image-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 170px;
            color: #a1a1aa;
            background: #303238;
        }

        .event-content {
            padding: 16px;
        }

        .badge {
            display: inline-block;
            margin-bottom: 10px;
            padding: 5px 9px;
            border-radius: 999px;
            color: #ecfdf5;
            background: #065f46;
            font-size: 12px;
            font-weight: 700;
            text-transform: capitalize;
        }

        .badge-urgent {
            background: #991b1b;
        }

        .badge-tahunan {
            background: #1d4ed8;
        }

        .event-title {
            margin: 0 0 10px;
            font-size: 19px;
        }

        .event-date,
        .event-description {
            margin: 7px 0;
            color: #c4c4cc;
            font-size: 14px;
            line-height: 1.55;
        }

        .event-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 15px;
        }

        .event-actions form {
            margin: 0;
        }

        .empty-state {
            padding: 75px 20px;
            color: #a1a1aa;
            text-align: center;
        }

        .empty-state strong {
            display: block;
            margin-bottom: 8px;
            color: #f4f4f5;
            font-size: 21px;
        }

        .alert-success {
            margin-bottom: 18px;
            padding: 14px 16px;
            color: #d1fae5;
            background: #064e3b;
            border: 1px solid #059669;
            border-radius: 10px;
        }

        .pagination {
            padding: 18px 20px;
        }

        @media (max-width: 760px) {
            .navbar {
                flex-wrap: wrap;
                gap: 6px;
                padding: 16px 20px;
            }

            .container {
                padding: 28px 18px;
            }

            .search-form {
                width: 100%;
            }

            .search-input {
                min-width: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="/" class="brand">Kopma<span>UGM</span></a>

        <a href="/portal" class="nav-link">Dashboard</a>
        <a href="/portal/documents" class="nav-link">Documents</a>
        <a href="/portal/transactions" class="nav-link">Transactions</a>
        <a href="{{ route('events.index') }}" class="nav-link active">Events</a>
    </nav>

    <main class="container">
        <div class="breadcrumb">Dashboard &nbsp;›&nbsp; Events</div>

        <h1>Events</h1>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="toolbar">
            <div class="categories">
                <a
                    href="{{ route('events.index', ['search' => request('search')]) }}"
                    class="category-link {{ request('category') === null ? 'active' : '' }}"
                >
                    Semua Event
                </a>

                <a
                    href="{{ route('events.index', ['category' => 'urgent', 'search' => request('search')]) }}"
                    class="category-link {{ request('category') === 'urgent' ? 'active' : '' }}"
                >
                    Event Urgent
                </a>

                <a
                    href="{{ route('events.index', ['category' => 'bulanan', 'search' => request('search')]) }}"
                    class="category-link {{ request('category') === 'bulanan' ? 'active' : '' }}"
                >
                    Event Bulanan
                </a>

                <a
                    href="{{ route('events.index', ['category' => 'tahunan', 'search' => request('search')]) }}"
                    class="category-link {{ request('category') === 'tahunan' ? 'active' : '' }}"
                >
                    Event Tahunan
                </a>
            </div>

            <a href="{{ route('events.create') }}" class="button button-primary">
                + Tambah Event
            </a>
        </div>

        <div class="toolbar">
            <form action="{{ route('events.index') }}" method="GET" class="search-form">
                @if (request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Cari judul atau keterangan event..."
                    value="{{ request('search') }}"
                >

                <button type="submit" class="button">Cari</button>

                <a href="{{ route('events.index') }}" class="button">
                    Reset
                </a>
            </form>
        </div>

        <section class="panel">
            @if ($events->count())
                <div class="event-grid">
                    @foreach ($events as $event)
                        <article class="event-card">
                            @if ($event->image)
                                <img
                                    src="{{ asset('storage/' . $event->image) }}"
                                    alt="{{ $event->title }}"
                                    class="event-image"
                                >
                            @else
                                <div class="event-image-placeholder">
                                    Tidak ada gambar
                                </div>
                            @endif

                            <div class="event-content">
                                <span class="badge badge-{{ $event->category }}">
                                    {{ $event->category }}
                                </span>

                                <h2 class="event-title">
                                    {{ $event->title }}
                                </h2>

                                <p class="event-date">
                                    Mulai:
                                    {{ $event->opened_at?->format('d M Y, H:i') ?? '-' }}
                                </p>

                                <p class="event-date">
                                    Selesai:
                                    {{ $event->closed_at?->format('d M Y, H:i') ?? '-' }}
                                </p>

                                <p class="event-description">
                                    {{ $event->description ?: 'Tidak ada keterangan.' }}
                                </p>

                                <div class="event-actions">
                                    @if ($event->url)
                                        <a
                                            href="{{ $event->url }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="button"
                                        >
                                            Buka Link
                                        </a>
                                    @endif

                                    <a
                                        href="{{ route('events.edit', $event) }}"
                                        class="button"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('events.destroy', $event) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus event ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="button button-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="pagination">
                    {{ $events->links() }}
                </div>
            @else
                <div class="empty-state">
                    <strong>Belum ada event</strong>
                    Tambahkan event urgent, bulanan, atau tahunan melalui tombol Tambah Event.
                </div>
            @endif
        </section>
    </main>
</body>
</html>
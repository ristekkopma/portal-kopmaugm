<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event | Kopma UGM</title>

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
            max-width: 920px;
            margin: 0 auto;
            padding: 34px 24px 60px;
        }

        .breadcrumb {
            margin-bottom: 18px;
            color: #a1a1aa;
            font-size: 15px;
        }

        h1 {
            margin: 0 0 24px;
            font-size: 36px;
        }

        .panel {
            padding: 24px;
            background: #191a1e;
            border: 1px solid #2d2f35;
            border-radius: 16px;
        }

        .form-group {
            margin-bottom: 19px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #f4f4f5;
            font-size: 14px;
            font-weight: 700;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px 14px;
            color: #f4f4f5;
            background: #232429;
            border: 1px solid #45474e;
            border-radius: 10px;
            font-size: 14px;
        }

        textarea {
            min-height: 130px;
            resize: vertical;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #10b981;
            outline: none;
        }

        .help-text {
            margin-top: 7px;
            color: #a1a1aa;
            font-size: 12px;
            line-height: 1.5;
        }

        .error-text {
            margin-top: 7px;
            color: #fca5a5;
            font-size: 13px;
        }

        .alert-error {
            margin-bottom: 20px;
            padding: 14px 16px;
            color: #fee2e2;
            background: #7f1d1d;
            border: 1px solid #ef4444;
            border-radius: 10px;
        }

        .alert-error ul {
            margin: 8px 0 0;
            padding-left: 20px;
        }

        .grid-two {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .current-image {
            display: block;
            width: 220px;
            max-width: 100%;
            margin: 0 0 12px;
            border-radius: 10px;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 24px;
        }

        .button {
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

        .button:hover {
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

        @media (max-width: 760px) {
            .navbar {
                flex-wrap: wrap;
                gap: 6px;
                padding: 16px 20px;
            }

            .container {
                padding: 28px 18px;
            }

            .grid-two {
                grid-template-columns: 1fr;
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
        <div class="breadcrumb">
            Dashboard &nbsp;›&nbsp; Events &nbsp;›&nbsp; Edit Event
        </div>

        <h1>Edit Event</h1>

        @if ($errors->any())
            <div class="alert-error">
                <strong>Perubahan belum dapat disimpan.</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="panel">
            <form
                action="{{ route('events.update', $event) }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Judul Event</label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title', $event->title) }}"
                        required
                    >

                    @error('title')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Keterangan Event</label>

                    <textarea
                        id="description"
                        name="description"
                    >{{ old('description', $event->description) }}</textarea>

                    @error('description')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="url">Link Event atau Pendaftaran</label>

                    <input
                        type="url"
                        id="url"
                        name="url"
                        value="{{ old('url', $event->url) }}"
                        placeholder="https://contoh-link-event.com"
                    >

                    @error('url')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category">Kategori Event</label>

                    <select id="category" name="category" required>
                        <option value="">-- Pilih Kategori --</option>

                        @foreach ($categories as $value => $label)
                            <option
                                value="{{ $value }}"
                                {{ old('category', $event->category) === $value ? 'selected' : '' }}
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    @error('category')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="grid-two">
                    <div class="form-group">
                        <label for="opened_at">Tanggal dan Jam Mulai</label>

                        <input
                            type="datetime-local"
                            id="opened_at"
                            name="opened_at"
                            value="{{ old('opened_at', $event->opened_at?->format('Y-m-d\TH:i')) }}"
                            required
                        >

                        @error('opened_at')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="closed_at">Tanggal dan Jam Selesai</label>

                        <input
                            type="datetime-local"
                            id="closed_at"
                            name="closed_at"
                            value="{{ old('closed_at', $event->closed_at?->format('Y-m-d\TH:i')) }}"
                            required
                        >

                        @error('closed_at')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="image">Gambar Event</label>

                    @if ($event->image)
                        <img
                            src="{{ asset('storage/' . $event->image) }}"
                            alt="{{ $event->title }}"
                            class="current-image"
                        >
                    @endif

                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <div class="help-text">
                        Kosongkan apabila gambar lama tidak ingin diganti.
                    </div>

                    @error('image')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="actions">
                    <button type="submit" class="button button-primary">
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('events.index') }}" class="button">
                        Batal
                    </a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
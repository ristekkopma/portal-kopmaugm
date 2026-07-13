<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Terbaru Kopma UGM</title>
</head>
<body style="margin:0;background:#f3f4f6;font-family:Arial,sans-serif;color:#111827">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:24px 12px;background:#f3f4f6">
        <tr><td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:14px;overflow:hidden">
                <tr><td style="padding:28px;background:#047857;color:#ffffff">
                    <div style="font-size:13px;font-weight:700;letter-spacing:.08em">KOPMA UGM</div>
                    <h1 style="margin:8px 0 0;font-size:24px;line-height:1.3">Event Terbaru</h1>
                </td></tr>
                <tr><td style="padding:28px">
                    <p style="margin:0 0 18px">Halo {{ $recipient->name }},</p>
                    <p style="margin:0 0 22px">Kopma UGM memiliki Event terbaru:</p>

                    <h2 style="margin:0 0 18px;font-size:22px">{{ $recipient->event->title }}</h2>
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="7" style="font-size:14px">
                        <tr><td width="130"><strong>Tanggal</strong></td><td>{{ $recipient->event->event_date?->translatedFormat('d F Y') ?? '-' }}</td></tr>
                        <tr><td><strong>Waktu</strong></td><td>{{ $recipient->event->start_time ?: '-' }} – {{ $recipient->event->end_time ?: '-' }}</td></tr>
                        <tr><td><strong>Lokasi</strong></td><td>{{ $recipient->event->location ?: 'Akan diumumkan' }}</td></tr>
                        <tr><td><strong>Penyelenggara</strong></td><td>{{ $recipient->event->organizer_name ?: 'Kopma UGM' }}</td></tr>
                    </table>

                    @if ($recipient->batch->message)
                        <p style="margin:22px 0;line-height:1.7">{{ $recipient->batch->message }}</p>
                    @endif

                    <p style="margin:26px 0 0">
                        <a href="{{ route('events.show', $recipient->event) }}" style="display:inline-block;margin:0 8px 8px 0;padding:12px 18px;border-radius:8px;background:#047857;color:#ffffff;text-decoration:none;font-weight:700">Lihat Detail Event</a>
                        @if ($recipient->event->safe_registration_url)
                            <a href="{{ $recipient->event->safe_registration_url }}" style="display:inline-block;padding:12px 18px;border-radius:8px;background:#e5e7eb;color:#111827;text-decoration:none;font-weight:700">Daftar Event</a>
                        @endif
                    </p>
                </td></tr>
            </table>
        </td></tr>
    </table>
</body>
</html>

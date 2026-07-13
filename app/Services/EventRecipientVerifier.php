<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class EventRecipientVerifier
{
    public function verifyUrl(string $url): array
    {
        $csvUrl = $this->csvUrl(trim($url));
        $this->guardPublicUrl($csvUrl);

        try {
            $response = Http::timeout(15)->connectTimeout(5)->maxRedirects(3)->get($csvUrl);
        } catch (\Throwable) {
            throw ValidationException::withMessages([
                'data.spreadsheet_url' => 'Spreadsheet tidak dapat dibaca. Pastikan aksesnya adalah “Anyone with the link – Viewer”.',
            ]);
        }

        if (! $response->successful() || strlen($response->body()) > 5_000_000) {
            throw ValidationException::withMessages([
                'data.spreadsheet_url' => 'Spreadsheet tidak dapat dibaca atau ukurannya terlalu besar. Pastikan tautan dapat diakses publik.',
            ]);
        }

        if ($effectiveUrl = $response->effectiveUri()) {
            $this->guardPublicUrl((string) $effectiveUrl);
        }

        return $this->verifyCsv($response->body());
    }

    public function verifyCsv(string $csv): array
    {
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $csv);
        rewind($stream);

        $header = fgetcsv($stream);
        $header = array_map(fn ($value): string => strtolower(trim((string) $value, "\xEF\xBB\xBF \t\n\r\0\x0B")), $header ?: []);

        if (count($header) !== 3 || array_diff($header, ['id', 'name', 'email']) || count(array_unique($header)) !== 3) {
            throw ValidationException::withMessages([
                'data.spreadsheet_url' => 'Header spreadsheet harus tepat berisi id, name, dan email.',
            ]);
        }

        $rawRows = [];
        $line = 1;

        while (($values = fgetcsv($stream)) !== false) {
            $line++;
            $columnCount = count($values);
            $values = array_pad($values, 3, '');
            $rawRows[] = ['line' => $line, 'column_count' => $columnCount, ...array_combine($header, array_slice($values, 0, 3))];
        }

        fclose($stream);

        if ($rawRows === []) {
            throw ValidationException::withMessages(['data.spreadsheet_url' => 'Spreadsheet tidak memiliki data penerima.']);
        }

        $ids = collect($rawRows)->pluck('id')->filter(fn ($id) => ctype_digit(trim((string) $id)))->map(fn ($id) => (int) $id);
        $emails = collect($rawRows)->pluck('email')->map(fn ($email) => strtolower(trim((string) $email)))->filter();
        $usersById = User::query()->whereIn('id', $ids)->get()->keyBy('id');
        $usersByEmail = User::query()->whereIn('email', $emails)->get()->keyBy(fn (User $user): string => strtolower(trim($user->email)));
        $seenIds = [];
        $seenEmails = [];
        $rows = [];

        foreach ($rawRows as $index => $raw) {
            $idText = trim((string) $raw['id']);
            $name = $this->normalizeName((string) $raw['name']);
            $email = strtolower(trim((string) $raw['email']));
            $user = ctype_digit($idText) ? $usersById->get((int) $idText) : null;
            $status = 'valid';
            $message = 'Data sesuai.';

            if ($raw['column_count'] !== 3) {
                [$status, $message] = ['failed', 'Setiap baris harus tepat memiliki tiga kolom: id, name, dan email.'];
            } elseif ($idText === '' || $name === '' || $email === '') {
                [$status, $message] = ['failed', 'ID, nama, dan email wajib diisi. Lengkapi baris ini lalu verifikasi ulang.'];
            } elseif (! ctype_digit($idText)) {
                [$status, $message] = ['failed', 'ID harus berupa angka dan sama dengan ID pengguna di database.'];
            } elseif (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                [$status, $message] = ['failed', 'Format email tidak valid. Perbaiki alamat email lalu verifikasi ulang.'];
            } elseif (isset($seenIds[$idText]) || isset($seenEmails[$email])) {
                [$status, $message] = ['duplicate', 'ID atau email muncul lebih dari satu kali. Hapus baris duplikat lalu verifikasi ulang.'];
            } elseif (! $user) {
                $other = $usersByEmail->get($email);
                [$status, $message] = ['failed', $other
                    ? "ID {$idText} tidak sesuai; email tersebut milik pengguna ID {$other->id}."
                    : "Pengguna dengan ID {$idText} tidak ditemukan di database."];
            } elseif (strtolower(trim($user->email)) !== $email) {
                $other = $usersByEmail->get($email);
                [$status, $message] = ['failed', $other
                    ? "ID dan email mengarah ke pengguna berbeda. Email ini milik ID {$other->id}."
                    : "Email tidak sesuai dengan pengguna ID {$idText}. Gunakan email database: {$user->email}."];
            } elseif ($this->normalizeName($user->name) !== $name) {
                [$status, $message] = ['failed', "Nama tidak sesuai. Gunakan nama database: {$user->name}."];
            }

            if ($idText !== '') {
                $seenIds[$idText] = true;
            }
            if ($email !== '') {
                $seenEmails[$email] = true;
            }

            $rows[] = [
                'number' => $index + 1,
                'line' => $raw['line'],
                'id' => $idText,
                'spreadsheet_name' => trim((string) $raw['name']),
                'database_name' => $user?->name,
                'spreadsheet_email' => trim((string) $raw['email']),
                'database_email' => $user?->email,
                'user_id' => $user?->id,
                'status' => $status,
                'message' => $message,
            ];
        }

        return [
            'total' => count($rows),
            'valid' => collect($rows)->where('status', 'valid')->count(),
            'failed' => collect($rows)->where('status', 'failed')->count(),
            'duplicate' => collect($rows)->where('status', 'duplicate')->count(),
            'rows' => $rows,
        ];
    }

    private function csvUrl(string $url): string
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw ValidationException::withMessages(['data.spreadsheet_url' => 'Masukkan URL Google Spreadsheet atau CSV yang valid.']);
        }

        if (preg_match('~^https://docs\.google\.com/spreadsheets/d/([^/]+)~', $url, $match)) {
            parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
            parse_str((string) parse_url($url, PHP_URL_FRAGMENT), $fragment);
            $gid = $query['gid'] ?? $fragment['gid'] ?? '0';

            return "https://docs.google.com/spreadsheets/d/{$match[1]}/export?format=csv&gid=".urlencode((string) $gid);
        }

        return $url;
    }

    private function guardPublicUrl(string $url): void
    {
        $scheme = parse_url($url, PHP_URL_SCHEME);
        $host = parse_url($url, PHP_URL_HOST);

        if (! in_array($scheme, ['http', 'https'], true) || ! $host || parse_url($url, PHP_URL_USER)) {
            throw ValidationException::withMessages(['data.spreadsheet_url' => 'URL spreadsheet tidak diizinkan.']);
        }

        foreach (gethostbynamel($host) ?: [] as $ip) {
            if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                throw ValidationException::withMessages(['data.spreadsheet_url' => 'URL spreadsheet harus mengarah ke alamat publik.']);
            }
        }
    }

    private function normalizeName(string $name): string
    {
        return mb_strtolower(preg_replace('/\s+/u', ' ', trim($name)) ?? '');
    }
}

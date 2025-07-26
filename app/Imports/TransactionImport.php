<?php

namespace App\Imports;

use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TransactionImport implements OnEachRow, WithHeadingRow
{
    public int $duplicateCount = 0;

    public function onRow(Row $row)
    {
        $r = $row->toArray();

        // Inisialisasi wallet null
        $wallet = null;

        // Cek apakah pakai 'nak'
        if (isset($r['nak'])) {
            $wallet = Wallet::whereHas('user.member', function ($query) use ($r) {
                $query->where('code', $r['nak']);
            })->first();
        }

        // Jika belum ketemu dan ada kolom 'nama', cari pakai nama
        if (!$wallet && isset($r['nama'])) {
            $wallet = Wallet::whereHas('user', function ($query) use ($r) {
                $query->where('name', $r['nama']);
            })->first();
        }

        // Jika tetap tidak ketemu, log dan skip
        if (!$wallet) {
            Log::warning("Wallet tidak ditemukan untuk baris: " . json_encode($r));
            return;
        }

                $duplikat = Transaction::where('wallet_id', $wallet->id)
            ->where('amount', (int) $r['nominal'])
            ->where('reference', strtolower($r['referensi']))
            ->whereDate('transacted_at', Carbon::parse($r['tanggal']))
            ->exists();

        if ($duplikat) {
            $this->hasDuplicates = true; // Flag aktif
            return;
        }


        // Simpan transaksi
        Transaction::create([
            'wallet_id' => $wallet->id,
            'type' => strtolower($r['jenis']) === 'debit',
            'amount' => (int) $r['nominal'],
            'reference' => strtolower($r['referensi']),
            'payment_method' => strtolower($r['metode_pembayaran']),
            'note' => $r['catatan'] ?? null,
            'transacted_at' => Carbon::parse($r['tanggal']),
        ]);
    }
}

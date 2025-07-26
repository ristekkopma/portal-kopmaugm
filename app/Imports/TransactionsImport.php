<?php

namespace App\Imports;

use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class TransactionsImport implements ToModel, WithHeadingRow
{
    public int $duplicateCount = 0;

    public function model(array $row)
    {
        // Temukan user dari nama / nak
        $user = User::where('name', $row['nama'])->first();

        if (!$user) {
            // Jika user tidak ditemukan, abaikan baris
            return null;
        }

        $tanggal = Carbon::parse($row['tanggal'])->format('Y-m-d');

        // Cek duplikat berdasarkan kombinasi unik
        $duplicate = Transaction::where('wallet_id', $user->wallet->id ?? null)
            ->where('amount', $row['nominal'])
            ->where('reference', $row['referensi'])
            ->whereDate('transacted_at', $tanggal)
            ->exists();

        if ($duplicate) {
            $this->duplicateCount++;
            return null;
        }

        return new Transaction([
            'wallet_id'      => $user->wallet->id ?? null,
            'transacted_at'  => $tanggal,
            'type'           => strtolower($row['jenis']),
            'amount'         => $row['nominal'],
            'reference'      => $row['referensi'],
            'payment_method' => $row['metode_pembayaran'],
            'note'           => $row['catatan'] ?? null,
        ]);
    }
}

    
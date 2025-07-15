<?php

namespace App\Imports;

use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TransactionImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $wallet = Wallet::whereRelation('user', 'name', $row['nama'])->first();

        if (!$wallet) return null;

        return new Transaction([
            'wallet_id' => $wallet->id,
            'type' => $row['jenis'] === 'debit',
            'amount' => $row['nominal'],
            'reference' => $row['referensi'],
            'payment_method' => $row['metode_pembayaran'],
            'note' => $row['catatan'] ?? null,
            'transacted_at' => Carbon::parse($row['tanggal']),
        ]);
    }
}

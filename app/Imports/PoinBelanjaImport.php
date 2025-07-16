<?php
namespace App\Imports;

use App\Models\PoinBelanja;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;


class PoinBelanjaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $r = $row;

        // Coba cari user berdasarkan NAK jika tersedia
        if (isset($row['nak'])) {
            $user = User::whereRelation('member', 'code', $row['nak'])->first();
        }
        // Jika tidak ada 'nak', coba cari berdasarkan nama
        elseif (isset($row['nama'])) {
            $user = User::where('name', $row['nama'])->first();
        } else {
            return null; // Gagal, tidak ada kolom 'nak' atau 'nama'
        }

        if (!$user) {
            return null;
        }

        $nominal = (float) $row['nominal'];
        $poin = floor($nominal / 10000); // Misal aturan: 10rb = 1 poin

        $duplikat = PoinBelanja::where('user_id', $user->id)
            ->where('nominal', (int) $r['nominal'])
            ->where('usaha', $r['usaha'])
            ->whereDate('tanggal_transaksi', Carbon::parse($r['tanggal_transaksi']))
            ->exists();

        if ($duplikat) {
            $this->hasDuplicates = true; // Flag aktif
            return;
        }

        
        return new PoinBelanja([
            'user_id' => $user->id,
            'nominal' => $nominal,
            'usaha' => $row['usaha'],
            'tanggal_transaksi' => Carbon::parse($row['tanggal_transaksi']),
            'total_poin' => $poin,
        ]);
    }
}

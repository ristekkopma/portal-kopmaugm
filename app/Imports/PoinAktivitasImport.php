<?php

namespace App\Imports;

use App\Models\PoinAktivitas;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;


class PoinAktivitasImport implements ToModel, WithHeadingRow
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

        if ($duplikat) {
            $this->hasDuplicates = true; // Flag aktif
            return;
        }

        $duplikat = PoinAktivitas::where('user_id', $user->id)
    ->where('nama_kegiatan', $r['nama_kegiatan'])
    ->where('jumlah_poin', (int) $r['jumlah_poin'])
    ->whereDate('tanggal_kegiatan', Carbon::parse($r['tanggal_kegiatan']))
    ->exists();

if ($duplikat) {
    Log::info('Poin Aktivitas duplikat dilewati: ' . json_encode($r));
    return;
}


        return new PoinAktivitas([
            'user_id' => $user->id,
            'nama_kegiatan' => $row['nama_kegiatan'],
            'jumlah_poin' => $row['jumlah_poin'],
            'tanggal_kegiatan' => Carbon::parse($row['tanggal_kegiatan']),
        ]);
    }
}

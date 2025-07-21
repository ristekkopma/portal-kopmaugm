<?php

namespace App\Imports;

use App\Models\Member;
use App\Models\User;
use App\Enums\MemberStatus;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MemberImport implements ToModel, WithHeadingRow
{
    public bool $hasDuplicates = false;

    public function model(array $row)
    {
        $code  = trim($row['code']);
        $nama  = trim($row['nama_pengguna']);
        $type  = $row['type'] ?? null;
        $recruitmentStatus = $row['recruitment_status'] ?? null;
        $statusString = strtolower(trim($row['status']));

        // Konversi tanggal jika ada
        $registeredAt    = $this->parseDate($row['registered_at'] ?? null);
        $interviewAt     = $this->parseDate($row['interview_at'] ?? null);
        $joinedAt        = $this->parseDate($row['joined_at'] ?? null);
        $changeTypeAt    = $this->parseDate($row['change_type_at'] ?? null);

        // Cari user berdasarkan nama
        $user = User::whereRaw('LOWER(name) = ?', [strtolower($nama)])->first();

        if (!$user) {
            return null;
        }

        try {
            $status = MemberStatus::from($statusString);
        } catch (\ValueError $e) {
            return null;
        }

        // Deteksi duplikat
        $exists = Member::where('code', $code)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            $this->hasDuplicates = true;
            return null;
        }

        return new Member([
            'code'               => $code,
            'user_id'            => $user->id,
            'type'               => $type,
            'recruitment_status' => $recruitmentStatus,
            'status'             => $status,
            'registered_at'      => $registeredAt,
            'interview_at'       => $interviewAt,
            'joined_at'          => $joinedAt,
            'change_type_at'     => $changeTypeAt,
        ]);
    }

    private function parseDate($value)
    {
        try {
            return $value ? Carbon::createFromFormat('d-m-Y H:i:s', $value) : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}

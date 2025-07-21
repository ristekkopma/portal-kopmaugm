<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    public int $duplicateCount = 0;

    public function model(array $row)
    {
        $name  = trim($row['nama']);
        $email = trim($row['email']);
        $phone = trim($row['telepon'] ?? '');
        $role  = strtolower(trim($row['role']));
        $pass  = trim($row['password']);

        $duplicate = User::where('email', $email)
            ->orWhere(function ($q) use ($name, $phone) {
                $q->where('name', $name)
                  ->where('phone', $phone);
            })
            ->exists();

        if ($duplicate) {
            $this->duplicateCount++;
            return null;
        }

        return new User([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make($pass),
            'role' => $role,
            'email_verified_at' => now(),
        ]);
    }
}

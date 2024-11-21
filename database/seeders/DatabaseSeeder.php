<?php

namespace Database\Seeders;

use App\Enums\MemberType;
use App\Enums\RecruitmentStatus;
use App\Enums\UserRole;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'nik' => fake()->nik(),
            'phone' => fake()->phoneNumber(),
            'email' => 'test@example.com',
            'role' => UserRole::SuperAdmin
        ]);

        $user->wallet()->create([
            'balance' => 0
        ]);

        $user->member()->create([
            'code' => fake()->numberBetween(100000, 999999),
            'type' => MemberType::Regular,
            'joined_at' => now(),
            'registered_at' => now(),
            'recruitment_status' => RecruitmentStatus::Approved,
            'status' => true
        ]);
    }
}

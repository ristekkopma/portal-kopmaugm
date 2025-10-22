<?php

namespace App\Models;

use App\Enums\UserRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name',
    'email',
    'phone',
    'password',
    'avatar',
    'role',
    'is_verified', // ✅ ini penting supaya update bisa dilakukan
];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_verified' => 'boolean', // ✅ Tambahkan agar mudah dipakai logika
        ];
    }

    // Ubah nama menjadi huruf besar otomatis
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = strtoupper($value);
    }

    /**
     * Batasi akses panel berdasarkan peran
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            // Admin panel hanya untuk pengurus
            return $this->role !== UserRole::Candidate && $this->role !== UserRole::Member;
        }

        if ($panel->getId() === 'portal') {
            // Semua user bisa masuk portal, tapi kita batasi nanti berdasarkan is_verified
            return true;
        }

        return false;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }

    // === RELASI ===
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function savingCycleMember(): HasMany
    {
        return $this->hasMany(SavingCycleMember::class);
    }
}

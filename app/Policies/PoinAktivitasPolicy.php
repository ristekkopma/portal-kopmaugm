<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\PoinAktivitas;
use App\Models\User;

class PoinAktivitasPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function view(User $user, PoinAktivitas $poin): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function update(User $user, PoinAktivitas $poin): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function delete(User $user, PoinAktivitas $poin): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function restore(User $user, PoinAktivitas $poin): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function forceDelete(User $user, PoinAktivitas $poin): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }
}

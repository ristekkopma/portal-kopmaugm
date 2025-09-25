<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\PoinBelanja;
use App\Models\User;

class PoinBelanjaPolicy
{
    public function viewAny(User $user): bool
    {
        // SuperAdmin & Member boleh lihat
        return in_array($user->role, [UserRole::SuperAdmin, UserRole::Member]);
    }

    public function view(User $user, PoinBelanja $poin): bool
    {
        // SuperAdmin bisa lihat semua
        if ($user->role === UserRole::SuperAdmin) {
            return true;
        }

        // Member hanya bisa lihat poin miliknya sendiri
        return $user->role === UserRole::Member && $poin->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function update(User $user, PoinBelanja $poin): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function delete(User $user, PoinBelanja $poin): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function restore(User $user, PoinBelanja $poin): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function forceDelete(User $user, PoinBelanja $poin): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }
}

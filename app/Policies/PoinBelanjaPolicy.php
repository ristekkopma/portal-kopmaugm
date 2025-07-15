<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\PoinBelanja;
use App\Models\User;

class PoinBelanjaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function view(User $user, PoinBelanja $poin): bool
    {
        return $user->role === UserRole::SuperAdmin;
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

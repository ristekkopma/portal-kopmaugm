<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\EventFollower;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class EventFollowerPolicy
{
    public function viewAny(User $user): bool
    {
        return Gate::forUser($user)->allows('view_event_followers');
    }

    public function view(User $user, EventFollower $eventFollower): bool
    {
        return Gate::forUser($user)->allows('view_event_followers');
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, EventFollower $eventFollower): bool
    {
        return Gate::forUser($user)->allows('manage_event_followers');
    }

    public function delete(User $user, EventFollower $eventFollower): bool
    {
        return Gate::forUser($user)->allows('manage_event_followers');
    }

    public function restore(User $user, EventFollower $eventFollower): bool
    {
        return Gate::forUser($user)->allows('manage_event_followers');
    }

    public function forceDelete(User $user, EventFollower $eventFollower): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }
}

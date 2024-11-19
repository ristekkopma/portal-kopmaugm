<?php

namespace App\Observers;

use App\Models\Member;
use App\Models\Wallet;

class MemberObserver
{
    /**
     * Handle the Member "created" event.
     */
    public function created(Member $member): void
    {
        //
    }

    /**
     * Handle the Member "updated" event.
     */
    public function updated(Member $member): void
    {
        //
    }

    /**
     * Handle the Member "deleted" event.
     */
    public function deleted(Member $member): void
    {
        if ($member->user->wallet) {
            $member->user->wallet->delete();
        }
    }

    /**
     * Handle the Member "restored" event.
     */
    public function restored(Member $member): void
    {
        if ($member->user->wallet) {
            $member->user->wallet->restore();
        }
    }

    /**
     * Handle the Member "force deleted" event.
     */
    public function forceDeleted(Member $member): void
    {
        if ($member->user->wallet) {
            $member->user->wallet->forceDelete();
        }
    }
}

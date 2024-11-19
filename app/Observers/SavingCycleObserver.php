<?php

namespace App\Observers;

use App\Models\Member;
use App\Models\SavingCycle;

class SavingCycleObserver
{
    /**
     * Handle the SavingCycle "created" event.
     */
    public function created(SavingCycle $savingCycle): void
    {
        Member::activeMember()->each(function (Member $member) use ($savingCycle) {
            return $member->savingCycleMember()->create([
                'user_id' => $member->user_id,
                'member_id' => $member->id,
                'saving_cycle_id' => $savingCycle->id,
                'amount' => $savingCycle->default_amount,
            ]);
        });
    }

    /**
     * Handle the SavingCycle "updated" event.
     */
    public function updated(SavingCycle $savingCycle): void
    {
        //
    }

    /**
     * Handle the SavingCycle "deleted" event.
     */
    public function deleted(SavingCycle $savingCycle): void
    {
        //
    }

    /**
     * Handle the SavingCycle "restored" event.
     */
    public function restored(SavingCycle $savingCycle): void
    {
        //
    }

    /**
     * Handle the SavingCycle "force deleted" event.
     */
    public function forceDeleted(SavingCycle $savingCycle): void
    {
        //
    }
}

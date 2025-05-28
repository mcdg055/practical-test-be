<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $causer = auth()->user();

        activity()
            ->performedOn($user)
            ->causedBy($causer)
            ->withProperties($user->getAttributes())
            ->log("$causer->name created user $user->name");
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $changed = $user->getChanges();
        $causer = auth()->user();

        unset($changed['updated_at']);

        //get only what has changed
        $oldValues = collect($user->getOriginal())
            ->only(array_keys($changed));

        if ($user->wasChanged('password')) {
            $changed['password'] = '******'; // Mask password changes
            $oldValues['password'] = '******'; // Mask old password
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties([
                'old' => $oldValues,
                'new' => $changed,
            ])
            ->log("$causer->name updated user $user->name details.");
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $causer = auth()->user();

        activity()
            ->performedOn($user)
            ->causedBy($causer)
            ->withProperties($user->getAttributes())
            ->log("$causer->name deleted user $user->name");
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}

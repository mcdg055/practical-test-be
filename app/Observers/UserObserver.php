<?php

namespace App\Observers;

use App\Enum\LogAction;
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
            ->withProperties([
                'attributes' => $user->getAttributes(),
                'type' => LogAction::CREATED,
            ])
            ->log("Created user name $user->name");
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
            ->causedBy($causer)
            ->performedOn($user)
            ->withProperties([
                'attributes' => [
                    'old' => $user->getOriginal(),
                    'new' => $changed,

                ],
                'type' => LogAction::UPDATED,
            ])
            ->log("Updated user $user->name details");
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
            ->withProperties([
                'attributes' => $user->getAttributes(),
                'type' => LogAction::DELETED,
            ])
            ->log("Deleted a user name $user->name");
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

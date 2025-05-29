<?php

namespace App\Observers;

use App\Enum\LogAction;
use App\Models\IPAddress;

class IPAddressObserver
{
    /**
     * Handle the IPAddress "created" event.
     */
    public function created(IPAddress $ipAddress): void
    {
        $causer = auth()->user();

        activity()
            ->performedOn($ipAddress)
            ->causedBy($causer)
            ->withProperties([
                'attributes' => $ipAddress->getAttributes(),
                'type' => LogAction::CREATED,
            ])
            ->log("Added IP address with label $ipAddress->label");
    }

    /**
     * Handle the IPAddress "updated" event.
     */
    public function updated(IPAddress $ipAddress): void
    {
        $changed = $ipAddress->getChanges();
        $causer = auth()->user();

        unset($changed['updated_at']);

        //get only what has changed
        $oldValues = collect($ipAddress->getOriginal())
            ->only(array_keys($changed));

        activity()
            ->causedBy($causer)
            ->performedOn($ipAddress)
            ->withProperties([
                'attributes' => [
                    'old' => $oldValues,
                    'new' => $changed,

                ],
                'type' => LogAction::UPDATED,
            ])
            ->log("Updated IP address with label $ipAddress->label");
    }

    /**
     * Handle the IPAddress "deleted" event.
     */
    public function deleted(IPAddress $ipAddress): void
    {
        $causer = auth()->user();

        activity()
            ->performedOn($ipAddress)
            ->causedBy($causer)
            ->withProperties([
                'attributes' => $ipAddress->getAttributes(),
                'type' => LogAction::DELETED,
            ])
            ->log("Deleted IP Address with label $ipAddress->label");
    }

    /**
     * Handle the IPAddress "restored" event.
     */
    public function restored(IPAddress $ipAddress): void
    {
        //
    }

    /**
     * Handle the IPAddress "force deleted" event.
     */
    public function forceDeleted(IPAddress $ipAddress): void
    {
        //
    }
}

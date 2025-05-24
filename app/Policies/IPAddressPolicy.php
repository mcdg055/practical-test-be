<?php

namespace App\Policies;

use App\Models\IPAddress;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IPAddressPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, IPAddress $iPAddress): bool
    {
        return $user->hasRole('Super Admin') || $user->id === $iPAddress->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, IPAddress $iPAddress): bool
    {
        return $user->hasRole('Super Admin');
    }
}

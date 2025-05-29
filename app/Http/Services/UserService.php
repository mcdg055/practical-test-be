<?php


namespace App\Http\Services;

use App\Models\User;
use App\Enum\LogAction;
use App\Models\IPAddress;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function browse(array $data)
    {
        $page = Arr::get($data, 'page', 1);
        $perPage = Arr::get($data, 'perPage', 10);
        $search = Arr::get($data, 'search', '');

        return User::with('roles')
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orderBy('created_at', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function edit(array $data, User $user)
    {
        if ($password = Arr::get($data, 'password')) {
            $data['password'] = Hash::make($password);
        } else {
            unset($data['password']);
        }

        $user->fill($data);

        $oldRoles = $user->roles->pluck('name')->toArray();
        $newRoles = Arr::get($data, 'roles', []);

        $user->syncRoles($newRoles);

        $user->save();

        $causer = auth()->user();

        if ($oldRoles !== $newRoles) {
            activity()
                ->performedOn($user)
                ->causedBy($causer)
                ->withProperties(
                    [
                        'attributes' => ['old' => $oldRoles, 'new' => $newRoles],
                        'type' => LogAction::UPDATED
                    ]
                )
                ->log("$causer->name updated user roles for $user->name");
        }

        return $user->load('roles');
    }
}

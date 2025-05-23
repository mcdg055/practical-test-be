<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UserPatchRequest;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Requests\Users\UserPostRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function browse(Request $request)
    {
        if (!$request->user()->can('viewAny', User::class)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->all();

        $page = Arr::get($data, 'page', 1);
        $perPage = Arr::get($data, 'perPage', 10);
        $search = Arr::get($data, 'search', '');

        $users = User::with('roles')
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orderBy('created_at', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);

        return UserResource::collection($users);
    }

    public function read(Request $request, User $user)
    {
        if (!$request->user()->can('view', $user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new UserResource($user->load('roles'));
    }

    public function edit(UserPatchRequest $request, User $user)
    {
        $data = $request->all();

        if ($password = Arr::get($data, 'password')) {
            $data['password'] = Hash::make($password);
        } else {
            unset($data['password']);
        }

        $user->fill($data);

        $user->syncRoles($request->roles);
        $user->save();

        return new UserResource($user->load('roles'));
    }

    public function add(UserPostRequest $request)
    {
        $user = User::create($request->all())->assignRole($request->roles);
        return new UserResource($user->load('roles'));
    }

    public function delete(Request $request, User $user)
    {
        if (!$request->user()->can('delete', $user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user->delete();

        return response()->json($user, 200);
    }

    public function browseRoles(Request $request)
    {
        return response()->json(Role::pluck('name')->toArray());
    }
}

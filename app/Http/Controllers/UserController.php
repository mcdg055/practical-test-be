<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enum\LogAction;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Services\UserService;
use Spatie\Permission\Models\Role;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Users\UserPostRequest;
use App\Http\Requests\Users\UserPatchRequest;

class UserController extends Controller
{
    public function __construct(private UserService $service) {}

    public function browse(Request $request)
    {
        if (!$request->user()->can('viewAny', User::class)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return UserResource::collection($this->service->browse($request->all()));
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
        return new UserResource($this->service->edit($request->all(), $user));
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

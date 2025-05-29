<?php

namespace App\Http\Controllers;

use App\Enum\LogAction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $refreshToken = JWTAuth::fromUser(Auth::user());

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'attributes' => [
                    'ip' => request()->ip(),
                    'user_agent' => request()->header('User-Agent'),
                ],
                'type' => LogAction::LOGGED_IN
            ])
            ->log('Has logged in');

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => new UserResource($user->load('roles', 'permissions')),
            'authorization' => [
                'token' => $token,
                'refresh_token' => $refreshToken,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        activity()
            ->causedBy(Auth::user())
            ->withProperties([
                'attributes' => [
                    'ip' => request()->ip(),
                    'user_agent' => request()->header('User-Agent'),
                ],
                'type' => LogAction::LOGGED_OUT
            ])
            ->log('Has logged out');

        Auth::logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();

            return response()->json([
                'user' => JWTAuth::user(),
                'authorization' => [
                    'token' => $newToken,
                    'type' => 'bearer',
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
    }

    public function me()
    {
        return new UserResource(Auth::user()->load('roles', 'permissions'));
    }
}

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/auth/refresh', [AuthController::class, 'refresh'])->name('refresh');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
    Route::get( '/me',      [AuthController::class, 'me'])->name('user.me');

    //Users
    Route::get(     '/users',           [UserController::class, 'browse'])->middleware('role:Super Admin')->name('users.user.browse');
    Route::get(     '/users/{user}',    [UserController::class, 'read'])->name('users.user.read');
    Route::post(    '/users',           [UserController::class, 'add'])->middleware('role:Super Admin')->name('users.user.add');
    Route::patch(   '/users/{user}',    [UserController::class, 'edit'])->name('users.user.edit');
    Route::delete(  '/users/{user}',    [UserController::class, 'delete'])->middleware('role:Super Admin')->name('users.user.delete');

    //Roles
    Route::get(     '/roles',           [UserController::class, 'browseRoles'])->middleware('role:Super Admin')->name('users.role.browse');
});

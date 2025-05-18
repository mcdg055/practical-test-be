<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::get( '/me',      [AuthController::class, 'me'])->name('user.me');

    //Users
    Route::get(     '/users',           [App\Http\Controllers\UserController::class, 'browse'])->middleware('role:Super Admin')->name('users.user.browse');
    Route::get(     '/users/{user}',    [App\Http\Controllers\UserController::class, 'read'])->name('users.user.read');
    Route::post(    '/users',           [App\Http\Controllers\UserController::class, 'add'])->middleware('role:Super Admin')->name('users.user.add');
    Route::patch(   '/users/{user}',    [App\Http\Controllers\UserController::class, 'edit'])->name('users.user.edit');
    Route::delete(  '/users/{user}',    [App\Http\Controllers\UserController::class, 'delete'])->middleware('role:Super Admin')->name('users.user.delete');
});

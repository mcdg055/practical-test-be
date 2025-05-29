<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IPAddressController;
use App\Http\Controllers\ActivityLogController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/auth/refresh', [AuthController::class, 'refresh'])->name('refresh');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
    Route::get( '/me',      [AuthController::class, 'me'])->name('user.me');
    
    //Roles
    Route::get(     '/roles',           [UserController::class, 'browseRoles'])->middleware('role:Super Admin')->name('users.role.browse');
  
    //Users
    Route::get(     '/users',           [UserController::class, 'browse'])->middleware('role:Super Admin')->name('users.user.browse');
    Route::get(     '/users/{user}',    [UserController::class, 'read'])->name('users.user.read');
    Route::post(    '/users',           [UserController::class, 'add'])->middleware('role:Super Admin')->name('users.user.add');
    Route::patch(   '/users/{user}',    [UserController::class, 'edit'])->name('users.user.edit');
    Route::delete(  '/users/{user}',    [UserController::class, 'delete'])->middleware('role:Super Admin')->name('users.user.delete');
    
    //IP Address
    Route::get(     '/ip-addresses',           [IPAddressController::class, 'browse'])->name('ip-addresses.ip-address.browse');
    Route::patch(   '/ip-addresses/{ip}',           [IPAddressController::class, 'edit'])->name('ip-addresses.ip-address.edit');
    Route::post(    '/ip-addresses',           [IPAddressController::class, 'add'])->name('ip-addresses.ip-address.add');
    Route::delete(  '/ip-addresses/{ip}',           [IPAddressController::class, 'delete'])->name('ip-addresses.ip-address.delete');
    
    // Activity Log
    Route::get('/activity-logs', [ActivityLogController::class, 'browse'])->name('activity-logs.browse')->middleware(['role:Super Admin']);
});

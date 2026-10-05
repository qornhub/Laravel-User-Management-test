<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


Route::delete('/users/bulk', [UserController::class, 'bulkDestroy'])
    ->name('api.users.bulk-destroy');
    
Route::apiResource('users', UserController::class)
    ->only(['store', 'index', 'show', 'destroy']);


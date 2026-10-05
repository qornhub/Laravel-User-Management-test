<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('login');
});


Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/dashboard', [UserController::class, 'index'])
        ->name('admin.dashboard');

    Route::delete('/admin/users/bulk', [UserController::class, 'bulkDestroy'])
        ->name('admin.users.bulk-destroy');

    Route::get('/admin/users/export', [UserController::class, 'export'])
        ->name('admin.users.export');

    Route::resource('admin/users', UserController::class)
        ->except(['show'])
        ->names('admin.users');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

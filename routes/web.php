<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', App\Http\Livewire\Home::class)->name('home');
Route::get('/clear-cache', [App\Http\Controllers\CacheController::class, 'index']);
Route::get('/storage-link', [App\Http\Controllers\CacheController::class, 'storageLink']);

Route::get('/registration', App\Http\Livewire\Registrations::class)->name('registration');

Route::middleware('auth:web')
    ->group(function () {
        // Route::get('/profile', App\Http\Livewire\Profiles::class)->name('profile');
    });

Route::middleware('auth:web')
    ->group(function () {
        // Route::get('prints/admit-card', [App\Http\Controllers\PrintController::class, 'printAdmitCard'])->name('prints.admit-card');
    });

// Auth::routes();
Auth::routes([
    'register' => false,
    'verify' => false,
    'reset' => false
]);


require_once "dashboard.php";
require_once "admin.php";

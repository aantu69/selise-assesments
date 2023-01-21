<?php

use UniSharp\LaravelFilemanager\Lfm;
use Illuminate\Support\Facades\Route;

// Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'middleware' => 'auth'], function () {
//     Route::get('/', App\Http\Livewire\Dashboard\Home::class);
//     Route::get('home', App\Http\Livewire\Dashboard\Home::class)->name('home');
//     Route::get('change-password', App\Http\Livewire\Dashboard\PasswordChanges::class)->name('change-password');
//     Route::get('user-profile', App\Http\Livewire\Dashboard\UserProfiles::class)->name('user-profile');
//     Route::get('users', App\Http\Livewire\Dashboard\Users::class)->name('users');
//     Route::get('user-permissions', App\Http\Livewire\Dashboard\UserPermissions::class)->name('user-permissions');
//     Route::get('user-roles', App\Http\Livewire\Dashboard\UserRoles::class)->name('user-roles');

//     Route::group(['prefix' => 'filemanager'], function () {
//         Lfm::routes();
//     });
// });


Route::prefix('dashboard')
    ->as('dashboard.')
    ->middleware('auth:web')
    ->group(function () {
        Route::get('/', App\Http\Livewire\Dashboard\Home::class)->name('home');
        Route::get('change-password', App\Http\Livewire\Dashboard\PasswordChanges::class)->name('change-password');
        Route::get('user-profile', App\Http\Livewire\Dashboard\UserProfiles::class)->name('user-profile');
        Route::get('users', App\Http\Livewire\Dashboard\Users::class)->name('users');

        Route::prefix('filemanager')
            ->group(function () {
                Lfm::routes();
            });
    });

Route::prefix('dashboard')
    ->as('dashboard.')
    ->middleware('auth:web')
    ->group(function () {
        // Route::get('prints/notice/{notice}', [App\Http\Controllers\Dashboard\PrintController::class, 'printNotice'])->name('prints.notice');
    });

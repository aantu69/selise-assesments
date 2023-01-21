<?php

use UniSharp\LaravelFilemanager\Lfm;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
        Route::get('register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
        // Route::get('password/reset', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        // Route::get('password/reset/{token}', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::get('email/verify', [App\Http\Controllers\Admin\Auth\VerificationController::class, 'show'])->name('verification.notice');
        Route::get('email/verify/{id}', [App\Http\Controllers\Admin\Auth\VerificationController::class, 'verify'])->name('verification.verify');
        Route::get('email/resend', [App\Http\Controllers\Admin\Auth\VerificationController::class, 'resend'])->name('verification.resend');
        Route::post('login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
        Route::post('register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'register']);
        Route::post('password/email', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::post('password/reset', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
        Route::post('logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');
    });

Route::prefix('admin')
    ->as('admin.')
    ->middleware('auth:admin')
    ->group(function () {
        Route::get('/', App\Http\Livewire\Admin\Home::class)->name('home');
        Route::get('change-password', App\Http\Livewire\Admin\PasswordChanges::class)->name('change-password');
        Route::get('user-profile', App\Http\Livewire\Admin\UserProfiles::class)->name('user-profile');
        Route::get('admins', App\Http\Livewire\Admin\Admins::class)->name('admins');
        Route::get('admin-permissions', App\Http\Livewire\Admin\AdminPermissions::class)->name('admin-permissions');
        Route::get('admin-roles', App\Http\Livewire\Admin\AdminRoles::class)->name('admin-roles');
        Route::get('users', App\Http\Livewire\Admin\Users::class)->name('users');
        Route::get('user-permissions', App\Http\Livewire\Admin\UserPermissions::class)->name('user-permissions');
        Route::get('user-roles', App\Http\Livewire\Admin\UserRoles::class)->name('user-roles');
        Route::get('books', App\Http\Livewire\Admin\Books::class)->name('books');

        Route::prefix('filemanager')
            ->group(function () {
                Lfm::routes();
            });
    });

<?php

use App\Livewire\Admin\User\UserIndex;
use App\Livewire\Main\ViewFolder;
use App\Models\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    Route::view('test', 'test.test')
        ->middleware(['auth', 'verified'])
        ->name('test');

Route::group(["prefix" => "admin", "as" => "admin."], function () {
    Route::group(["prefix" => "users", "as" => "users."], function () {
        Route::get('/', UserIndex::class)->middleware(['auth', 'verified'])->name('index');
    });
});

Route::group(["prefix" => "main", "as" => "main."], function () {
    Route::group(["prefix" => "folders", "as" => "folders."], function () {
        Route::get('/show/{main_folder_id}', ViewFolder::class)->middleware(['auth', 'verified'])->name('show');
    });
});

Route::get('preview/{id}', function ($id) {
    $file = File::find($id);
    return Storage::response('/uploads/'.$file->file_name, $file->name);
})->name('preview');

Route::get('validate-qr/{id}', function () {
    return "QR validations goes here";
})->name('validate-qr');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';

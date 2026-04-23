<?php
use App\Http\Controllers\DashboardController;
use App\Livewire\Admin\User\UserIndex;
use App\Livewire\Main\AttachQr;
use App\Livewire\Main\Validate;
use App\Livewire\Main\ViewFolder;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

    Route::view('test', 'test.test')
        ->middleware(['auth', 'verified'])
        ->name('test');

Route::group(["prefix" => "admin", "as" => "admin."], function () {
    Route::group(["prefix" => "users", "as" => "users."], function () {
        Route::get('/', UserIndex::class)->middleware(['auth', 'verified'])->name('index');
    });
});

Route::group(["prefix" => "main", "as" => "main."], function () {
    Route::get('get-qr-and-barcode/{id}', function($id) {
        $file = File::find($id);
        return view('downloadQrAndBarcode', ['file' => $file]);
    })->name('get-qr-and-barcode');

    Route::group(["prefix" => "folders", "as" => "folders."], function () {
        Route::get('/show/{main_folder_id}', ViewFolder::class)->middleware(['auth', 'verified'])->name('show');
        Route::get('/attachqr/{main_file_id}', AttachQr::class)->middleware(['auth', 'verified'])->name('attachqr');
    });
});

Route::get('preview/{id}', function ($id) {
    $file = File::find($id);
    $file->createLog(auth()->user()->name.' viewed the file: '.$file->name, auth()->user()->id);
    return Storage::response('/uploads/'.$file->file_name, $file->name);
})->name('preview');

Route::get('validate/{id}', Validate::class)->name('validate-qr');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication() && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';

Route::get("generate-password/{password}", function($password){
    return bcrypt($password);
});

Route::get("pdf-page/{barcode_no}", function(Request $request, $barcode_no){
    $file = File::where("barcode_no", $barcode_no)->first();
    $ff = Storage::response('/uploads/'.$file->file_name, $file->name);
    $fileName = Storage::temporaryUrl('/uploads/' . $file->file_name, now()->addMinutes(60));
    $imagick = new Imagick();
    // return $fileName;
    
    $imagick->readImage($fileName);
    // $imagick->readImage($fileName);
    $imagick = $imagick->flattenImages();
    return "test";
})->name('pdf-page');

<?php

use App\Http\Controllers\DetailKegiatanController;
use App\Http\Controllers\IdentitasSuratController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubUnitkerjaController;
use App\Http\Controllers\UsulanKegiatanController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Isi identitas surat
    Route::get('/identitas/create', [IdentitasSuratController::class, 'create'])->name('identitassurat.create');
    Route::post('/identitas', [IdentitasSuratController::class, 'store'])->name('identitassurat.store');

    //Usulan kegiatan
    Route::get('/usulan', [UsulanKegiatanController::class, 'index'])->name('usulan.index');
    Route::get('/usulan/create/{surat}', [UsulanKegiatanController::class, 'create'])->name('usulan.create');
    Route::post('/usulan', [UsulanKegiatanController::class, 'store'])->name('usulan.store');

    //Isi detail kegiatan untuk usulan kegiatan
    Route::get('/usulan/{usulan}/detail/create', [DetailKegiatanController::class, 'create'])->name('detail.create');
    Route::post('/usulan/{usulan}/detail', [DetailKegiatanController::class, 'store'])->name('detail.store');
    Route::patch('/usulan/{usulan}/status', [UsulanKegiatanController::class, 'updateStatus'])->name('usulan.updateStatus');

    Route::middleware(['role:superadmin'])->group(function () {
        Route::get('/superadmin/usulan/pending', [UsulanKegiatanController::class, 'pendingList'])->name('usulan.pending');
        Route::get('/usulan/{usulankegiatan}/review', [UsulanKegiatanController::class, 'reviewForm'])->name('usulan.review');
        Route::patch('/usulan/{usulankegiatan}/review', [UsulanKegiatanController::class, 'reviewSubmit'])->name('usulan.reviewSubmit');
    });

    // Upload Kop & TTD
    Route::get('/subunitkerja/upload', [SubUnitkerjaController::class, 'edit'])->name('subunitkerja.edit');
    Route::post('/subunitkerja/upload', [SubUnitkerjaController::class, 'update'])->name('subunitkerja.update');

    Route::get('/usulan/{usulankegiatan}/download', [UsulanKegiatanController::class, 'download'])->name('usulan.download');

});

require __DIR__ . '/auth.php';

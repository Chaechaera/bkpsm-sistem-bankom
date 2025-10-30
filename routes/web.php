<?php

use App\Http\Controllers\DetailKegiatanController;
use App\Http\Controllers\IdentitasSuratController;
use App\Http\Controllers\LaporanKegiatanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubUnitkerjaController;
use App\Http\Controllers\UsulanKegiatanController;
use App\Http\Controllers\VerifikasiDokumenController;
use App\Models\Detailkegiatan;
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
    //Route::get('/usulan/{usulan}/detail/update', [DetailKegiatanController::class, 'update'])->name('usulan.update');
    //Route::get('/usulan/{usulan}/edit', [DetailKegiatanController::class, 'edit'])->name('usulan.edit');
    Route::patch('/usulan/{usulan}', [DetailKegiatanController::class, 'update'])->name('usulan.update');
    Route::post('/usulan', [UsulanKegiatanController::class, 'store'])->name('usulan.store');

    //Isi detail kegiatan untuk usulan kegiatan
    Route::get('/usulan/{usulan}/detail/create', [DetailKegiatanController::class, 'create'])->name('detail.create');
    Route::post('/usulan/{usulan}/detail', [DetailKegiatanController::class, 'store'])->name('detail.store');
    Route::patch('/usulan/{usulan}/status', [UsulanKegiatanController::class, 'updateStatus'])->name('usulan.updateStatus');

    // Update progress dan upload hasil kegiatan (admin)
    //Route::get('/usulan/{usulan}/edit-progress', [UsulanKegiatanController::class, 'editProgress'])->name('usulan.editProgress');
    //Route::patch('/usulan/{usulan}/update-progress', [UsulanKegiatanController::class, 'updateProgress'])->name('usulan.updateProgress');
    Route::get('/usulan/{usulankegiatan}/edit-progress', [UsulanKegiatanController::class, 'editProgress'])->name('usulan.editProgress');
    Route::patch('/usulan/{usulankegiatan}/update-progress', [UsulanKegiatanController::class, 'updateProgress'])->name('usulan.updateProgress');
    //Route::patch('/laporan/{laporan}/update-progress', [LaporanKegiatanController::class, 'updateProgress'])->name('laporan.updateProgress');

    // Update progress (admin)
    //Route::get('/usulan/{id}/edit-progress', [UsulanKegiatanController::class, 'editProgress'])->name('usulan.editProgress');
    //Route::patch('/usulan/{id}/update-progress', [UsulanKegiatanController::class, 'updateProgress'])->name('usulan.updateProgress');
    //Route::get('/usulan/{usulankegiatan}/edit-progress', [UsulanKegiatanController::class, 'editProgress'])->name('usulan.editProgress');
    //Route::patch('/laporan/{usulankegiatan}/update-progress', [LaporanKegiatanController::class, 'updateProgress'])->name('laporan.updateProgress');
    Route::patch('/laporan/{laporankegiatan}/update-progress', [LaporanKegiatanController::class, 'updateProgress'])->name('laporan.updateProgress');
    
    Route::middleware(['role:superadmin'])->group(function () {
        Route::get('/superadmin/usulan/pending', [UsulanKegiatanController::class, 'pendingList'])->name('usulan.pending');
        Route::get('/superadmin/usulan/{usulankegiatan}/review', [UsulanKegiatanController::class, 'reviewForm'])->name('usulan.review');
        Route::patch('/superadmin/usulan/{usulankegiatan}/review', [UsulanKegiatanController::class, 'reviewSubmit'])->name('usulan.reviewSubmit');

        Route::get('/superadmin/usulan/preview/{usulankegiatan}', [UsulanKegiatanController::class, 'preview'])->name('usulan.preview');

        Route::get('/superadmin/usulan/{usulankegiatan}/verifikasi-dokumen', [UsulanKegiatanController::class, 'verifikasiDokumen'])->name('usulan.verifikasiDokumen');
        Route::put('/superadmin/usulan/{usulankegiatan}/verifikasi', [UsulanKegiatanController::class, 'verifikasiLaporan'])->name('usulan.verifikasi');

        Route::get('/verifikasi/{id}', [VerifikasiDokumenController::class, 'show'])->name('verifikasi.show');
        Route::post('/verifikasi/{id}', [VerifikasiDokumenController::class, 'update'])->name('verifikasi.update');
    });

    // Upload Kop & TTD
    Route::get('/subunitkerja/upload', [SubUnitkerjaController::class, 'edit'])->name('subunitkerja.edit');
    Route::post('/subunitkerja/upload', [SubUnitkerjaController::class, 'update'])->name('subunitkerja.update');

    Route::get('/usulan/preview/{usulankegiatan}', [UsulanKegiatanController::class, 'preview'])->name('usulan.preview');
    Route::get('/usulan/{usulankegiatan}/download', [UsulanKegiatanController::class, 'download'])->name('usulan.download');

});

require __DIR__ . '/auth.php';

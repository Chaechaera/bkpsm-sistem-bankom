<?php

namespace App\Http\Controllers;

use App\Models\Usulankegiatan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SuperAdminController extends Controller
{

    public function verifikasiDokumen($id)
    {
        $usulankegiatan = Usulankegiatan::with(['subunitkerja.unitkerja', 'laporankegiatan'])->findOrFail($id);

        return Inertia::render('Superadmin/VerifikasiDokumen', [
            'usulankegiatan' => $usulankegiatan,
        ]);
    }
}

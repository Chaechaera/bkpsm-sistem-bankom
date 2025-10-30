<?php

namespace App\Http\Controllers;

use App\Models\LaporanKegiatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LaporanKegiatanController extends Controller
{
    public function updateProgress(Request $request, LaporanKegiatan $laporankegiatan)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat memperbarui progress kegiatan.');
        }

        $validated = $request->validate([
            'statususulan_kegiatan' => 'required|string',
            'dokumenpendukung_kegiatan' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'dokumenPK_kegiatan' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        // Upload dokumen pendukung
        if ($request->hasFile('dokumenpendukung_kegiatan')) {
            $path1 = $request->file('dokumenpendukung_kegiatan')->store('dokumen_pendukung', 'public');
            $laporankegiatan->dokumenpendukung_kegiatan = $path1;
        }

        // Upload dokumen PK
        if ($request->hasFile('dokumenPK_kegiatan')) {
            $path2 = $request->file('dokumenPK_kegiatan')->store('dokumen_pengembangankompetensi', 'public');
            $laporankegiatan->dokumenPK_kegiatan = $path2;
        }

        // Update status laporan
        if ($validated['statususulan_kegiatan'] === 'completed') {
            $laporankegiatan->statuslaporan_kegiatan = 'Accepted';
        } else {
            $laporankegiatan->statuslaporan_kegiatan = 'Rejected';
        }
        $laporankegiatan->save();

        // Update status usulan kegiatan juga
        if ($laporankegiatan->usulankegiatan) {
            $laporankegiatan->usulankegiatan->update([
                'statususulan_kegiatan' => $validated['statususulan_kegiatan'],
            ]);
        } else {
            // Fallback kalau relasi belum terhubung
            DB::table('usulankegiatans')
                ->where('id', $laporankegiatan->usulankegiatan_id)
                ->update(['statususulan_kegiatan' => $validated['statususulan_kegiatan']]);
        }


        return redirect()->route('usulan.index')
            ->with('success', 'Progress kegiatan berhasil diperbarui.');
    }
}

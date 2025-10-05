<?php

namespace App\Http\Controllers;

use App\Models\Detailkegiatan;
use App\Models\Identitassurat;
use App\Models\RefMetodepelatihan;
use App\Models\Usulankegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DetailKegiatanController extends Controller
{
    public function create(Usulankegiatan $usulan)
    {
        $usulan->load('identitassurat');
        
        return Inertia::render('Usulan/LengkapiUsulan', [
            'usulankegiatan' => $usulan,
            'metodes' => RefMetodepelatihan::select('id','metode_pelatihan')->get(),
        ]);
    }

    public function store(Request $request, Usulankegiatan $usulan)
    {
        $request->validate( [
            'latarbelakang_kegiatan' => 'nullable|string',
            'dasarhukum_kegiatan' => 'nullable|string',
            'uraian_kegiatan' => 'nullable|string',
            'maksud_kegiatan' => 'nullable|string',
            'tujuan_kegiatan' => 'nullable|string',
            'hasil_kegiatan' => 'nullable|string',
            'narasumber_kegiatan' => 'nullable|string',
            'peserta_kegiatan' => 'nullable|string',
            'alokasianggaran_kegiatan' => 'nullable|numeric',
            'metodepelatihan_id' => 'nullable|exists:ref_metodepelatihans,id',
            'dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $usulan->update([
                'statususulan_kegiatan' => 'pending'
            ]);

            $dokPath = null;
            if ($request->hasFile('dokumen')) {
                $dokPath = $request->file('dokumen')->store("usulan/{$usulan->id}", 'public');
            }

            Detailkegiatan::create([
                'usulankegiatan_id' => $usulan->id,
                'latarbelakang_kegiatan' => $request->latarbelakang_kegiatan,
                'dasarhukum_kegiatan' => $request->dasarhukum_kegiatan,
                'uraian_kegiatan' => $request->uraian_kegiatan,
                'maksud_kegiatan' => $request->maksud_kegiatan,
                'tujuan_kegiatan' => $request->tujuan_kegiatan,
                'hasil_kegiatan' => $request->hasil_kegiatan,
                'narasumber_kegiatan' => $request->narasumber_kegiatan,
                'peserta_kegiatan' => $request->peserta_kegiatan,
                'alokasianggaran_kegiatan' => $request->alokasianggaran_kegiatan,
                'metodepelatihan_id' => $request->metodepelatihan_id,
                'dokumenpendukung_kegiatan' => $dokPath,
            ]);

            DB::commit();
            return redirect()->route('usulan.index')->with('success', 'Usulan berhasil dilengkapi.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}

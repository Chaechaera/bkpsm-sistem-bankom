<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubUnitkerjaController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $subunit_id = $user->subunitkerja_id;

        // cek apakah sudah ada file kop/ttd
        $kop_path = "templatesurat/{$subunit_id}/kop.png";
        $ttd_path = "templatesurat/{$subunit_id}/ttd.png";

        return inertia('UploadKopTTD', [
            'kopExists' => Storage::disk('public')->exists($kop_path),
            'ttdExists' => Storage::disk('public')->exists($ttd_path),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $subunit_id = $user->subunitkerja_id;

        $request->validate([
            'kop_surat' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'tanda_tangan' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // simpan di folder storage/app/public/templatesurat/{subunitId}/
        if ($request->hasFile('kop_surat')) {
            $request->file('kop_surat')->storeAs("templatesurat/{$subunit_id}", 'kop.png', 'public');
        }

        if ($request->hasFile('tanda_tangan')) {
            $request->file('tanda_tangan')->storeAs("templatesurat/{$subunit_id}", 'ttd.png', 'public');
        }

        return back()->with('success', 'Kop surat dan tanda tangan berhasil diupdate.');
    }
}

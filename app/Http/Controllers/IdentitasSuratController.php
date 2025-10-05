<?php

namespace App\Http\Controllers;

use App\Models\Identitassurat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IdentitasSuratController extends Controller
{
    public function create()
    {
        return Inertia::render('Identitas/CreateIdentitas');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:2048',
        ]);

        if ($request->hasFile('lampiran')) {
            $validated['lampiran'] = $request->file('lampiran')->store('lampiran', 'public');
        }

        $identitas = Identitassurat::create($validated);

        return redirect()->route('usulan.create', ['surat' => $identitas->id])->with('success', 'Identitas Surat Berhasil Disimpan');
    }

}

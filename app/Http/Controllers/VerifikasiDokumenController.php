<?php

namespace App\Http\Controllers;

use App\Models\BalasanLaporanKegiatan;
use App\Models\Sertifikat;
use App\Models\Usulankegiatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\PDF;

class VerifikasiDokumenController extends Controller
{
    // ✅ Tampilkan halaman verifikasi dokumen
    public function show($id)
    {
        $usulankegiatan = Usulankegiatan::with(['subunitkerja.unitkerja', 'laporankegiatan'])
            ->findOrFail($id);

        return Inertia::render('Superadmin/VerifikasiDokumen', [
            'usulankegiatan' => $usulankegiatan,
        ]);
    }

    // ✅ Proses verifikasi dokumen laporan
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // Hanya superadmin yang boleh memverifikasi
        if ($user->role !== 'superadmin') {
            abort(403, 'Hanya superadmin yang dapat memverifikasi dokumen.');
        }

        $request->validate([
            'status' => 'required|in:verified,report_rejected',
        ]);

        $usulankegiatan = Usulankegiatan::findOrFail($id);
        $laporan = $usulankegiatan->laporankegiatan;

        if (!$laporan) {
            return back()->with('error', 'Laporan kegiatan belum tersedia.');
        }

        // update status laporan
        $laporan->statuslaporan_kegiatan = $request->status;
        $laporan->save();

        // update status usulan jika laporan diverifikasi
        if ($request->status === 'verified') {
            $usulankegiatan->statususulan_kegiatan = 'finish';
            $usulankegiatan->save();
        }

        return redirect()->route('usulan.pending')
            ->with('success', 'Status laporan telah diperbarui menjadi ' . $request->status);
    }

    public function verifikasiLaporan(Request $request, $id)
{
    $user = Auth::user();
    if ($user->role !== 'superadmin') {
        abort(403);
    }

    $request->validate([
        'status' => 'required|in:verified,report_rejected',
        // optional fields untuk balasan:
        'detail' => 'nullable|string',
        'jumlah_jp' => 'nullable|integer'
    ]);

    // ambil usulan dan laporan
    $usulankegiatan = Usulankegiatan::findOrFail($id);
    $laporan = $usulankegiatan->laporankegiatan;
    if (!$laporan) {
        return back()->with('error', 'Laporan kegiatan belum tersedia.');
    }

    // lakukan di transaction supaya konsisten
    DB::beginTransaction();
    try {
        // 1. Update status laporan
        $laporan->statuslaporan_kegiatan = $request->status;
        $laporan->save();

        // 2. Kalau diverifikasi, buat balasan + file PDF + sertifikat
        if ($request->status === 'verified') {

            // create balasan record
            $balasan = BalasanLaporanKegiatan::create([
                'laporankegiatan_id' => $laporan->id,
                'identitassurat_id' => $laporan->identitassurat_id ?? null,
                'detail' => $request->input('detail', 'Terima kasih atas laporan kegiatan.'),
                'jumlah_jp' => $request->input('jumlah_jp')
            ]);

            // prepare data untuk view PDF surat balasan
            $dataSurat = [
                'usulan' => $usulankegiatan,
                'laporan' => $laporan,
                'balasan' => $balasan,
            ];

            $pdfSurat = PDF::loadView('pdf.suratbalasan', $dataSurat)->setPaper('A4','portrait');

            $filenameSurat = 'SuratBalasan_' . $laporan->id . '_' . time() . '.pdf';
            $storageSurat = 'public/surat_balasan/' . $filenameSurat;
            Storage::put($storageSurat, $pdfSurat->output());
            $balasan->update(['file_surat' => $filenameSurat]);

            // create sertifikat record
            $nomorSertifikat = 'SR-' . now()->format('Ymd') . '-' . Str::padLeft($balasan->id, 4, '0');
            $sertifikat = Sertifikat::create([
                'balasan_laporan_kegiatan_id' => $balasan->id,
                'subunitkerja_id' => $usulankegiatan->subunitkerja_id,
                'nomor_sertifikat' => $nomorSertifikat,
                'tanggalkeluar_sertifikat' => now()->toDateString(),
            ]);

            // generate pdf sertifikat
            $pdfSertif = PDF::loadView('pdf.sertifikat', [
                'usulan' => $usulankegiatan,
                'laporan' => $laporan,
                'balasan' => $balasan,
                'sertifikat' => $sertifikat,
            ])->setPaper('A4','landscape');

            $filenameSertif = 'Sertifikat_' . $sertifikat->id . '_' . time() . '.pdf';
            $storageSertif = 'public/sertifikat/' . $filenameSertif;
            Storage::put($storageSertif, $pdfSertif->output());
            $sertifikat->update(['file_path' => $filenameSertif]);

            // opsional: simpan file_surat di usulankegiatan juga jika ingin
            $usulankegiatan->update(['file_surat' => $balasan->file_surat ?? null]);

            // notify creator (opsional)
            if ($usulankegiatan->createby) {
                // buat notification LaporanDiverifikasiNotification
                // $usulankegiatan->createby->notify(new LaporanDiverifikasiNotification($balasan, $sertifikat));
            }
        }

        // jika report_rejected -> cukup update status (atau simpan note)
        DB::commit();
    } catch (\Throwable $e) {
        DB::rollBack();
        \Log::error('Error verifikasi laporan: ' . $e->getMessage());
        return back()->with('error', 'Terjadi kesalahan saat memverifikasi: ' . $e->getMessage());
    }

    // redirect kembali
    return redirect()->route('usulan.pending')->with('success', 'Laporan diverifikasi dan dokumen berhasil dibuat.');
}
}

<?php

namespace App\Http\Controllers;

use App\Models\BalasanLaporanKegiatan;
use App\Models\Detailkegiatan;
use App\Models\Identitassurat;
use App\Models\LaporanKegiatan;
use App\Models\RefCarapelatihan;
use App\Models\RefMetodepelatihan;
use App\Models\RefSubunitkerja;
use App\Models\Sertifikat;
use App\Models\User;
use App\Models\Usulankegiatan;
use App\Notifications\UsulanStatusChanged;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class UsulanKegiatanController extends Controller
{

    public function index()
{
    $usulans = Usulankegiatan::with([
        'identitassurat:id,nomor_surat,tanggal_surat',
        'laporankegiatan:id,usulankegiatan_id,statuslaporan_kegiatan',
        'laporankegiatan.balasanlaporankegiatan:id,laporan_kegiatan_id,file_surat',
        'laporankegiatan.balasanlaporankegiatan.sertifikat:id,balasan_laporan_kegiatan_id,file_path'
    ])
    ->select('id', 'nama_kegiatan', 'identitassurat_id', 'tanggal_pelaksanaan', 'statususulan_kegiatan')
    ->get();

    return Inertia::render('Usulan/ShowUsulan', [
        'usulans' => $usulans
    ]);
}

    public function pendingList()
    {
        $usulans = Usulankegiatan::with('subunitkerja.unitkerja', 'identitassurat')
            //->where('statususulan_kegiatan', 'pending')
            ->get(['id', 'nama_kegiatan', 'subunitkerja_id', 'identitassurat_id', 'statususulan_kegiatan']);

        return Inertia::render('Superadmin/ListUsulanPending', [
            'usulans' => $usulans,
        ]);
    }

    public function trackingUsulan()
    {
        $user = Auth::user();

        $latestUsulan = Usulankegiatan::where('created_by', $user->id)
            ->latest()
            ->first();

        return Inertia::render('Usulan/TrackingUsulan', [
            'latestUsulan' => $latestUsulan,
        ]);
    }

    public function reviewForm(Usulankegiatan $usulankegiatan)
    {
        $usulankegiatan->load('createby', 'identitassurat', 'subunitkerja.unitkerja', 'detailkegiatan');

        return Inertia::render('Superadmin/ReviewUsulan', [
            'usulankegiatan' => $usulankegiatan,
        ]);
    }

    public function reviewSubmit(Request $request, Usulankegiatan $usulankegiatan)
    {
        // authorization (double-check)
        //if (!$request->user()->hasRole('superadmin')) {
        //abort(403);
        //}

        $request->validate([
            'action' => 'required|in:approved,rejected',
            'note' => 'nullable|string|max:2000',
        ]);

        $statususulan_kegiatan = $request->action === 'approved' ? 'approved' : 'rejected';

        $usulankegiatan->update([
            'statususulan_kegiatan' => $statususulan_kegiatan,
            'review_note' => $request->note,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        // kirim notifikasi ke creator (in-app / email)
        //$createby = $usulankegiatan->createby;
        //if ($createby) {
        //$createby->notify(new UsulanStatusChanged($usulankegiatan));
        //}

        return redirect()
            ->route('usulan.pending')
            ->with('success', 'Usulan telah ' . ($statususulan_kegiatan === 'approved' ? 'disetujui' : 'ditolak') . ' 👍');
    }

    public function download(Usulankegiatan $usulankegiatan)
    {
        // Ambil ID subunit kerja
        $subunit_id = $usulankegiatan->subunitkerja_id;
        $dir = storage_path("app/public/templatesurat/{$subunit_id}/");

        // Cek apakah ada file kop & tanda tangan di folder template
        $kop_file = collect(glob($dir . 'kop.*'))->first() ?: null;
        $ttd_file = collect(glob($dir . 'ttd.*'))->first() ?: null;

        // Simpan ke variabel
        $usulankegiatan->kop_surat = file_exists($kop_file) ? $kop_file : null;
        $usulankegiatan->tanda_tangan = file_exists($ttd_file) ? $ttd_file : null;

        // Generate PDF dari view
        $pdf = PDF::loadView('pdf.usulan_kegiatan', [
            'usulankegiatan' => $usulankegiatan,
            'kop_path' => $kop_file,
            'ttd_path' => $ttd_file,
        ])->setPaper('A4', 'portrait');

        // Tentukan nama file dan lokasi penyimpanan
        $filename = 'Surat_Usulan_' . $usulankegiatan->nama . '.pdf';
        $storagePath = 'public/surat/' . $filename;

        // Simpan PDF ke storage jika belum ada
        if (!Storage::exists($storagePath)) {
            Storage::put($storagePath, $pdf->output());
            $usulankegiatan->update(['file_surat' => $filename]);
        }

        // Stream untuk menampilkan di browser
        return $pdf->stream("Usulan_Kegiatan_{$usulankegiatan->nama_kegiatan}.pdf");
    }

    public function preview(Usulankegiatan $usulankegiatan)
    {
        // Ambil ID subunit kerja
        $subunit_id = $usulankegiatan->subunitkerja_id;
        $dir = storage_path("app/public/templatesurat/{$subunit_id}/");

        // Cek apakah ada file kop & tanda tangan
        $kop_file = collect(glob($dir . 'kop.*'))->first() ?: null;
        $ttd_file = collect(glob($dir . 'ttd.*'))->first() ?: null;

        $usulankegiatan->kop_surat = $kop_file ? $kop_file : null;
        $usulankegiatan->tanda_tangan = $ttd_file ? $ttd_file : null;

        // Generate PDF dari view
        $pdf = PDF::loadView('pdf.usulan_kegiatan', [
            'usulankegiatan' => $usulankegiatan,
            'kop_path' => $kop_file,
            'ttd_path' => $ttd_file,
        ])->setPaper('A4', 'portrait');

        // Stream hasil preview (tidak menyimpan ke storage)
        return $pdf->stream("Preview_Surat_Usulan_{$usulankegiatan->nama_kegiatan}.pdf");
    }


    /*public function download(Usulankegiatan $usulankegiatan)
{
    // Ambil data terkait surat dan kop
    $subunitId = $usulankegiatan->subunitkerja_id;

    $kopPath = storage_path("app/public/templatesurat/{$subunitId}/kop.png");
    $ttdPath = storage_path("app/public/templatesurat/{$subunitId}/ttd.png");

    $usulankegiatan->kop_surat = file_exists($kopPath) ? $kopPath : null;
    $usulankegiatan->tanda_tangan = file_exists($ttdPath) ? $ttdPath : null;

    // Generate PDF dari view surat
    $pdf = PDF::loadView('pdf.usulan_kegiatan', [
        'usulankegiatan' => $usulankegiatan,
        'kopPath' => $usulankegiatan->kop_surat,
        'ttdPath' => $usulankegiatan->tanda_tangan,
    ])->setPaper('A4', 'portrait');

    // Buat nama file unik
    $filename = 'Surat_Usulan_' . $usulankegiatan->id . '.pdf';

    // Simpan file ke storage/app/public/surat/
    \Storage::put('public/surat/' . $filename, $pdf->output());

    // Update database (simpen nama file surat)
    $usulankegiatan->update([
        'file_surat' => $filename,
    ]);

    return response()->json([
        'message' => 'Surat berhasil dibuat dan disimpan.',
        'file' => $filename,
    ]);
}*/

    /*public function download(Usulankegiatan $usulankegiatan)
    {
        $subunitId = $usulankegiatan->subunitkerja_id;

        $kopPath = storage_path("app/public/templatesurat/{$subunitId}/kop.png");
        $ttdPath = storage_path("app/public/templatesurat/{$subunitId}/ttd.png");

        $usulankegiatan->kop_surat = file_exists($kopPath) ? $kopPath : null;
        $usulankegiatan->tanda_tangan = file_exists($ttdPath) ? $ttdPath : null;

        $pdf = PDF::loadView('pdf.usulan_kegiatan', compact('usulankegiatan'));


        $subunit_id = $usulankegiatan->subunitkerja_id;

        $dir = storage_path("app/public/templatesurat/{$subunit_id}/");

        $kop_file = collect(glob($dir . 'kop.*'))->first() ?: null;
        $ttd_file = collect(glob($dir . 'ttd.*'))->first() ?: null;

        $kop_path = $kop_file ? $kop_file : null;
        $ttd_path = $ttd_file ? $ttd_file : null;

        $pdf = PDF::loadView('pdf.usulan_kegiatan', [
            'usulankegiatan' => $usulankegiatan,
            'kopPath' => $kop_path,
            'ttdPath' => $ttd_path,
        ])->setPaper('A4', 'portrait');

        return $pdf->download("Usulan_Kegiatan_{$usulankegiatan->nama_kegiatan}.pdf");
    }*/

    public function create()
    {
        $user = Auth::user();
        //$identitas = Identitassurat::findOrFail();

        return Inertia::render('Usulan/CreateUsulan', [
            'identitassurats' => Identitassurat::select('id', 'nomor_surat', 'tanggal_surat', 'perihal')->get(),
            'subunitkerjas' => $user->subunitkerja->sub_unitkerja,
            'createdby' => $user->nama,
            'carapelatihans' => RefCarapelatihan::select('id', 'cara_pelatihan')->get(),
        ]);
    }

    public function updateStatus(Request $request, Usulankegiatan $usulankegiatan)
    {
        $request->validate([
            'statususulan_kegiatan' => 'required|string'
        ]);

        $statususulan_kegiatan = $request->statususulan_kegiatan;

        $user = Auth::user();

        //Jika Admin OPD
        if ($user->role === 'admin') {
            if (!in_array($statususulan_kegiatan, ['draft', 'in_progress', 'completed'])) {
                return back()->withErrors('Anda tidak boleh mengubah status ini!');
            }
        }

        //Jika Superadmin
        if ($user->role === 'superadmin') {
            if (!in_array($statususulan_kegiatan, ['pending', 'approved', 'rejected', 'finish', 'in_review'])) {
                return back()->withErrors('Superadmin hanya boleh mengubah status ini!');
            }
        }

        $usulankegiatan->statususulan_kegiatan = $statususulan_kegiatan;
        $usulankegiatan->save();

        return back()->with('success', 'Status usulan diperbarui ke: ' . $usulankegiatan->statususulan_kegiatan);
    }

    // Tampilkan halaman update progress (hanya admin)
    public function editProgress(Usulankegiatan $usulankegiatan)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat memperbarui progress kegiatan.');
        }

        // Pastikan status sebelumnya sudah approved
        //if ($usulankegiatan->statususulan_kegiatan !== 'approved' && $usulankegiatan->statususulan_kegiatan !== 'in_progress') {
        //return back()->with('error', 'Progress hanya bisa diubah jika status sudah approved atau sedang in_progress.');
        //}

        if (!in_array($usulankegiatan->statususulan_kegiatan, ['approved', 'in_progress', 'completed'])) {
            return back()->with('error', 'Progress hanya dapat diubah setelah disetujui.');
        }

        // Ambil laporan kegiatan terkait, atau buat baru jika belum ada
        //$laporankegiatan = $usulankegiatan->laporankegiatan ?? LaporanKegiatan::firstOrCreate(
        //['usulankegiatan_id' => $usulankegiatan->id],
        //['statuslaporan_kegiatan' => 'pending']
        //);

        $laporankegiatan = $usulankegiatan->laporankegiatan
            ?? LaporanKegiatan::create([
                'usulankegiatan_id' => $usulankegiatan->id,
                'status_laporan_kegiatan' => 'pending'
            ]);

        return Inertia::render('Usulan/EditProgressUsulan', [
            'usulankegiatan' => $usulankegiatan,
            'laporankegiatan' => $laporankegiatan,
        ]);
    }

    public function updateProgress(Request $request, Usulankegiatan $usulankegiatan)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat memperbarui progress kegiatan.');
        }

        $validated = $request->validate([
            'statususulan_kegiatan' => 'required|string|in:in_progress,completed',
        ]);

        // Update status
        $usulankegiatan->update([
            'statususulan_kegiatan' => $validated['statususulan_kegiatan'],
        ]);

        return redirect()->route('usulan.index')->with('success', 'Progress kegiatan berhasil diperbarui.');
    }

    /*public function updateProgress(Request $request, Usulankegiatan $usulankegiatan)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat memperbarui progress kegiatan.');
        }

        $request->validate([
            'statususulan_kegiatan' => 'required|in:in_progress,completed',
            //'dokumenpendukung_kegiatan' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            //'dokumenPK_kegiatan' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        // Upload file dokumen pendukung
        //if ($request->hasFile('dokumenpendukung_kegiatan')) {
            //$path1 = $request->file('dokumenpendukung_kegiatan')->store('dokumen_pendukung', 'public');
            //$laporankegiatan->dokumenpendukung_kegiatan = $path1;
        //}

        // Upload file dokumen pengembangan kompetensi
        //if ($request->hasFile('dokumenPK_kegiatan')) {
            //$path2 = $request->file('dokumenPK_kegiatan')->store('dokumen_pengembangankompetensi', 'public');
            //$laporankegiatan->dokumenPK_kegiatan = $path2;
        //}

        // Update status progress
        $usulankegiatan->update([
            'statususulan_kegiatan' => (string) $request->statususulan_kegiatan,
        ]);

        return redirect()->route('usulan.index')->with('success', 'Progress kegiatan berhasil diperbarui.');
    }*/

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'subunitkerja_id' => 'nullable|exists:ref_subunitkerjas,id',
            'lokasi_kegiatan' => 'nullable|string|max:255',
            'carapelatihan_id' => 'nullable|exists:ref_carapelatihans,id',
            'tanggal_pelaksanaan' => 'nullable|date',
            'identitassurat_id' => 'nullable|exists:identitassurats,id',
            'statususulan_kegiatan' => 'nullable|in:draft,submit',
        ]);

        $user = Auth::user();

        $usulan = Usulankegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'subunitkerja_id' => $user->subunitkerja_id,
            'lokasi_kegiatan' => $request->lokasi_kegiatan,
            'carapelatihan_id' => $request->carapelatihan_id,
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'identitassurat_id' => $request->identitassurat_id,
            'statususulan_kegiatan' => $request['status'] === 'submit' ? 'pending' : 'draft',
            'created_by' => $user->id,
        ]);

        //Kalo Klik Draft
        if (($request['status'] ?? null) === 'draft') {
            return redirect()->route('usulan.index')->with('success', 'Usulan disimpan sebagai draft');
        }
        //Kalo Klik Submit
        return redirect()->route('detail.create', ['usulan' => $usulan->id])
            ->with('success', 'Usulan berhasil dibuat. Silakan lengkapi identitas surat dan detail kegiatan.');
    }

    public function verifikasiDokumen(UsulanKegiatan $usulankegiatan)
    {
        return Inertia::render('Superadmin/VerifikasiDokumen', [
            'usulankegiatan' => $usulankegiatan->load(['subunitkerja.unitkerja', 'laporankegiatan'])
        ]);
    }

    /*public function verifikasiDokumen($id)
    {
        $usulankegiatan = Usulankegiatan::with(['subunitkerja.unitkerja', 'laporankegiatan'])->findOrFail($id);

        return Inertia::render('Superadmin/VerifikasiDokumen', [
            'usulankegiatan' => $usulankegiatan,
        ]);
    }*/

    /*public function verifikasiLaporan(Request $request, $id)
{
    $user = Auth::user();
    if ($user->role !== 'superadmin') {
        abort(403);
    }

    $request->validate([
        'status' => 'required|in:verified,report_rejected'
    ]);

    $usulankegiatan = Usulankegiatan::findOrFail($id);
    $laporan = $usulankegiatan->laporankegiatan;

    if (!$laporan) {
        return back()->with('error', 'Laporan kegiatan belum tersedia.');
    }

    $laporan->statuslaporan_kegiatan = $request->status;
    $laporan->save();

    if ($request->status === 'verified') {
        $usulankegiatan->statususulan_kegiatan = 'finish';
        $usulankegiatan->save();
    }

    return redirect()->route('usulan.pending')
        ->with('success', 'Laporan telah diverifikasi.');
}*/

    public function verifikasiLaporan(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'superadmin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:verified,report_rejected'
        ]);

        $usulankegiatan = Usulankegiatan::findOrFail($id);
        $laporan = $usulankegiatan->laporankegiatan;

        if (!$laporan) {
            return back()->with('error', 'Laporan kegiatan belum tersedia.');
        }

        $laporan->statuslaporan_kegiatan = $request->status;
        $laporan->save();

        if ($request->status === 'verified') {
            // ✅ Update status usulan menjadi finish
            $usulankegiatan->statususulan_kegiatan = 'finish';
            $usulankegiatan->save();

            // ✅ Pastikan identitas surat ada
            $identitas = IdentitasSurat::latest()->first();

            // ✅ Simpan data surat balasan
            $balasan = BalasanLaporanKegiatan::create([
                'laporankegiatan_id' => $laporan->id,
                'identitassurat_id' => $identitas?->id,
                'detail' => "Menindaklanjuti laporan kegiatan '{$usulankegiatan->nama_kegiatan}' telah diverifikasi.",
                'jumlah_jp' => rand(8, 40),
            ]);

            // ✅ Generate PDF surat balasan
            $pdfBalasan = PDF::loadView('pdf.balasan_usulan_kegiatan', compact('balasan', 'usulankegiatan'));
            $fileBalasan = 'balasan/' . Str::uuid() . '.pdf';
            Storage::disk('public')->put($fileBalasan, $pdfBalasan->output());
            $balasan->file_path = $fileBalasan;
            $balasan->save();

            // ✅ Generate sertifikat otomatis
            $sertifikat = Sertifikat::create([
                'balasanlaporankegiatan_id' => $balasan->id,
                'subunitkerja_id' => $usulankegiatan->subunitkerja_id,
                'nomor_sertifikat' => 'SERT-' . strtoupper(Str::random(6)),
                'tanggalkeluar_sertifikat' => now(),
            ]);

            // ✅ Generate PDF sertifikat
            $pdfSertifikat = PDF::loadView('pdf.sertifikat', compact('sertifikat', 'usulankegiatan'));
            $fileSertifikat = 'sertifikat/' . Str::uuid() . '.pdf';
            Storage::disk('public')->put($fileSertifikat, $pdfSertifikat->output());
            $sertifikat->file_path = $fileSertifikat;
            $sertifikat->save();

            return redirect()->route('usulan.pending')
                ->with('success', 'Laporan diverifikasi. Surat balasan & sertifikat berhasil dibuat.');
        }

        if ($request->status === 'report_rejected') {
            $laporan->update(['statuslaporan_kegiatan' => 'rejected']);
            return redirect()->route('usulan.pending')
                ->with('info', 'Laporan ditolak.');
        }

        return redirect()->route('usulan.pending')
            ->with('error', 'Status tidak dikenali.');
    }

    public function previewBalasan($id)
{
    $balasan = BalasanLaporanKegiatan::findOrFail($id);
    $path = 'public/' . $balasan->file_path;

    if (!Storage::exists($path)) {
        abort(404, 'File surat balasan tidak ditemukan.');
    }

    return response()->file(storage_path('app/' . $path));
}

public function previewSertifikat($id)
{
    $sertifikat = Sertifikat::findOrFail($id);
    $path = 'public/' . $sertifikat->file_path;

    if (!Storage::exists($path)) {
        abort(404, 'File sertifikat tidak ditemukan.');
    }

    return response()->file(storage_path('app/' . $path));
}

}

<?php

namespace App\Http\Controllers;

use App\Models\Detailkegiatan;
use App\Models\Identitassurat;
use App\Models\RefCarapelatihan;
use App\Models\RefMetodepelatihan;
use App\Models\RefSubunitkerja;
use App\Models\User;
use App\Models\Usulankegiatan;
use App\Notifications\UsulanStatusChanged;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UsulanKegiatanController extends Controller
{

    public function index()
    {
        return Inertia::render('Usulan/ShowUsulan', [
            'usulans' => Usulankegiatan::all(['id', 'nama_kegiatan', 'statususulan_kegiatan']),
        ]);
    }

    public function pendingList()
    {
        $usulans = Usulankegiatan::with('subunitkerja', 'createby')
            ->where('statususulan_kegiatan', 'pending')
            ->get(['id', 'nama_kegiatan', 'created_by', 'tanggal_pelaksanaan', 'subunitkerja_id', 'statususulan_kegiatan']);

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
        $usulankegiatan->load('createby', 'identitassurat', 'subunitkerja', 'detailkegiatan');

        return Inertia::render('Superadmin/ReviewUsulan', [
            'usulankegiatan' => $usulankegiatan,
        ]);
    }

    public function reviewSubmit(Request $request, Usulankegiatan $usulankegiatan)
    {
        // authorization (double-check)
        if (!$request->user()->hasRole('superadmin')) {
            abort(403);
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'note' => 'nullable|string|max:2000',
        ]);

        $statususulan_kegiatan = $request->action === 'approve' ? 'approved' : 'rejected';

        $usulankegiatan->update([
            'statususulan_kegiatan' => $statususulan_kegiatan,
            'review_note' => $request->note,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => Carbon::now(),
        ]);

        // kirim notifikasi ke creator (in-app / email)
        $createby = $usulankegiatan->createby;
        if ($createby) {
            $createby->notify(new UsulanStatusChanged($usulankegiatan));
        }

        return redirect()->route('usulan.pending')->with('success', 'Usulan telah ' . ($statususulan_kegiatan === 'approved' ? 'disetujui' : 'ditolak'));
    }

    public function download(Usulankegiatan $usulankegiatan)
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
    }

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
            if (!in_array($statususulan_kegiatan, ['pending', 'approved', 'rejected', 'finish'])) {
                return back()->withErrors('Superadmin hanya boleh mengubah status ini!');
            }
        }

        $usulankegiatan->statususulan_kegiatan = $statususulan_kegiatan;
        $usulankegiatan->save();

        return back()->with('success', 'Status usulan diperbarui ke: ' . $usulankegiatan->statususulan_kegiatan);
    }

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
}

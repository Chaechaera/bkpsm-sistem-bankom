<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalasanLaporanKegiatan extends Model
{
    protected $table = 'balasan_laporan_kegiatans';
    protected $fillable = ['laporan_kegiatan_id','identitassurat_id','detail','jumlah_jp','file_surat'];

    public function laporankegiatann()
    {
        return $this->belongsTo(LaporanKegiatan::class, 'laporan_kegiatan_id');
    }

    public function sertifikat()
    {
        return $this->hasOne(Sertifikat::class, 'sertifikat_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    protected $fillable = ['balasan_laporan_kegiatan_id','subunitkerja_id','nomor_sertifikat','tanggalkeluar_sertifikat','file_path'];

    public function balasanlaporankegiatan()
    {
        return $this->belongsTo(BalasanLaporanKegiatan::class, 'balasan_laporan_kegiatan_id');
    }
}

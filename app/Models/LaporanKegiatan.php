<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKegiatan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kegiatans';

    protected $fillable = [
        'usulankegiatan_id',
        'dokumenpendukung_kegiatan',
        'dokumenPK_kegiatan',
        'statususulan_kegiatan',
        'statuslaporan_kegiatan',
    ];

    public function usulanKegiatan()
    {
        return $this->belongsTo(Usulankegiatan::class, 'usulankegiatan_id');
    }

    public function balasanlaporankegiatan()
    {
        return $this->hasOne(BalasanLaporanKegiatan::class, 'laporan_kegiatan_id');
    }

}

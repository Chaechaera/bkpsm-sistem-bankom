<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usulankegiatan extends Model
{
    use HasFactory;
    protected $table = 'usulankegiatans';

    protected $fillable = [
        'subunitkerja_id',
        'identitassurat_id',
        'nama_kegiatan',
        'lokasi_kegiatan',
        'carapelatihan_id',
        'tanggal_pelaksanaan',
        'statususulan_kegiatan',
        'created_by'
    ];

    public function detailkegiatan() {
        return $this->hasMany(Detailkegiatan::class, 'usulankegiatan_id');
    }

    public function subunitkerja()
    {
        return $this->belongsTo(RefSubunitkerja::class, 'subunitkerja_id');
    }

    public function identitassurat() {
        return $this->belongsTo(Identitassurat::class, 'identitassurat_id');
    }

    public function createby() {
        return $this->belongsTo(User::class, 'created_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detailkegiatan extends Model
{
    protected $table = 'detailkegiatans';

    protected $fillable = [
        'usulankegiatan_id',
        'latarbelakang_kegiatan',
        'dasarhukum_kegiatan',
        'uraian_kegiatan',
        'maksud_kegiatan',
        'tujuan_kegiatan',
        'hasil_kegiatan',
        'narasumber_kegiatan',
        'peserta_kegiatan',
        'alokasianggaran_kegiatan',
        'metodepelatihan_id',
        'dokumenpendukung_kegiatan'
    ];    
    
    public function usulan() {
        return $this->belongsTo(Usulankegiatan::class, 'usulankegiatan_id');
    }

    public function metodepelatihan() {
        return $this->belongsTo(RefMetodepelatihan::class);
    }
}

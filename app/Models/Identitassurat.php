<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Identitassurat extends Model
{
    use HasFactory;

    protected $table = 'identitassurats';

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'perihal',
        'lampiran',
    ];

    public function usulanKegiatan()
    {
        return $this->hasOne(Usulankegiatan::class, 'identitassurat_id');
    }
}

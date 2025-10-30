<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class RefSubunitkerja extends Model
{
    use HasFactory;

    protected $table = 'ref_subunitkerjas';

    protected $fillable = ['id', 'unitkerja_id', 'sub_unitkerja', 'singkatan'];

    // Relasi ke Unit Kerja
    public function unitkerja()
    {
        return $this->belongsTo(RefUnitkerja::class, 'unitkerja_id');
    }

    // Relasi ke Usulan Kegiatan
    public function usulankegiatans()
    {
        return $this->hasMany(Usulankegiatan::class, 'subunitkerja_id');
    }
}

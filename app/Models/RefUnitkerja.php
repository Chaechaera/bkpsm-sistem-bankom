<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefUnitkerja extends Model
{
    use HasFactory;

    protected $table = 'ref_unitkerjas';

    protected $fillable = ['id', 'kode_unitkerja', 'unitkerja'];

    public function subunitkerjas()
    {
        return $this->hasMany(RefSubunitkerja::class, 'unitkerja_id');
    }
}

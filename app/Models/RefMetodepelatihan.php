<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefMetodepelatihan extends Model
{
    use HasFactory;

    protected $table = 'ref_metodepelatihans';

    protected $fillable = ['metode_pelatihan'];
}

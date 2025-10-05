<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefCarapelatihan extends Model
{
    use HasFactory;

    protected $table = 'ref_carapelatihans';

    protected $fillable = ['cara_pelatihan'];
}

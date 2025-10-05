<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class RefSubunitkerja extends Model
{
    use HasFactory;

    protected $table = 'ref_subunitkerjas';

    protected $fillable = ['sub_unitkerja'];

    /**public function users() 
    {
        return $this->hasMany(User::class, 'subunitkerja_id');
    }*/
}

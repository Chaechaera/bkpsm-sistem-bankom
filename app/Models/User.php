<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'users';

    protected $fillable = [
        'nip',
        'nama',
        'email',
        'password',
        'role',
        'subunitkerja_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function subunitkerja()
    {
        return $this->belongsTo(RefSubunitkerja::class, 'subunitkerja_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    public function canAccessPanel($panel): bool
    {
        return $this->hasAnyRole(['admin', 'superadmin']);
    }

    // Helper baca enum role lama (opsional)
    public function enumRole(): ?string
    {
        return $this->role;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

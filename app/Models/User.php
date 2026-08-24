<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'username', 'email', 'password', 'role', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    //protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed', 'is_active' => 'boolean'];

    protected $casts = ['email_verified_at' => 'datetime', 'is_active' => 'boolean'];

    // Relasi: BAA yang menginput calon
    public function calonDiinput()
    {
        return $this->hasMany(CalonMahasiswa::class, 'input_by');
    }

    // Relasi: BAPSI yang memverifikasi
    public function calonDiverifikasi()
    {
        return $this->hasMany(CalonMahasiswa::class, 'verifikasi_by');
    }

    // Relasi: hasil MOORA yang dihitung BAPSI
    public function hasilMoora()
    {
        return $this->hasMany(HasilMoora::class, 'dihitung_oleh');
    }

    // Helper: cek role
    public function isBaa(): bool    { return $this->role === 'baa'; }
    public function isBapsi(): bool  { return $this->role === 'bapsi'; }
    public function isLkm(): bool    { return $this->role === 'lkm'; }
}

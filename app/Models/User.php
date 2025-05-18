<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'name',
        'nidn',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    //Relasi
    public function surat()
    {
        return $this->hasMany(Surat::class);
    }

    public function disposisiDari()
    {
        return $this->hasMany(Disposisi::class);
    }

    public function disposisiKepada()
    {
        return $this->hasMany(Disposisi::class);
    }

    // JWT methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}

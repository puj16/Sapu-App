<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Petani extends Authenticatable
{
    use Notifiable;

    protected $table = 'petani';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nik', 'nama', 'wa', 'kk', 'ktp', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function lahan()
    {
        return $this->hasMany(Lahan::class, 'add_by');
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'nik');
    }

    public function rdkk()
    {
        return $this->hasMany(Rdkk::class, 'nik');
    }
}

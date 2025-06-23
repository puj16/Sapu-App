<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pupuk extends Model
{
    use HasFactory;

    protected $table = 'pupuk';

    protected $fillable = [
        'nama_pupuk',
        'harga'
    ];

    public function rdkk()
    {
        return $this->hasMany(Rdkk::class, 'id_pupuk');
    }
}


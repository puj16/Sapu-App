<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'stok';

    protected $fillable = [
        'tahun',
        'periode',
        'id_pupuk',
        'pupuk_datang',
        'pupuk_tersisa',
    ];

        public function pupuk()
    {
        return $this->belongsTo(Pupuk::class, 'id_pupuk');
    }
}


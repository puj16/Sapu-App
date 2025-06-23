<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyaluran extends Model
{
    //
    use HasFactory;

    protected $table = 'penyaluran'; // Nama tabel

    protected $primaryKey = 'id'; // Primary key

    protected $fillable = [
        'nik',
        'tahun',
        'periode',
        'komoditi',
        'tgl_penyaluran',
        'tgl_bayar',
        'total_bayar',
        'metode_bayar',
        'status_pembayaran',
        'status_penyaluran',
        'info',
        'snap_token',
    ];

    // Relasi ke model Petani (jika ada)
    public function petani()
    {
        return $this->belongsTo(Petani::class, 'nik', 'nik');
    }
}

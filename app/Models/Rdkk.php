<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rdkk extends Model
{
    use HasFactory;

    protected $table = 'rdkk';
    protected $primaryKey = 'id_RDKK';

    protected $fillable = [
        'tahun',
        'periode',
        'komoditi',
        'nik',
        'id_pengajuan',
        'id_pupuk',
        'volume_pupuk_mt1',
        'pengajuan_mt1',
        'volume_pupuk_mt2',
        'pengajuan_mt2',
        'volume_pupuk_mt3',
        'pengajuan_mt3',
        'total_harga',
        'status_penyaluran',
        'info'
    ];

    public function pupuk()
    {
        return $this->belongsTo(Pupuk::class, 'id_pupuk');
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan');
    }

    public function petani()
    {
        return $this->belongsTo(Petani::class, 'nik');
    }
}

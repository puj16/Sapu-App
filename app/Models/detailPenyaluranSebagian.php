<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detailPenyaluranSebagian extends Model
{
    //
    protected $table = 'detail_penyaluran_sebagian';
    protected $fillable = [
        'id_penyaluran',
        'id_pupuk',
        'volume_pupuk',
    ];
    public function penyaluran()
    {
        return $this->belongsTo(Penyaluran::class, 'id_penyaluran');
    }
    public function pupuk()
    {
        return $this->belongsTo(Pupuk::class, 'id_pupuk');
    }
}

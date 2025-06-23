<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';
    protected $primaryKey = 'id_pengajuan';

    protected $fillable = [
        'NOP',
        'nik',
        'catatan',
        'status_validasi',
        'tahun',
        'luasan',
        'komoditi',
    ];



    public function petani()
    {
        return $this->belongsTo(Petani::class, 'nik', 'nik');
    }

    public function lahan()
    {
        return $this->belongsTo(Lahan::class, 'NOP', 'NOP');
    }

    public function rdkk()
    {
        return $this->hasMany(Rdkk::class, 'id_pengajuan');
    }
}




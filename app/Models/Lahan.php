<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lahan extends Model
{
    use HasFactory;

    protected $table = 'lahan';
    protected $primaryKey = 'NOP';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'NOP',
        'NIK',
        'Luas',
        'status',
        'Foto_SPPT',
        'add_by',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApelPiket extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_urut',
        'tanggal',
        'hari',
        'pembina',
        'pemimpin',
        'ket',
    ];
}
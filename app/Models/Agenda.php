<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'tanggal_kegiatan',
        'hari',
        'waktu',
        'acara',
        'ruang',
        'bidang',
        'disposisi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kegiatan' => 'date:Y-m-d',
        ];
    }
}
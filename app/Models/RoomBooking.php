<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomBooking extends Model
{
    protected $fillable = [
        'ruang',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'acara',
        'user_id',
        'pemesan',
        'owner_token',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date:Y-m-d',
        ];
    }
}
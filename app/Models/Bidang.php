<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bidang extends Model
{
    protected $table = 'bidangs';

    protected $fillable = [
        'nama',
    ];

    public function agendas(): BelongsToMany
    {
        return $this->belongsToMany(
            Agenda::class,
            'agenda_bidang',
            'bidang_id',
            'agenda_id'
        )->withTimestamps();
    }
}
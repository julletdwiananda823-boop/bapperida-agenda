<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agenda extends Model
{
    protected $fillable = [
        'tanggal_kegiatan',
        'hari',
        'waktu',
        'jam_mulai',
        'jam_selesai',
        'acara',
        'ruang',
        'ruangan_id',
        'bidang',
        'disposisi',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kegiatan' => 'date:Y-m-d',
        ];
    }

    public function bidangs(): BelongsToMany
    {
        return $this->belongsToMany(
            Bidang::class,
            'agenda_bidang',
            'agenda_id',
            'bidang_id'
        )->withTimestamps();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(AgendaAttachment::class);
    }

    public function disposisis(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'agenda_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
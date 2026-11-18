<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaAttachment extends Model
{
    protected $fillable = [
        'agenda_id',
        'original_name',
        'stored_name',
        'file_path',
        'mime_type',
        'file_size',
        'access_type',
        'uploaded_by',
    ];

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }
}
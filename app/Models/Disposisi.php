<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disposisi extends Model
{
    protected $fillable = [
        'agenda_id',
        'isi',
        'ditujukan_ke',
        'user_id',
        'tenggat',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tenggat' => 'date:Y-m-d',
        ];
    }

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BienImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'bien_id',
        'url',
        'ordre',
        'legende',
    ];

    protected $casts = [
        'ordre' => 'integer',
    ];

    public function bien(): BelongsTo
    {
        return $this->belongsTo(Bien::class);
    }
}

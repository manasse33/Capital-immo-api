<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temoignage extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'initiale',
        'role',
        'message',
        'avatar',
        'note',
        'is_active',
        'ordre',
    ];

    protected $casts = [
        'note' => 'integer',
        'is_active' => 'boolean',
        'ordre' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre')->orderBy('created_at', 'desc');
    }
}

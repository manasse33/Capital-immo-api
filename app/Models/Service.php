<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'slug',
        'description',
        'description_longue',
        'icon',
        'image',
        'avantages',
        'cta',
        'ordre',
        'is_active',
    ];

    protected $casts = [
        'avantages' => 'array',
        'is_active' => 'boolean',
        'ordre' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->titre);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre');
    }
}

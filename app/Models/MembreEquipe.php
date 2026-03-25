<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembreEquipe extends Model
{
    use HasFactory;

    protected $table = 'membres_equipe';

    protected $fillable = [
        'prenom',
        'nom',
        'poste',
        'email',
        'telephone',
        'photo',
        'description',
        'ordre',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ordre' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre');
    }

    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }
}

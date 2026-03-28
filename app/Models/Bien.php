<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Bien extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'slug',
        'description',
        'prix',
        'surface',
        'pieces',
        'chambres',
        'salle_de_bain',
        'etage',
        'type',
        'transaction',
        'location_period',
        'zone',
        'quartier',
        'reference',
        'statut',
        'en_vedette',
        'caracteristiques',
        'user_id',
        'vue_count',
    ];

    protected $casts = [
        'prix' => 'integer',
        'surface' => 'integer',
        'pieces' => 'integer',
        'chambres' => 'integer',
        'salle_de_bain' => 'integer',
        'etage' => 'integer',
        'en_vedette' => 'boolean',
        'caracteristiques' => 'array',
        'vue_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bien) {
            if (empty($bien->slug)) {
                $bien->slug = Str::slug($bien->titre);
            }
            if (empty($bien->reference)) {
                $bien->reference = 'CIG-' . strtoupper(substr($bien->type, 0, 1)) . '-' . str_pad(Bien::count() + 1, 3, '0', STR_PAD_LEFT);
            }
        });

        static::updating(function ($bien) {
            if ($bien->isDirty('titre') && empty($bien->slug)) {
                $bien->slug = Str::slug($bien->titre);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(BienImage::class)->orderBy('ordre');
    }

    public function scopeDisponible($query)
    {
        return $query->where('statut', 'disponible');
    }

    public function scopeEnVedette($query)
    {
        return $query->where('en_vedette', true)->where('statut', 'disponible');
    }

    public function scopeVente($query)
    {
        return $query->where('transaction', 'vente');
    }

    public function scopeLocation($query)
    {
        return $query->where('transaction', 'location');
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['type'] ?? null, fn ($q, $type) => $type !== 'all' ? $q->where('type', $type) : $q)
            ->when($filters['transaction'] ?? null, fn ($q, $t) => $t !== 'all' ? $q->where('transaction', $t) : $q)
            ->when($filters['zone'] ?? null, fn ($q, $zone) => $zone !== 'Toutes les zones' ? $q->where(function($sq) use ($zone) {
                $sq->where('zone', $zone)->orWhere('quartier', $zone);
            }) : $q)
            ->when($filters['prix_min'] ?? null, fn ($q, $prix) => $q->where('prix', '>=', $prix * 1000000))
            ->when($filters['prix_max'] ?? null, fn ($q, $prix) => $q->where('prix', '<=', $prix * 1000000))
            ->when($filters['surface_min'] ?? null, fn ($q, $surface) => $q->where('surface', '>=', $surface))
            ->when($filters['search'] ?? null, fn ($q, $search) => $q->where(function($sq) use ($search) {
                $sq->where('titre', 'like', "%{$search}%")
                   ->orWhere('quartier', 'like', "%{$search}%")
                   ->orWhere('description', 'like', "%{$search}%");
            }));
    }

    public function getPrixFormateAttribute(): string
    {
        if ($this->transaction === 'location') {
            return number_format($this->prix, 0, ',', ' ') . ' FCFA/mois';
        }
        if ($this->prix >= 1000000000) {
            return number_format($this->prix / 1000000000, 1, ',', ' ') . ' Mds FCFA';
        }
        return number_format($this->prix / 1000000, 0, ',', ' ') . ' M FCFA';
    }

    public function getMainImageAttribute(): ?string
    {
        return $this->images->first()?->url;
    }

    public function incrementVueCount(): void
    {
        $this->increment('vue_count');
    }
}

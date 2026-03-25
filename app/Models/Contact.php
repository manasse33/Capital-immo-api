<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'telephone',
        'email',
        'objet',
        'message',
        'bien_id',
        'reference_bien',
        'is_read',
        'notes',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }
}

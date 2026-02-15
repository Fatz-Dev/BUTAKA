<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;

    // disable updated_at karena tabel feedback hanya punya created_at
    const UPDATED_AT = null;


    protected $fillable = [
        'visitor_id',
        'rating',
        'comment',
    ];

    /**
     * Relasi ke Visitor
     */
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Scope untuk filter berdasarkan rating
     */
    public function scopeByRating($query, int $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope untuk rating tinggi (4-5)
     */
    public function scopeHighRating($query)
    {
        return $query->where('rating', '>=', 4);
    }

    /**
     * Scope untuk rating rendah (1-2)
     */
    public function scopeLowRating($query)
    {
        return $query->where('rating', '<=', 2);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'purpose',
        'host_name',
        'institution',
        'status',
        'check_in_time',
        'check_out_time',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'check_in_time' => 'datetime',
            'check_out_time' => 'datetime',
        ];
    }

    /**
     * Scope untuk visitor yang sedang menunggu
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    /**
     * Scope untuk visitor yang sedang berkunjung
     */
    public function scopeBerkunjung($query)
    {
        return $query->where('status', 'berkunjung');
    }

    /**
     * Scope untuk visitor yang sudah selesai
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Scope untuk visitor hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('check_in_time', today());
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitor extends Model
{
    use HasFactory;

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

    // scope untuk visitor yang sedang menunggu
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    // scope untuk visitor yang sedang berkunjung
    public function scopeBerkunjung($query)
    {
        return $query->where('status', 'berkunjung');
    }

    // scope untuk visitor yang sudah selesai
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    // scope untuk visitor hari ini
    public function scopeToday($query)
    {
        return $query->whereDate('check_in_time', today());
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'purpose',
        'visit_date',
        'visit_time',
        'status',
    ];

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }
}

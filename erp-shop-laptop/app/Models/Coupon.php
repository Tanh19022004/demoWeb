<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function isValid()
    {
        $now = now();
        return $this->is_active &&
            $now->between($this->start_date, $this->end_date) &&
            ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }
} 
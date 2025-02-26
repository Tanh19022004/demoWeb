<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'email',
        'notes',
        'total',
        'status',
        'payment_status',
        'payment_method'
    ];

    protected $casts = [
        'total' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            default => 'Không xác định'
        };
    }

    public function getCustomerNameAttribute()
    {
        return $this->shipping_name;
    }

    public function getCustomerEmailAttribute()
    {
        return $this->email;
    }

    public function getCustomerPhoneAttribute()
    {
        return $this->shipping_phone;
    }

    // Tự động tạo order number
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_number = 'ORD-' . strtoupper(uniqid());
        });
    }
} 
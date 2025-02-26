<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Tự động tạo slug từ name
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = str()->slug($category->name);
        });
    }
} 
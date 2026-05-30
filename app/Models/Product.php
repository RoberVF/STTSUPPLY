<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'title', 'slug', 'category', 'description', 
        'provider_url', 'provider_price', 'selling_price', 
        'images', 'is_active', 'team', 'league'
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    // Mutador automático para el slug al guardar por nombre
    protected static function booted()
    {
        static::creating(function ($product) {
            $product->slug = Str::slug($product->title);
        });
    }
}
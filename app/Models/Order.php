<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = ['customer_name', 'customer_address', 'total_amount', 'status', 'gift_name'];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
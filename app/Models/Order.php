<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'delivered_address_id',
        'total_price',
        'status',
        'ordered_at',
    ];

    /**
     * The user who placed the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The address where the order is delivered.
     */
    public function deliveredAddress()
    {
        return $this->belongsTo(Address::class, 'delivered_address_id');
    }

    /**
     * The items within this order.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}


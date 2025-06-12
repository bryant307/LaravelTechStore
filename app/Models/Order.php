<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'address_id',
        'order_number',
        'total_amount',
        'payment_method',
        'payment_id',
        'status',
        'notes',
        'tracking_number'
    ];
    
    /**
     * Generate a unique order number
     */
    public static function generateOrderNumber()
    {
        $prefix = 'ORD-';
        $timestamp = now()->format('YmdHis');
        $random = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        return $prefix . $timestamp . $random;
    }
    
    /**
     * Get the user that placed the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the shipping address for the order
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    
    /**
     * Get the items for the order
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

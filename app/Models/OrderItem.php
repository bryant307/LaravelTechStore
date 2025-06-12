<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'name',
        'price',
        'quantity',
        'options',
        'subtotal'
    ];
    
    protected $casts = [
        'options' => 'array',
        'price' => 'float',
        'subtotal' => 'float'
    ];
    
    /**
     * Get the order that this item belongs to
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    /**
     * Get the product for this item
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Get the product variant for this item
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}

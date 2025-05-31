<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'option_id',
        'value',
        'description',
        'image_path', // <-- Asegura que image_path sea fillable
        // 'sku', // Descomenta si usas SKU
        // 'stock', // Descomenta si usas stock
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantImage extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    
    protected $fillable = [
        'product_variant_id',
        'image_path',
        'alt_text',
        'is_primary',
        'order'
    ];
    
    /**
     * Obtener la URL completa de la imagen
     */
    public function getImageAttribute()
    {
        return \Illuminate\Support\Facades\Storage::url($this->image_path);
    }
    
    /**
     * Relación con la variante del producto
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}

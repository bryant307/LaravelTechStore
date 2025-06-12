<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;    protected $fillable = [
        'product_id',
        'option_id',
        'value',
        'description',
        'image_path',
        'sku', 
        'stock',
        'track_inventory',
        'available',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
    
    /**
     * Relación con las imágenes de la variante
     */
    public function images()
    {
        return $this->hasMany(ProductVariantImage::class)->orderBy('order');
    }
    
    /**
     * Obtener la imagen principal de la variante
     */
    public function getPrimaryImageAttribute()
    {
        $primaryImage = $this->images()->where('is_primary', true)->first();
        
        if (!$primaryImage && $this->images()->count() > 0) {
            $primaryImage = $this->images()->first();
        }
        
        return $primaryImage ? $primaryImage->image : ($this->product ? $this->product->image : null);
    }
    
    /**
     * Accesorio para obtener la URL de la imagen de la variante o la imagen del producto si no hay variante
     */
    public function getImageAttribute()
    {
        if ($this->image_path) {
            return \Illuminate\Support\Facades\Storage::url($this->image_path);
        }
        
        return $this->primary_image;
    }
}

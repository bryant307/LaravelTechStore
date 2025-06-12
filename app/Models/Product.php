<?php

namespace App\Models;

use App\Livewire\Admin\Products\ProductVariants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use hasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'image_path',
        'price',
        'subcategory_id',
        'stock',
        'track_inventory',
        'available',
        'low_stock_threshold',
    ];

    protected function image() : Attribute 
    {
        return Attribute::make(
            get: fn() => Storage::url($this->image_path),
        );
    }
    //RElacion uno amuchos inversa
    public function subcategory(){
        return $this->belongsTo(Subcategory::class);
    }

    //Relacion uno a muchos
    public function variants(){
        return $this->hasMany(Variant::class);
    }

    //Relacion uno a muchos
    public function options(){
        return $this->belongsToMany(Option::class)
        ->withPivot('value')
        ->withTimestamps();
    }   
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Relación con las reseñas del producto
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    /**
     * Obtiene la valoración promedio del producto
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()
            ->where('approved', true)
            ->avg('rating') ?? 0;
    }
    
    /**
     * Obtiene el número de reseñas del producto
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()
            ->where('approved', true)
            ->count();
    }

    /**
     * Scope para filtrar productos por familia
     */
    public function scopeByFamily($query, $familyId)
    {
        return $query->whereHas('subcategory.category', function ($q) use ($familyId) {
            $q->where('family_id', $familyId);
        });
    }

    /**
     * Scope para filtrar productos por categoría
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('subcategory', function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        });
    }

    /**
     * Scope para filtrar productos por subcategoría
     */
    public function scopeBySubcategory($query, $subcategoryId)
    {
        return $query->where('subcategory_id', $subcategoryId);
    }

    /**
     * Scope para buscar productos por texto en nombre, descripción o sku
     */
    public function scopeSearch($query, $searchTerm)
    {
        if (!empty($searchTerm)) {
            return $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('sku', 'like', '%' . $searchTerm . '%');
            });
        }
        
        return $query;
    }

    /**
     * Scope para filtrar productos por características seleccionadas
     */
    public function scopeByFeatures($query, $featureIds)
    {
        if (!empty($featureIds)) {
            return $query->whereHas('options.features', function ($q) use ($featureIds) {
                $q->whereIn('features.id', $featureIds);
            });
        }
        
        return $query;
    }

    /**
     * Scope para ordenar productos por relevancia
     */
    public function scopeOrderByRelevance($query, $searchTerm = null)
    {
        if (!empty($searchTerm)) {
            return $query->orderByRaw("CASE 
                WHEN name LIKE ? THEN 1 
                WHEN name LIKE ? THEN 2 
                WHEN description LIKE ? THEN 3 
                ELSE 4 
            END, name ASC", [
                $searchTerm,
                '%' . $searchTerm . '%',
                '%' . $searchTerm . '%'
            ]);
        }
        
        return $query->orderBy('name', 'asc');
    }
    
    /**
     * Scope para obtener los productos más recientes
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
    
    /**
     * Scope para limitar la cantidad de resultados
     */
    public function scopeTake($query, $limit)
    {
        return $query->limit($limit);
    }
    
    /**
     * Scope para obtener los productos en oferta
     */
    public function scopeOnSale($query)
    {
        return $query->where('discount_percentage', '>', 0);
    }
    
    /**
     * Scope para filtrar productos por rango de precios
     */
    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }
}

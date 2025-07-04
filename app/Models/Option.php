<?php

namespace App\Models;

use App\Livewire\Admin\Products\ProductVariants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Feature;
use App\Models\Product;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];
    //Relacion uno a muchos
    public function products(){
        return $this->belongsToMany(Product::class)
        ->withPivot('value')
        ->withTimestamps();
    }

    //Relacion uno a muchos inversa
    public function features():HasMany{
        return $this->hasMany(Feature::class);
    }
    
    /**
     * Scope para filtrar opciones por familia
     */
    public function scopeByFamily($query, $familyId)
    {
        return $query->whereHas('products', function ($query) use ($familyId) {
            $query->whereHas('subcategory.category', function ($subQuery) use ($familyId) {
                $subQuery->where('family_id', $familyId);
            });
        });
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariants::class);
    }
    //
}

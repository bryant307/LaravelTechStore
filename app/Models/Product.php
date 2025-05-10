<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

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
    ];
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
}

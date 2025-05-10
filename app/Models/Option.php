<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function feature(){
        return $this->hasMany(Feature::class);
    }
    //
}

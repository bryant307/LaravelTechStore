<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'title',
        'comment',
        'verified_purchase',
        'approved'
    ];

    /**
     * Relación con el usuario que escribió la reseña
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el producto reseñado
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

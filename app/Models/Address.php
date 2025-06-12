<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'description',
        'distrito',
        'departamento',
        'receiver',
        'receiver_info',
        'latitude',
        'longitude',
        'default'
    ];

    protected $casts = [
        'receiver_info' => 'array',
        'default' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

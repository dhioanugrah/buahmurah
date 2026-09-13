<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fruit extends Model
{
    protected $fillable = [
        'name',
        'stock',
        'unit',
        'price',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'user_id',
        'fruit_id',
        'qty',
        'unit',
        'price',
        'total_price',
        'payment_method',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fruit()
    {
        return $this->belongsTo(Fruit::class);
    }
}

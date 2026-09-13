<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $fillable = [
        'nomor',
        'tanggal',
        'keterangan',
        'kondisi',
    ];
}

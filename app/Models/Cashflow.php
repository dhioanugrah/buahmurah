<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cashflow extends Model
{
    protected $fillable = [
        'nomor',
        'tanggal',
        'keterangan',
        'debit',
        'kredit',
        'saldo',
        'bukti_transaksi',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kulakan extends Model
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'nomor',
        'bulan',
        'employee_name',
        'absensi_total_hari',
        'kasbon',
        'total',
    ];
}

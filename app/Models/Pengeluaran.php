<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'kategori',
        'deskripsi',
        'jumlah',
        'metode_pembayaran'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];
}
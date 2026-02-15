<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceSparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'sparepart_id',
        'qty',        // 🔥 tambahkan ini
        'harga'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // 🔥 INI YANG KURANG
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}

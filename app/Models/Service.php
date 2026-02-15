<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'kendaraan_id','teknisi_id','jenis_service','tanggal','keluhan','status_servis',
        'status_pembayaran', 
        'total_jasa','total_sparepart','grand_total'
    ];

    public function items()
    {
        return $this->hasMany(ServiceItem::class);
    }

    public function spareparts()
    {
        return $this->hasMany(ServiceSparepart::class);
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class);
    }

    
}

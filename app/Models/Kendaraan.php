<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'plat_nomor', 'merk', 'tipe'];

    /**
     * Relasi ke model Service
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'kendaraan_id');
    }

    /**
     * Relasi balik ke Customer (Opsional tapi disarankan)
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
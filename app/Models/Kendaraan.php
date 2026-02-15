<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id','plat_nomor','merk','tipe','warna'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}

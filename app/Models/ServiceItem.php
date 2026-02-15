<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable = ['service_id','nama_jasa','harga'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

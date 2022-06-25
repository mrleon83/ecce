<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherData extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_id',
        'datefrom',
        'weatherdata',
    ];

    public function ipAddress(){
        return $this->belongsTo(IpAddresses::class, 'id', 'ip_id');
    }
}


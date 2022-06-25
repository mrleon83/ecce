<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpAddresses extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'latitude',
        'longitude',
        'city',
        'country',
    ];

    public function weather_data(){
        return $this->hasMany(WeatherData::class, 'ip_id', 'id');
    }
}

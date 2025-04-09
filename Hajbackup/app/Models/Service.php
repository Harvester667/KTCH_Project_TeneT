<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service',
        'duration',
        'price',
        'description'
    ];

    public $timestamp=true;

    public function bookings()
    {
        return $this->hasMany(Booking::class );
    }
    // public function bookings()
    // {
    //     return $this->belongsToMany(Booking::class, 'booking_service');
    // }
    // public function  booking(){
    //     return $this->hasMany(Booking::class);
    // }
}
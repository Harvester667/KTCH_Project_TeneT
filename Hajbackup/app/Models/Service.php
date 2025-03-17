<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public $timestamp=false;

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_service');
    }
    // public function  booking(){
    //     return $this->hasMany(Booking::class);
    // }
}

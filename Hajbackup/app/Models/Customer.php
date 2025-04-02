<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'gender',
        'invoice_address',
        'invoice_postcode',
        'invoice_city',
        'birth_date'
    ];

    public $timestamp=true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_user');
    }

    // public function booking(){
    //     return $this->hasMany(Booking::class);
    // }
}

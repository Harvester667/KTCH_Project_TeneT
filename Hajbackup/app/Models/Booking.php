<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'user_id',
        'employee_id',
        'customer_id',
        'service_id',
        'booking_time'
    ];

    public $timestamp=true;

    public function employees()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customers()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function services()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'booking_user', 'booking_id', 'user_id');
    // }

    // public function services()
    // {
    //     return $this->belongsToMany(Service::class, 'booking_service', 'booking_id', 'service_id');
    // }

    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'booking_user');
    // }

    // public function services()
    // {
    //     return $this->belongsToMany(Service::class, 'booking_service');
    // }



    // public function customer(){
    //     return $this->belongsTo(Customer::class);
    // }

    // public function employee(){
    //     return $this->belongsTo(Employee::class);
    // }
//
    // public function user(){
    //     return $this->belongsTo(User::class);
    // }

    // public function service(){
    //     return $this->belongsTo(Service::class);
    // }
}

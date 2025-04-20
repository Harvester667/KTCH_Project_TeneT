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
        'description',
        'active'
    ];

    public $timestamp=true;

    public function booking()
    {
        return $this->hasMany(Booking::class);
    }
}
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;
    

    public function bookings(){

        return $this->hasMany(Booking::class, 'employee_id', 'customer_id');
    }
    // public function customer()
    // {
    //     return $this->hasOne(Customer::class);
    // }

    // public function employee()
    // {
    //     return $this->hasOne(Employee::class);
    // }

    // public function bookings()
    // {
    //     return $this->belongsToMany(Booking::class);
    // }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    //Kitölthető mezők. Ha nincs itt NULL értéket ad!
    protected $fillable = [
        'name',
        'email',
        'password',
        //'city_id',
        'admin',
        'login_counter',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
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
    

    public function booking(){

        return $this->hasMany(Booking::class);
    }

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
        'admin',
        'role',
        'active',
        'login_counter',
        'phone',
        'gender',
        'invoice_address',
        'invoice_postcode',
        'invoice_city',
        'birth_date',
        'qualifications',
        'description',
        'login_counter',
        'banning_time'
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

    public function isProtectedAdmin(): bool
    {
        return $this->admin == 2;
    }
}
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// if(config('app.enabled_email_confirmation')==true)
// class User extends Authenticatable implements MustVerifyEmail

// if(config('app.enabled_email_confirmation')==false)
// class User extends Authenticatable

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = ['id'];

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function linkStaff(){
        return $this->hasOne(Staff::class,'id','id_users')->with(['linkStaffRole']);
    }
    public function linkCustomer(){
        return $this->hasOne(Customer::class,'id','id_users');
    }
}
<?php

namespace App\Models;

use App\Models\Status;
use App\Models\Customer;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkStaff(){
        return $this->hasMany(Staff::class,'id','id_staff');
    }

    public function linkStatus(){
        return $this->hasMany(Status::class,'id','status');
    }

    public function linkCustomer(){
        return $this->hasMany(Customer::class,'id','id_customer');
    }
}

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
        return $this->belongsTo(Staff::class,'id_staff');
    }

    public function linkStatus(){
        return $this->belongsTo(Status::class,'status');
    }

    public function linkCustomer(){
        return $this->belongsTo(Customer::class,'id_customer');
    }
}

<?php

namespace App\Models;
use App\Models\Staff;
use App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaTrip extends Model
{
    use HasFactory;

    protected $guarded = [
      'id'
    ];

    public function linkStaff(){
      return $this->belongsTo(Staff::class,'id_staff');
    }

    public function linkCustomer(){
      return $this->belongsTo(Customer::class,'id_customer');
    }
}

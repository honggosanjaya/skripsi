<?php

namespace App\Models;

use App\Models\Trip;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Item;
use App\Models\Retur;
use App\Models\Order;
use App\Models\Event;
use App\Models\OrderTrack;
use App\Models\Reimbursement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    // public function linkTrip(){
    //     return $this->hasMany(Trip::class,'status','id');
    // }

    // public function linkCustomer(){
    //     return $this->hasMany(Customer::class,'status','id');
    // }

    // public function linkStaff(){
    //     return $this->hasMany(Staff::class,'status','id');
    // }

    public function linkItem(){
        return $this->hasMany(Item::class,'status','id');
    }

    public function linkRetur(){
        return $this->hasMany(Retur::class,'status','id');
    }

    public function linkOrder(){
        return $this->hasMany(Order::class,'status','id');
    }

    public function linkEvent(){
        return $this->hasMany(Event::class,'status','id');
    }

    public function linkOrderTrack(){
        return $this->hasMany(OrderTrack::class,'status','id');
    }

    public function linkReimbursement(){
      return $this->hasMany(Reimbursement::class,'status','id');
  }
}

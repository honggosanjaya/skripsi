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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkTrip(){
        return $this->belongsTo(Trip::class,'status','id');
    }

    public function linkCustomer(){
        return $this->hasMany(Customer::class,'status','id');
    }

    public function linkStaff(){
        return $this->belongsTo(Staff::class,'status','id');
    }

    public function linkItem(){
        return $this->belongsTo(Item::class,'status','id');
    }

    public function linkRetur(){
        return $this->belongsTo(Retur::class,'status','id');
    }

    public function linkOrder(){
        return $this->belongsTo(Order::class,'status','id');
    }

    public function linkEvent(){
        return $this->belongsTo(Event::class,'status','id');
    }

    public function linkOrderTrack(){
        return $this->belongsTo(OrderTrack::class,'status','id');
    }
}

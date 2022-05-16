<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\OrderTrack;
use App\Models\Invoice;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkStaff(){
        return $this->hasMany(Staff::class,'id','id_staff');
    }

    public function linkOrderItem(){
        return $this->belongsTo(OrderItem::class,'id_order','id');
    }

    public function linkCustomer(){
        return $this->hasMany(Customer::class,'id','id_customer');
    }

    public function linkOrderTrack(){
        return $this->belongsTo(OrderTrack::class,'id_order','id');
    }

    public function linkInvoice(){
        return $this->belongsTo(Invoice::class,'id_order','id');
    }

    public function linkStatus(){
        return $this->hasMany(Status::class,'id','status');
    }
}

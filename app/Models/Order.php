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
        return $this->belongsTo(Staff::class,'id_staff');
    }

    public function linkOrderItem(){
        return $this->hasMany(OrderItem::class,'id_order','id')->with(['linkItem']);
    }

    public function linkCustomer(){
        return $this->belongsTo(Customer::class,'id_customer');
    }

    public function linkOrderTrack(){
        return $this->hasOne(OrderTrack::class,'id_order','id')->with(['linkStatus']);
    }

    public function linkInvoice(){
        return $this->hasOne(Invoice::class,'id_order','id');
    }

    public function linkStatus(){
        return $this->belongsTo(Status::class,'status');
    }
}

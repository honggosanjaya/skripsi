<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\Retur;
use App\Models\Trip;
use App\Models\History;
use App\Models\CustomerType;
use App\Models\District;
use App\Models\Order;
use App\Models\Status;
use App\Models\ReturType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkStaff(){
        return $this->hasMany(Staff::class,'id','id_staff');
    }

    public function linkRetur(){
        return $this->belongsTo(Retur::class,'id_customer','id');
    }

    public function linkTrip(){
        return $this->belongsTo(Trip::class,'id_customer','id');
    }

    public function linkHistory(){
        return $this->belongsTo(History::class,'id_customer','id');
    }

    public function linkCustomerType(){
        return $this->hasMany(CustomerType::class,'id','id_jenis');
    }

    public function linkDistrict(){
        return $this->hasMany(District::class,'id','id_wilayah');
    }

    public function linkOrder(){
        return $this->belongsTo(Order::class,'id_customer','id');
    }

    public function linkStatus(){
        return $this->hasMany(Status::class,'id','status');
    }

    public function linkReturType(){
        return $this->hasMany(ReturType::class,'id','tipe_retur');
    }
}

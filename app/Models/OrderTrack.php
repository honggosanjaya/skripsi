<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Staff;
use App\Models\Status;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTrack extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkOrder(){
        return $this->belongsTo(Order::class,'id','id_order');
    }

    public function linkStaffPengonfirmasi(){
        return $this->hasMany(Staff::class,'id','id_staff_pengonfirmasi');
    }

    public function linkStaffPengirim(){
        return $this->hasMany(Staff::class,'id','id_staff_pengirim');
    }

    public function linkStatus(){
        return $this->belongsTo(Status::class,'id','status');
    }

    public function linkVehicle(){
        return $this->belongsTo(Vehicle::class,'id','id_vehicle');
    }
}

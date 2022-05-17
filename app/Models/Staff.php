<?php

namespace App\Models;

use App\Models\Event;
use App\Models\Trip;
use App\Models\Pengadaan;
use App\Models\StaffRole;
use App\Models\Customer;
use App\Models\OrderTrack;
use App\Models\Order;
use App\Models\Status;
use App\Models\Retur;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $table = 'staffs';
    protected $guarded = [
        'id'
      ];
  
    public function linkEvent(){
        return $this->hasMany(Event::class,'id_staff','id');
    }

    public function linkTrip(){
        return $this->hasMany(Trip::class,'id_staff','id');
    }

    public function linkPengadaan(){
        return $this->hasMany(Pengadaan::class,'id_staff','id');
    }

    public function linkStaffRole(){
        return $this->hasOne(StaffRole::class,'id','role');
    }

    public function linkCustomer(){
        return $this->hasMany(Customer::class,'id_staff','id');
    }

    public function linkStaffPengonfirmasi(){
        return $this->hasMany(OrderTrack::class,'id_staff_pengonfirmasi','id');
    }

    public function linkStaffPengirim(){
        return $this->hasMany(OrderTrack::class,'id_staff_pengirim','id');
    }

    public function linkOrder(){
        return $this->hasMany(Order::class,'id_staff','id');
    }

    public function linkStatus(){
        return $this->belongsTo(Status::class,'id','status');
    }

    public function linkReturPengaju(){
        return $this->hasMany(Retur::class,'id_staff_pengaju','id');
    }

    public function linkReturPengonfirmasi(){
        return $this->hasMany(Retur::class,'id_staff_pengonfirmasi','id');
    }
}

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
        return $this->belongsTo(Event::class,'id_staff','id');
    }

    public function linkTrip(){
        return $this->belongsTo(Trip::class,'id_staff','id');
    }

    public function linkPengadaan(){
        return $this->belongsTo(Pengadaan::class,'id_staff','id');
    }

    public function linkStaffRole(){
        return $this->hasMany(StaffRole::class,'id','role');
    }

    public function linkCustomer(){
        return $this->belongsTo(Customer::class,'id_staff','id');
    }

    public function linkStaffPengonfirmasi(){
        return $this->hasMany(OrderTrack::class,'id_staff_pengonfirmasi','id');
    }

    public function linkStaffPengirim(){
        return $this->hasMany(OrderTrack::class,'id_staff_pengirim','id');
    }

    public function linkOrder(){
        return $this->belongsTo(Order::class,'id_staff','id');
    }

    public function linkStatus(){
        return $this->hasMany(Status::class,'id','status');
    }

    public function linkReturPengaju(){
        return $this->belongsTo(Retur::class,'id_staff_pengaju','id');
    }

    public function linkReturPengonfirmasi(){
        return $this->belongsTo(Retur::class,'id_staff_pengonfirmasi','id');
    }
}

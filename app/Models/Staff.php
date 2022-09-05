<?php

namespace App\Models;

use App\Models\Event;
use App\Models\Trip;
use App\Models\Pengadaan;
use App\Models\StaffRole;
use App\Models\Customer;
use App\Models\OrderTrack;
use App\Models\Order;
use App\Models\LaporanPenagihan;
use App\Models\Retur;
use App\Models\Reimbursement;
use App\Models\Pembayaran;
use App\Models\RencanaTrip;
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
    public function linkTripEc(){
        return $this->hasMany(Trip::class,'id_staff','id')->where('status_enum','2')->whereHas('linkCustomer',function($q) {
            $q->where('status_enum','1');
        });
    }
    public function linkTripEcF(){
        return $this->hasMany(Trip::class,'id_staff','id')->where('status_enum','2')->whereHas('linkCustomer',function($q) {
            $q->where('status_enum','1');
        });
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

    public function linkReturPengaju(){
        return $this->hasMany(Retur::class,'id_staff_pengaju','id');
    }

    public function linkReturPengonfirmasi(){
        return $this->hasMany(Retur::class,'id_staff_pengonfirmasi','id');
    }

    public function linkReimbursementPengaju(){
      return $this->hasMany(Reimbursement::class,'id_staff_pengaju','id');
    }

    public function linkReimbursementPengonfirmasi(){
        return $this->hasMany(Reimbursement::class,'id_staff_pengonfirmasi','id');
    }

    public function linkLaporanPenagihanPenagih(){
      return $this->hasMany(LaporanPenagihan::class,'id_staff_penagih','id');
    }

    public function linkPembayaranPenagih(){
      return $this->hasMany(Pembayaran::class,'id_staff_penagih','id');
    }

    public function linkRencanaTrip(){
      return $this->hasMany(RencanaTrip::class,'id_staff','id');
    }
}

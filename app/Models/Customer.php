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

    protected $appends = ['full_alamat','image_url'];
    protected $guarded = [
        'id'
      ];
  
    public function linkStaff(){
        return $this->belongsTo(Staff::class,'id','id_staff');
    }

    public function linkRetur(){
        return $this->hasMany(Retur::class,'id_customer','id');
    }

    public function linkTrip(){
        return $this->hasMany(Trip::class,'id_customer','id');
    }

    public function linkHistory(){
        return $this->hasMany(History::class,'id_customer','id');
    }

    public function linkCustomerType(){
        return $this->belongsTo(CustomerType::class,'id','id_jenis');
    }

    public function linkDistrict(){
        return $this->hasOne(District::class,'id','id_wilayah');
    }

    public function linkOrder(){
        return $this->hasMany(Order::class,'id_customer','id');
    }

    public function linkStatus(){
        return $this->hasMany(Status::class,'id','status');
    }

    public function linkReturType(){
        return $this->belongsTo(ReturType::class,'id','tipe_retur');
    }
    public function getFullAlamatAttribute()
    {
        return "{$this->alamat_utama} {$this->alamat_nomor}";
    }
    public function getImageUrlAttribute()
    {
        return asset('storage/customer/'.$this->foto);
    }
}

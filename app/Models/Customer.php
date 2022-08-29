<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\Retur;
use App\Models\Trip;
use App\Models\History;
use App\Models\CustomerType;
use App\Models\District;
use App\Models\Order;
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
        return $this->belongsTo(Staff::class,'id_staff','id');
    }

    public function linkRetur(){
        return $this->hasMany(Retur::class,'id_customer','id');
    }

    public function linkTrip(){
        return $this->hasMany(Trip::class,'id_customer','id');
    }

    public function latestLinkTrip(){
        return $this->hasOne(Trip::class,'id_customer','id')->latest();
    }

    public function linkHistory(){
        return $this->hasMany(History::class,'id_customer','id');
    }

    public function linkCustomerType(){
        return $this->belongsTo(CustomerType::class,'id_jenis','id');
    }

    public function linkDistrict(){
        return $this->belongsTo(District::class,'id_wilayah','id');
    }

    public function linkOrder(){
        return $this->hasMany(Order::class,'id_customer','id');
    }

    public function linkReturType(){
        return $this->belongsTo(ReturType::class,'tipe_retur','id');
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

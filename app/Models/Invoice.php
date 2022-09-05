<?php

namespace App\Models;

use App\Models\Retur;
use App\Models\Order;
use App\Models\Event;
use App\Models\Pembayaran;
use App\Models\LaporanPenagihan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkRetur(){
        return $this->hasMany(Retur::class,'id_invoice','id');
    }

    public function linkOrder(){
        return $this->belongsTo(Order::class,'id_order')->with(['linkOrderItem','linkCustomer']);
    }

    public function linkEvent(){
        return $this->belongsTo(Event::class,'id_event');
    }

    public function linkLaporanPenagihan(){
      return $this->hasMany(LaporanPenagihan::class,'id_invoice','id');
    }

    public function linkPembayaran(){
      return $this->hasMany(Pembayaran::class,'id_invoice','id');
    }
}

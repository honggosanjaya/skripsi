<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Pembayaran;
use App\Models\MetodePembayaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [
      'id'
    ];

    public function linkPembayaran(){
      return $this->hasMany(Pembayaran::class, 'invoice_id');
    }

    public function linkOrder(){
      return $this->belongsTo(Order::class, 'id', 'order_id');
    }

    public function linkmetodepembayaran(){
      return $this->belongsTo(MetodePembayaran::class, 'id', 'metodepembayaran_id');
    }
}

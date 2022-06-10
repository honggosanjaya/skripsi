<?php

namespace App\Models;

use App\Models\Retur;
use App\Models\Order;
use App\Models\Event;
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
        return $this->belongsTo(Order::class,'id_order');
    }

    public function linkEvent(){
        return $this->belongsTo(Event::class,'id_event');
    }
}

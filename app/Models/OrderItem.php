<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkItem(){
        return $this->belongsTo(Item::class,'id_item', 'id');
    }

    public function linkOrder(){
        return $this->belongsTo(Order::class,'id_order', 'id');
    }
}

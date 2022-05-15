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
        return $this->hasMany(Item::class,'id','id_item');
    }

    public function linkOrder(){
        return $this->hasMany(Order::class,'id','id_order');
    }
}

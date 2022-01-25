<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [
      'id'
    ];

    public function linkinvoice(){
      return $this->hasOne(Invoice::class, 'order_id');
    }

    public function linkorderitem(){
      return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function linktoko(){
      return $this->belongsTo(Toko::class, 'id', 'toko_id');
    }

    public function linksales(){
      return $this->belongsTo(User::class, 'id', 'sales_id');
    }
}

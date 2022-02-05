<?php

namespace App\Models;

use App\Models\Toko;
use App\Models\User;
use App\Models\Invoice;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [
      'id'
    ];

    public function linkInvoice(){
      return $this->hasOne(Invoice::class, 'order_id');
    }

    public function linkOrderItem(){
      return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function linkToko(){
      return $this->belongsTo(Toko::class, 'id', 'toko_id');
    }

    public function linksales(){
      return $this->belongsTo(User::class, 'id', 'sales_id');
    }
}

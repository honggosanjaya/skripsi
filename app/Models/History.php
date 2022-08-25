<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkCustomer(){
        return $this->belongsTo(Customer::class,'id_customer');
    }

    public function linkItem(){
        return $this->belongsTo(Item::class,'id_item')->with(['linkOrderItem']);
    }
}

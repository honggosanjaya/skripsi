<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPriceList extends Model
{
    use HasFactory;

    protected $guarded = [
      'id'
    ];

    public function linkItem(){
      return $this->belongsTo(Item::class,'id_item')->with(['linkOrderItem']);
    }
}

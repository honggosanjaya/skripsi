<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Toko;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class History extends Model
{
    use HasFactory;

    protected $guarded = [
      'id'
    ];

    public function linkToko(){
      return $this->belongsTo(Toko::class, 'id', 'toko_id');
    }

    public function linkItem(){
      return $this->belongsTo(Item::class, 'id', 'item_id');
    }
}

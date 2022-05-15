<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    use HasFactory;

    protected $guarded = [
      'id'
    ];
    public function linkSales() 
    { 
      return $this->belongsTo(User::class, 'id', 'sales_id');
    }
    public function linkItem()
    {
      return $this->belongsTo(Item::class, 'id', 'item_id');
    }
    public function linkToko()
    {
      return $this->belongsTo(Toko::class, 'id', 'toko_id');
    }
}

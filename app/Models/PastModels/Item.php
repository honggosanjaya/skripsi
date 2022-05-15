<?php

/*namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [
      'id'
    ];
    public function linkOrderItem()
    { 
        return $this->hasMany(Orderitem::class, 'item_id');
    }
    public function linkHistory()
    {
        return $this->hasMany(History::class, 'item_id');
    }
    public function linkRetur()
    {
        return $this->hasMany(Retur::class, 'item_id');
    }
    
}*/

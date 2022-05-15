<?php

namespace App\Models;

use App\Models\History;
use App\Models\Pengadaan;
use App\Models\Status;
use App\Models\OrderItem;
use App\Models\Retur;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkHistory(){
        return $this->hasMany(History::class,'id_item','id');
    }

    public function linkPengadaan(){
        return $this->hasMany(Pengadaan::class,'id_item','id');
    }

    public function linkStatus(){
        return $this->hasMany(Status::class,'id','status');
    }

    public function linkOrderItem(){
        return $this->belongsTo(OrderItem::class,'id_item','id');
    }

    public function linkRetur(){
        return $this->belongsTo(Retur::class,'id_item','id');
    }
}

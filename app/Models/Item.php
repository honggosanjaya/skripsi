<?php

namespace App\Models;

use App\Models\History;
use App\Models\Pengadaan;
use App\Models\Kanvas;
use App\Models\OrderItem;
use App\Models\Retur;
use App\Models\CategoryItem;
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

    public function linkOrderItem(){
        return $this->hasMany(OrderItem::class,'id_item','id');
    }

    public function linkKanvas(){
      return $this->hasMany(Kanvas::class,'id_item','id');
    }

    public function linkRetur(){
        return $this->hasMany(Retur::class,'id_item','id');
    }

    public function linkCategoryItem(){
      return $this->belongsTo(CategoryItem::class,'id_category','id');
  }
}

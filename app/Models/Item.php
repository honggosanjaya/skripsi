<?php

namespace App\Models;

use App\Models\History;
use App\Models\Pengadaan;
use App\Models\Kanvas;
use App\Models\OrderItem;
use App\Models\Retur;
use App\Models\CategoryItem;
use App\Models\GaleryItem;
use App\Models\ItemPriceList;
use App\Models\GroupItem;
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

    public function linkGaleryItem(){
      return $this->hasMany(GaleryItem::class,'id_item','id');
    }

    public function linkItemPriceList(){
      return $this->hasMany(ItemPriceList::class,'id_item','id');
    }

    public function linkGroupItem(){
      return $this->hasMany(GroupItem::class,'id_item','id');
    }
}

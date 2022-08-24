<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class CategoryItem extends Model
{
    use HasFactory;

    protected $guarded = [
      'id'
    ];

    public function linkItem(){
      return $this->hasMany(Item::class,'id_category','id');
    }
}

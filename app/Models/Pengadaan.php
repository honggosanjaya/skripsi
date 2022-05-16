<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkStaff(){
        return $this->hasMany(Staff::class,'id','id_staff');
    }

    public function linkItem(){
        return $this->hasMany(Item::class,'id','id_item');
    }
}

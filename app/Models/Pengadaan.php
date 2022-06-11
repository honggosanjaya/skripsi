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
        return $this->belongsTo(Staff::class,'id_staff');
    }

    public function linkItem(){
        return $this->belongsTo(Item::class,'id_item');
    }
}

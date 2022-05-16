<?php

namespace App\Models;

use App\Models\OrderTrack;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkOrderTrack(){
        return $this->hasMany(OrderTrack::class,'id_vehicle','id');
    }
}

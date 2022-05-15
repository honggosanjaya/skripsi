<?php

namespace App\Models;

use App\Models\Retur;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturType extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkRetur(){
        return $this->belongsTo(Retur::class,'tipe_retur','id');
    }
}

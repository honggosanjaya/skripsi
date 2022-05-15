<?php

namespace App\Models;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffRole extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkStaff(){
        return $this->belongsTo(Staff::class,'role','id');
    }
}

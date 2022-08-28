<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkInvoice(){
        return $this->hasMany(Invoice::class,'id_event','id');
    }

    public function linkStaff(){
        return $this->belongsTo(Staff::class,'id_staff');
    }
}

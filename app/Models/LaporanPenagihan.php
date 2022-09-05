<?php

namespace App\Models;
use App\Models\Staff;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPenagihan extends Model
{
    use HasFactory;
    
    protected $guarded = [
      'id'
    ];

    public function linkStaffPenagih(){
      return $this->belongsTo(Staff::class,'id_staff_penagih');
    }

    public function linkInvoice(){
      return $this->belongsTo(Invoice::class,'id_invoice')->with(['LinkOrder']);
    }
}

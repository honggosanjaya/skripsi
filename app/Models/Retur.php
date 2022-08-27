<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\ReturType;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkItem(){
        return $this->belongsTo(Item::class,'id_item');
    }

    public function linkStaffPengaju(){
        return $this->belongsTo(Staff::class,'id_staff_pengaju');
    }

    public function linkStaffPengonfirmasi(){
        return $this->belongsTo(Staff::class,'id_staff_pengonfirmasi');
    }

    public function linkCustomer(){
        return $this->belongsTo(Customer::class,'id_customer');
    }

    public function linkInvoice(){
        return $this->belongsTo(Invoice::class,'id_invoice');
    }

    public function linkReturType(){
        return $this->belongsTo(ReturType::class,'tipe_retur');
    }

    // public function linkStatus(){
    //     return $this->belongsTo(Status::class,'status');
    // }
}

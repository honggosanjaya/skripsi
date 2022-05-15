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
        return $this->hasMany(Item::class,'id','id_item');
    }

    public function linkStaffPengaju(){
        return $this->hasMany(Staff::class,'id','id_staff_pengaju');
    }

    public function linkStaffPengonfirmasi(){
        return $this->hasMany(Staff::class,'id','id_staff_pengonfirmasi');
    }

    public function linkCustomer(){
        return $this->hasMany(Customer::class,'id','id_customer');
    }

    public function linkInvoice(){
        return $this->hasMany(Invoice::class,'id','id_invoice');
    }

    public function linkReturType(){
        return $this->hasMany(ReturType::class,'id','tipe_retur');
    }

    public function linkStatus(){
        return $this->hasMany(Status::class,'id','status');
    }
}

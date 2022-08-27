<?php

namespace App\Models;
use App\Models\Staff;
use App\Models\CashAccount;
use App\Models\Status;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reimbursement extends Model
{
    use HasFactory;

    protected $guarded = [
      'id'
    ];

    public function linkStaffPengaju(){
      return $this->belongsTo(Staff::class,'id_staff_pengaju');
    }

    public function linkStaffPengonfirmasi(){
        return $this->belongsTo(Staff::class,'id_staff_pengonfirmasi');
    }

    public function linkCashAccount(){
      return $this->belongsTo(CashAccount::class,'id_cash_account');
    }

}

<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\CashAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function linkStaff(){
      return $this->belongsTo(Staff::class,'id_staff');
    }

    public function linkCashAccount(){
      return $this->belongsTo(CashAccount::class,'id_cash_account');
    }
}

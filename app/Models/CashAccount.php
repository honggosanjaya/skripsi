<?php

namespace App\Models;

use App\Models\Reimbursement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAccount extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [
        'id'
    ];
  
    public function linkReimbursement(){
        return $this->hasMany(Reimbursement::class,'id_cash_account','id');
    }

    public function linkKas(){
      return $this->hasMany(Kas::class,'id_cash_account','id');
    }

    public function linkCashAccount(){
      return $this->belongsTo(CashAccount::class,'account_parent','account');
    }
}

<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [
        'id'
      ];
  
    public function linkCustomer(){
        return $this->hasMany(Customer::class,'id_jenis','id');
    }
}

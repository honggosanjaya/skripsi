<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\District;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
      ];
  
    public function linkCustomer(){
        return $this->hasMany(Customer::class,'id_wilayah','id');
    }

    public function subcategory(){
        return $this->hasMany(District::class,'id_parent');
    }
}

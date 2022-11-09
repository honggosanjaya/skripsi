<?php

namespace App\Models;

use App\Models\Customer;
// use App\Models\District;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

    // public function parent()
    // {
    //     return $this->belongsTo(self::class,'id_parent','id');
    // }
    
    // public function children()
    // {
    //     return $this->hasMany(self::class,'id_parent','id');
    // }
    
    // public function getAscendantsAttribute()
    // {
    //     $parents = collect([]);
    //     $parent = $this->parent;
    //     while(!is_null($parent)) {
    //         $parents->push($parent);
    //         $parent = $parent->parent;
    //     }
    //     return $parents;
    // }
    
    // public function descendants()
    // {
    //     return $this->children()->with('descendants');
    // }
}

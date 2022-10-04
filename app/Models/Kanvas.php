<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\Item;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kanvas extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];
  
    public function linkItem(){
      return $this->belongsTo(Item::class,'id_item', 'id');
    }

    public function linkStaffPengonfirmasiPembawaan(){
      return $this->belongsTo(Staff::class,'id_staff_pengonfirmasi_pembawaan', 'id');
    }

    public function linkStaffPengonfirmasiPengembalian(){
      return $this->belongsTo(Staff::class,'id_staff_pengonfirmasi_pengembalian', 'id');
    }

    public function linkStaffYangMembawa(){
      return $this->belongsTo(Staff::class,'id_staff_yang_membawa', 'id');
    }

}

<?php

namespace App\Models;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $guarded = [
      'id'
    ];

    public function linkinvoice(){
      return $this->hasMany(Invoice::class, 'pembayaran_id');
    }
}

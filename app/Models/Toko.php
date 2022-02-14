<?php

namespace App\Models;

use App\Models\JenisToko;
use App\Models\Trip;
use App\Models\Order;
use App\Models\History;
use App\Models\Retur;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function linkJenisToko()
    {
        return $this->belongsTo(JenisToko::class, 'id');
    }

    public function linkTrip()
    {
        return $this->hasMany(Trip::class, 'toko_id', 'id');
    }

    public function linkOrder()
    {
        return $this->hasMany(Order::class, 'toko_id', 'id')->with(['linkInvoice']);
    }

    public function linkHistory()
    {
        return $this->hasMany(History::class, 'toko_id', 'id');
    }

    public function linkRetur()
    {
        return $this->hasMany(Retur::class, 'toko_id', 'id');
    }
}

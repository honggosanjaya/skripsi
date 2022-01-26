<?php

namespace App\Models;

use App\Models\Toko;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function linktoko()
    {
        return $this->belongsTo(Toko::class, 'id', 'toko_id');
    }

    public function linksales()
    {
        return $this->belongsTo(User::class, 'id', 'sales_id');
    }
}

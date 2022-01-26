<?php

namespace App\Models;

use App\Models\Toko;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisToko extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function linktoko()
    {
        return $this->hasMany(Toko::class, 'id');
    }
}

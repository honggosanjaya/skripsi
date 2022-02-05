<?php

namespace App\Models;

use App\Models\Toko;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisToko extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function linkToko()
    {
        return $this->hasMany(Toko::class, 'jenis_toko_id', 'id');
    }
}

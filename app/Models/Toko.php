<?php

namespace App\Models;

use App\Models\JenisToko;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function linkjenistoko()
    {
        return $this->belongsTo(JenisToko::class, 'id', 'jenis_toko_id');
    }
}

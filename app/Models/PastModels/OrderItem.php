<?php

/*namespace App\Models;
<<<<<<< HEAD

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = [
      'id'
    ];

=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class OrderItem extends Model
{
    use HasFactory;
    protected $guarded = [
      'id'
    ];
>>>>>>> origin/master
    public function linkOrder()
    { 
        return $this->belongsTo(Order::class, 'id', 'order_id');
    }
    public function linkItem()
    {
        return $this->belongsTo(Item::class, 'id', 'item_id')->with(['rellicense','relagent']);
    }
<<<<<<< HEAD
}*/
=======
}*/
>>>>>>> origin/master
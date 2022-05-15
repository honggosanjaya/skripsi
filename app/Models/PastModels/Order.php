<?php

/*namespace App\Models;
<<<<<<< HEAD

=======
>>>>>>> origin/master
use App\Models\Toko;
use App\Models\User;
use App\Models\Invoice;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
<<<<<<< HEAD

class Order extends Model
{
    use HasFactory;

    protected $guarded = [
      'id'
    ];

    public function linkInvoice(){
      return $this->hasOne(Invoice::class, 'order_id')->with(['linkmetodepembayaran']);
    }

    public function linkOrderItem(){
      return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function linkToko(){
      return $this->belongsTo(Toko::class, 'toko_id','id')->with(['linkJenisToko']);
    }

=======
class Order extends Model
{
    use HasFactory;
    protected $guarded = [
      'id'
    ];
    public function linkInvoice(){
      return $this->hasOne(Invoice::class, 'order_id')->with(['linkmetodepembayaran']);
    }
    public function linkOrderItem(){
      return $this->hasMany(OrderItem::class, 'order_id');
    }
    public function linkToko(){
      return $this->belongsTo(Toko::class, 'toko_id','id')->with(['linkJenisToko']);
    }
>>>>>>> origin/master
    public function linksales(){
      return $this->belongsTo(User::class, 'sales_id','id');
    }
}
*/
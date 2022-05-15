<?php

/*namespace App\Models;
<<<<<<< HEAD

=======
>>>>>>> origin/master
use App\Models\Toko;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD

class Trip extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

=======
class Trip extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
>>>>>>> origin/master
    public function linkToko()
    {
        return $this->belongsTo(Toko::class, 'id', 'toko_id');
    }
<<<<<<< HEAD

=======
>>>>>>> origin/master
    public function linkSales()
    {
        return $this->hasOne(User::class, 'id', 'sales_id');
    }
}
<<<<<<< HEAD
*/
=======
*/
>>>>>>> origin/master

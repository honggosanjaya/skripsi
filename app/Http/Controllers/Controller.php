<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\History;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\OrderItem;
use App\Models\Staff;
use App\Models\Item;
use App\Models\Trip;
use App\Models\Event;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Vehicle;
use App\Models\Status;

use App\Helpers\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test(Request $request){
      dd(app('url'));
      // $all=Customer::get();
      // foreach ($all as $one) {
      //   $date=Trip::where('id_customer',$one->id)->orderBy('created_at','DESC')->first()->created_at??null;
      //   Customer::find($one->id)->update([
      //     'updated_at' => $date??null
      //   ]);
      // }
           
    }
}

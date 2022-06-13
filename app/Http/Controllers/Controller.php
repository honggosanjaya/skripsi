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
            $id_staff=$request->id_staff;
            
            $data=Order::
              whereHas('linkOrderTrack',function($q) use( $id_staff) {
                $q->where('id_staff_pengirim', $id_staff);
              })
              ->with(['linkOrderTrack','linkInvoice','linkCustomer','linkOrderItem'])->pluck('id');
      
            $data['a']=Order::
              whereHas('linkOrderTrack',function($q) use( $id_staff) {
                $q->where('id_staff_pengirim', $id_staff);
              })
              ->with(['linkOrderTrack','linkInvoice','linkCustomer','linkOrderItem'])
              ->where(function ($query) {
                $query->whereHas('linkOrderTrack',function($q) {
                        $q->where('status', 22);
                      })
                      ->orWhereHas('linkOrderTrack',function($q) {
                        $q->where('status','>', 22)->whereDate('waktu_sampai',now());
                      });
              })->pluck('id');
      
            dd($data);
           
    }
}

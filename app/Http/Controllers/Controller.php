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
use App\Models\Retur;
use App\Models\Pengadaan;
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
      // $all=Invoice::get();
      // foreach ($all as $one) {
      //   $data= "INV-".Invoice::where('id',$one->id)->first()->nomor_invoice."-".date_format(now(),"YmdHis");
      //   Invoice::find($one->id)->update([
      //     'nomor_invoice' => $data
      //   ]);
      // }
      // $all=Retur::get();
      // foreach ($all as $one) {
      //   $data= "RTR-".Retur::where('id',$one->id)->first()->no_retur."-".date_format(now(),"YmdHis");
      //   Retur::find($one->id)->update([
      //     'no_retur' => $data
      //   ]);
      // }
      // $all=Pengadaan::get();
      // foreach ($all as $one) {
      //   $data= "PGD-".Pengadaan::where('id',$one->id)->first()->no_pengadaan."-".date_format(now(),"YmdHis");
      //   Pengadaan::find($one->id)->update([
      //     'no_pengadaan' => $data
      //   ]);
      // }
           
    }
}

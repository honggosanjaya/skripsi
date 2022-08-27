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
use App\Models\Reimbursement;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test(Request $request){
      // \Storage::delete('/customer/CUST-Honggo-Sanjaya-20220630074556.png');

      // echo asset('storage/qc.txt');
      // \Storage::disk('public')->delete('qc.txt');
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

      // NOTE: ======UNCOMMENT SATU SATU=====


      // $all=Trip::get();
      // foreach ($all as $one) {
      //   if($one->status == 1){
      //     Trip::find($one->id)->update([
      //       'status_enum' => 1
      //     ]);
      //   }elseif($one->status == 2){
      //     Trip::find($one->id)->update([
      //       'status_enum' => 2
      //     ]);
      //   }
      // }


      // $all=Customer::get();
      // foreach ($all as $one) {
      //   if($one->status == 3){
      //     Customer::find($one->id)->update([
      //       'status_enum' => '1'
      //     ]);
      //   }elseif($one->status == 4){
      //     Customer::find($one->id)->update([
      //       'status_enum' => '-1'
      //     ]);
      //   }
      // }


      // $all=Customer::get();
      // foreach ($all as $one) {
      //   if($one->status_limit_pembelian == 5){
      //     Customer::find($one->id)->update([
      //       'status_limit_pembelian_enum' => '1'
      //     ]);
      //   }elseif($one->status_limit_pembelian == 6){
      //     Customer::find($one->id)->update([
      //       'status_limit_pembelian_enum' => '-1'
      //     ]);
      //   }elseif($one->status_limit_pembelian == 7){
      //     Customer::find($one->id)->update([
      //       'status_limit_pembelian_enum' => '0'
      //     ]);
      //   }elseif($one->status_limit_pembelian == null){
      //     Customer::find($one->id)->update([
      //       'status_limit_pembelian_enum' => null
      //     ]);
      //   }
      // }


      // $all=Staff::get();
      // foreach ($all as $one) {
      //   if($one->status == 8){
      //     Staff::find($one->id)->update([
      //       'status_enum' => '1'
      //     ]);
      //   }elseif($one->status == 9){
      //     Staff::find($one->id)->update([
      //       'status_enum' => '-1'
      //     ]);
      //   }
      // }


      // $all=Item::get();
      // foreach ($all as $one) {
      //   if($one->status == 10){
      //     Item::find($one->id)->update([
      //       'status_enum' => '1'
      //     ]);
      //   }elseif($one->status == 11){
      //     Item::find($one->id)->update([
      //       'status_enum' => '-1'
      //     ]);
      //   }
      // }


      // $all=Retur::get();
      // foreach ($all as $one) {
      //   if($one->status == 12){
      //     Retur::find($one->id)->update([
      //       'status_enum' => '1'
      //     ]);
      //   }elseif($one->status == 13){
      //     Retur::find($one->id)->update([
      //       'status_enum' => '0'
      //     ]);
      //   }
      // }


      // $all=Order::get();
      // foreach ($all as $one) {
      //   if($one->status == 14){
      //     Order::find($one->id)->update([
      //       'status_enum' => '1'
      //     ]);
      //   }elseif($one->status == 15){
      //     Order::find($one->id)->update([
      //       'status_enum' => '-1'
      //     ]);
      //   }
      // }


      // $all=Event::get();
      // foreach ($all as $one) {
      //   if($one->status == 16){
      //     Event::find($one->id)->update([
      //       'status_enum' => '1'
      //     ]);
      //   }elseif($one->status == 17){
      //     Event::find($one->id)->update([
      //       'status_enum' => '-1'
      //     ]);
      //   }elseif($one->status == 18){
      //     Event::find($one->id)->update([
      //       'status_enum' => '0'
      //     ]);
      //   }elseif($one->status == 26){
      //     Event::find($one->id)->update([
      //       'status_enum' => '-2'
      //     ]);
      //   }
      // }
           

      // $all=OrderTrack::get();
      // foreach ($all as $one) {
      //   if($one->status == 19){
      //     OrderTrack::find($one->id)->update([
      //       'status_enum' => '0'
      //     ]);
      //   }elseif($one->status == 20){
      //     OrderTrack::find($one->id)->update([
      //       'status_enum' => '1'
      //     ]);
      //   }elseif($one->status == 21){
      //     OrderTrack::find($one->id)->update([
      //       'status_enum' => '2'
      //     ]);
      //   }elseif($one->status == 22){
      //     OrderTrack::find($one->id)->update([
      //       'status_enum' => '3'
      //     ]);
      //   }elseif($one->status == 23){
      //     OrderTrack::find($one->id)->update([
      //       'status_enum' => '4'
      //     ]);
      //   }elseif($one->status == 24){
      //     OrderTrack::find($one->id)->update([
      //       'status_enum' => '5'
      //     ]);
      //   }elseif($one->status == 25){
      //     OrderTrack::find($one->id)->update([
      //       'status_enum' => '-1'
      //     ]);
      //   }
      // }


      // $all=Reimbursement::get();
      // foreach ($all as $one) {
      //   if($one->status == 27){
      //     Reimbursement::find($one->id)->update([
      //       'status_enum' => '0'
      //     ]);
      //   }elseif($one->status == 28){
      //     Reimbursement::find($one->id)->update([
      //       'status_enum' => '1'
      //     ]);
      //   }elseif($one->status == 29){
      //     Reimbursement::find($one->id)->update([
      //       'status_enum' => '2'
      //     ]);
      //   }elseif($one->status == 30){
      //     Reimbursement::find($one->id)->update([
      //       'status_enum' => '-1'
      //     ]);
      //   }
      // }


    }
}

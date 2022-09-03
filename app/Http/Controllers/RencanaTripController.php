<?php

namespace App\Http\Controllers;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\RencanaTrip;
use Illuminate\Http\Request;

class RencanaTripController extends Controller
{

  public function storeRencana(Request $request){
    foreach($request->id_customer as $id_cust){
      RencanaTrip::insert([
        'id_customer' => $id_cust,
        'id_staff' => $request->id_staff,
        'tanggal' => $request->tanggal,
        'status_enum' => '-1'
      ]);
    }

    return redirect('/administrasi')->with('pesanSukses','Berhasil membuat rencana kunjungan'); 
  }

  public function datakunjunganAPI(Request $request, $id){
    $date = $request->date;

    $allrencanas = RencanaTrip::where('id_staff', $id)
    ->with(['linkCustomer', 'linkCustomer.linkDistrict'])
    ->get();

    $daterencanas = RencanaTrip::where('id_staff', $id)
    ->whereDate('tanggal', '=', $date)
    ->with(['linkCustomer', 'linkCustomer.linkDistrict'])
    ->get();

    if($date == null){
      return response()->json([
        'data' => $allrencanas,
        'status' => 'success'
      ]);
    }else{
      return response()->json([
        'data' => $daterencanas,
        'status' => 'success'
      ]);
    }
  }
}

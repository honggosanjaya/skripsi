<?php

namespace App\Http\Controllers;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\District;
use App\Models\Trip;
use App\Models\RencanaTrip;
use App\Models\LaporanPenagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Jenssegers\Agent\Agent;

class RencanaTripController extends Controller
{
  public function index(Request $request){
    $input=[
      'dateStart'=>date('Y-m-01'),
      'dateEnd'=>date('Y-m-t')
    ];

    $customers = Customer::all();
    $staffs = Staff::where('status_enum','1')->where('role', 3)->get();
    $histories = RencanaTrip::orderBy('tanggal', 'DESC')->get();
    $districts = District::orderBy('nama', 'ASC')->get();

    $data = [
      'input' => $input,
      'customers' => $customers,  
      'staffs' => $staffs,
      'histories' => $histories,
      'districts' => $districts
    ];

    $agent = new Agent();
    if($agent->isMobile()){
      return view('mobile.administrasi.rencanakunjungan.index',$data);
    }else{
      return view('administrasi.rencanakunjungan.index',$data);
    }
  }

  public function storeRencana(Request $request){
    $request->validate([
      'id_customer' => 'required',
      'id_staff' => 'required',
      'tanggal' => 'required'           
    ]);

    foreach($request->id_customer as $index => $id_cust){
      $waktu_tanggal_start = $request->tanggal." 00:00:00";
      $waktu_tanggal_end = $request->tanggal." 23:59:59";

      $trip = Trip::where('id_customer', $id_cust)
      ->where('id_staff', $request->id_staff)    
      ->whereBetween('waktu_masuk',[$waktu_tanggal_start, $waktu_tanggal_end])
      ->get();

      if(count($trip) > 0){
        RencanaTrip::insert([
          'id_customer' => $id_cust,
          'id_staff' => $request->id_staff,
          'tanggal' => $request->tanggal,
          'status_enum' => '1',
          'estimasi_nominal' => $request->estimasi_nominal[$index],
          'created_at' => now(),
          'updated_at' => now()
        ]);
      }else{
        RencanaTrip::insert([
          'id_customer' => $id_cust,
          'id_staff' => $request->id_staff,
          'tanggal' => $request->tanggal,
          'status_enum' => '-1',
          'estimasi_nominal' => $request->estimasi_nominal[$index],
          'created_at' => now(),
          'updated_at' => now()
        ]);
      }
    }

    return redirect('/administrasi/rencanakunjungan')->with('successMessage','Berhasil membuat rencana kunjungan'); 
  }

  public function datakunjunganAPI(Request $request, $id){
    $date = $request->date ?? date('Y-m-d');

    $rencana = RencanaTrip::where('rencana_trips.id_staff', $id)
                ->whereDate('tanggal', '=', $date)
                ->join('customers','customers.id','=','rencana_trips.id_customer')
                ->join('districts','customers.id_wilayah','=','districts.id')
                ->selectRaw('rencana_trips.id as id_rencana, rencana_trips.status_enum as status_rencana, 
                              rencana_trips.estimasi_nominal, customers.id as id_customer,
                              customers.nama as nama_customer, districts.nama as nama_wilayah')
                ->orderBy('customers.nama', 'ASC')
                ->get()->toArray();

    $tagihan = LaporanPenagihan::where('id_staff_penagih',$id)
                ->whereDate('tanggal', '=', $date)
                ->join('invoices','invoices.id','=','laporan_penagihans.id_invoice')
                ->join('orders','orders.id','=','invoices.id_order')
                ->join('customers','customers.id','=','orders.id_customer')
                ->join('districts','customers.id_wilayah','=','districts.id')
                ->selectRaw('laporan_penagihans.id as id_tagihan, laporan_penagihans.id_invoice, 
                              laporan_penagihans.status_enum as status_penagihan, 
                              customers.id as id_customer, customers.nama as nama_customer, 
                              districts.nama as nama_wilayah')
                ->orderBy('customers.nama', 'ASC')
                ->get()->toArray();

    $data = array_merge($rencana, $tagihan);

    function sort_array_by_key($array, $sort_key){
      $key_array = array_column($array, $sort_key);
      array_multisort($key_array, SORT_ASC, $array);
      return $array;
    }
    $sorted_data = sort_array_by_key($data, 'nama_customer');
    // dd($sorted_data);

    return response()->json([
      'data' => $sorted_data,
      // 'data_tagihan' => $tagihan,
      'status' => 'success'
    ]);
  }

  public function cetakRAK(Request $request){
    $salesman = $request->salesman;    
    $dateStart = $request->dateStart." 00:00:00";
    $dateEnd = $request->dateEnd." 23:59:59";

    $trips = Trip::whereBetween('waktu_masuk', [$dateStart, $dateEnd])
              ->whereHas('linkStaff', function($q) use($salesman) {
                $q->where(strtolower('nama'),'like','%'.$salesman.'%');
              })->get();

    $rencana_trips = RencanaTrip::whereBetween('tanggal', [$request->dateStart, $request->dateEnd])
                      ->whereHas('linkStaff', function($q) use($salesman) {
                        $q->where(strtolower('nama'),'like','%'.$salesman.'%');
                      })->get();

    $trip_rak_complete = [];
    $id_trip_complete = [];
    $id_rak_complete = [];

    foreach($trips as $trip){
      foreach($rencana_trips as $rencana){
        if($rencana->id_customer == $trip->id_customer && $rencana->tanggal == date("Y-m-d", strtotime($trip->waktu_masuk))) {
            array_push($id_trip_complete, $trip->id);  
            array_push($id_rak_complete, $rencana->id);  

            array_push($trip_rak_complete, [
              "trip" => $trip,
              "rencana_trip" => $rencana,
            ]);
        }
      }
    }

    $trip_not_complete = Trip::whereNotIn('id',$id_trip_complete)
                          ->whereBetween('waktu_masuk', [$dateStart, $dateEnd])
                          ->whereHas('linkStaff', function($q) use($salesman) {
                            $q->where(strtolower('nama'),'like','%'.$salesman.'%');
                          })->orderBy('created_at', 'DESC')->get();

    $rak_not_complete = RencanaTrip::whereNotIn('id',$id_rak_complete)
                          ->whereBetween('tanggal', [$request->dateStart, $request->dateEnd])
                          ->whereHas('linkStaff', function($q) use($salesman) {
                            $q->where(strtolower('nama'),'like','%'.$salesman.'%');
                          })->orderBy('created_at', 'DESC')->get();

    
    $pdf = PDF::loadview('administrasi.rencanakunjungan.cetakRAK',[
      'trip_not_complete' => $trip_not_complete,
      'rak_not_complete' => $rak_not_complete,
      'trip_rak_complete' => $trip_rak_complete, 
      'document_title' => 'RAK-'.date("d-m-Y", strtotime($request->dateStart)). '_' . date("d-m-Y", strtotime($request->dateEnd))
    ]);

    $pdf->setPaper('A4', 'landscape');

    return $pdf->stream('RAK-'.date("d-m-Y", strtotime($request->dateStart)). '_' . date("d-m-Y", strtotime($request->dateEnd)) .'.pdf'); 
  }
}

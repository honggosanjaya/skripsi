<?php

namespace App\Http\Controllers;
use App\Models\Invoice;
use App\Models\LaporanPenagihan;
use App\Models\Staff;
use App\Models\Order;
use App\Models\Customer;
use App\Models\District;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use PDF;
use Jenssegers\Agent\Agent;

class LaporanPenagihanController extends Controller
{
  
  public function index(){
    $input=[
      'dateStart'=>date('Y-m-01'),
      'dateEnd'=>date('Y-m-t')
    ];

    $invoices = Invoice::whereHas('linkOrder', function($q) {
      $q->whereHas('linkOrderTrack', function($q) {
        $q->where('status_enum','4');
      });
    })->get();
    
    $staffs = Staff::where('status_enum','1')->whereIn('role', [3, 4])->get();
    $histories = LaporanPenagihan::orderBy('tanggal', 'DESC')->orderBy('id', 'DESC')->get();
    $districts = District::orderBy('nama', 'ASC')->get();

    $data = [
      'input' => $input,
      'invoices' => $invoices,  
      'staffs' => $staffs,   
      'histories' => $histories,
      'districts' => $districts          
    ];

    $agent = new Agent();
    if($agent->isMobile()){
      return view('mobile.administrasi.lp3.index',$data);
    }else{
      return view('administrasi.lp3.index',$data);
    }
  }

  public function storeLp3(Request $request){
    $request->validate([
      'id_invoice' => 'required',
      'id_staff_penagih' => 'required',
      'tanggal' => 'required'           
    ]);

    foreach($request->id_invoice as $inv){
      LaporanPenagihan::insert([
        'id_invoice' => $inv,
        'id_staff_penagih' => $request->id_staff_penagih,
        'tanggal' => $request->tanggal,
        'status_enum' => '-1',
        'created_at' => now()
      ]);
    }

    return redirect('/administrasi/lp3')->with('successMessage','Berhasil membuat LP3'); 
  }

  public function getDetailPenagihanAPI(Invoice $invoice){
    $idOrder = Invoice::where('id', $invoice->id)->first()->id_order;
    $idCustomer = Order::where('id', $idOrder)->first()->id_customer;
    $customer = Customer::where('id', $idCustomer)->first();
    $inv = Invoice::where('id', $invoice->id)->with(['linkPembayaran', 'linkLaporanPenagihan'])->first();
    $lp3 = LaporanPenagihan::where('id_invoice', $invoice->id)->where('status_enum','-1')->first();

    $total_bayar = Invoice::where('invoices.id', $invoice->id)
    ->join('pembayarans','invoices.id','=','pembayarans.id_invoice')
    ->whereHas('linkOrder', function($q) {
      $q->whereHas('linkOrderTrack', function($q) {
        $q->whereIn('status_enum',['4','5','6']);
      });
    })
    ->select('pembayarans.id_invoice', \DB::raw('SUM(pembayarans.jumlah_pembayaran) as total_bayar'))
    ->groupBy('pembayarans.id_invoice')->get()->sum('total_bayar');

    $tagihan = $invoice->harga_total - $total_bayar; 

    return response()->json([
      'data' => [
        'customer' => $customer,
        'tagihan' => $tagihan,
        'invoice' => $inv,
        'lp3' => $lp3
      ],
      'status' => 'success',
    ]);
  }

  // public function getPenagihanLapanganAPI(Request $request, Staff $staff){
  public function getPenagihanLapanganAPI(Staff $staff){
    // $date = $request->date;

    $Alltagihans = LaporanPenagihan::where('id_staff_penagih',$staff->id)
    ->orderBy('tanggal', 'ASC')
    ->with('linkInvoice')
    ->get();

    // $Specifictagihans = LaporanPenagihan::where('id_staff_penagih', $staff->id)
    // ->whereDate('tanggal', '=', $date)
    // ->with('linkInvoice')
    // ->get();

    // if($date == null){
      return response()->json([
        'data' => $Alltagihans,
        'status' => 'success'
      ]);
    // }else{
    //   return response()->json([
    //     'data' => $Specifictagihans,
    //     'status' => 'success'
    //   ]);
    // }
  }

  public function handlePenagihanLapanganAPI($id){
    $lp3 = LaporanPenagihan::where('id',$id)->first();
    $invoice = Invoice::where('id', $lp3->id_invoice)->first();
    $order = Order::where('id', $invoice->id_order)->first();
    $customer = Customer::where('id', $order->id_customer)->first()->nama;

    LaporanPenagihan::where('id',$id)->update([
      'status_enum' => '1'
    ]);

    return response()->json([
      'status' => 'success',
      'message' => 'berhasil mengonfirmasi penagihan pembayaran untuk '.$customer
    ]);
  }

  public function cetakLp3(Request $request){
    $lp3s = LaporanPenagihan::whereBetween('tanggal', [$request->dateStart, $request->dateEnd])
            ->with(['linkStaffPenagih', 'linkInvoice', 'linkInvoice.linkPembayaran', 'linkInvoice.linkOrder.linkCustomer'])
            ->orderBy('tanggal', 'ASC')
            ->get();
    // dd($lp3s);

    $invoices = Invoice::join('pembayarans','invoices.id','=','pembayarans.id_invoice')
                    ->whereHas('linkOrder', function($q) {
                      $q->whereHas('linkOrderTrack', function($q) {
                        $q->whereIn('status_enum',['4','5','6']);
                      });
                    })
                    ->select('invoices.id', 'invoices.nomor_invoice', \DB::raw('SUM(pembayarans.jumlah_pembayaran) as total_bayar'))
                    ->groupBy('invoices.id', 'invoices.nomor_invoice')->get();
    // dd($invoices);              

    $pembayarans = Invoice::join('pembayarans','invoices.id','=','pembayarans.id_invoice')
                ->whereHas('linkOrder', function($q) {
                  $q->whereHas('linkOrderTrack', function($q) {
                    $q->whereIn('status_enum',['4','5','6']);
                  });
                })
                ->select('invoices.id', 'invoices.nomor_invoice', \DB::raw('SUM(pembayarans.jumlah_pembayaran) as jml_pembayaran'), 'pembayarans.tanggal', 'pembayarans.id_staff_penagih')
                ->groupBy('invoices.id', 'invoices.nomor_invoice', 'pembayarans.tanggal', 'pembayarans.id_staff_penagih')->get();
    // dd($pembayarans);

    $pdf = PDF::loadview('administrasi.lp3.cetakLp3',[
      'lp3s' => $lp3s,     
      'invoices' => $invoices,
      'pembayarans' => $pembayarans,
      'document_title' => 'LP3-'.date("d-m-Y", strtotime($request->dateStart)). '_' . date("d-m-Y", strtotime($request->dateEnd))
    ]);

    $pdf->setPaper('A4', 'landscape');

    return $pdf->stream('LP3-'.date("d-m-Y", strtotime($request->dateStart)). '_' . date("d-m-Y", strtotime($request->dateEnd)) .'.pdf'); 
  }
}

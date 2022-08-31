<?php

namespace App\Http\Controllers;
use App\Models\Invoice;
use App\Models\LaporanPenagihan;
use App\Models\Staff;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;

class LaporanPenagihanController extends Controller
{
  
  public function index(){
    $AllInvoices = [];
    // invoice yang sedang ditagih
    $invs = Invoice::whereHas('linkLaporanPenagihan', function($q) {
        $q->where('status_enum','-1');
    })->get();

    $invTagih = [];

    for($i=0; $i<count($invs); $i++){
      $invTagih[$i] = $invs[$i]->id;
    }

    // dd($invTagih);

    $selectableInvoices = Invoice::whereNotIn('id', $invTagih)->whereHas('linkOrder', function($q) {
      $q->whereHas('linkOrderTrack', function($q) {
        $q->where('status_enum','4');
      });
    })->get();

    foreach($selectableInvoices as $invoice){
      array_push($AllInvoices,[
        'id' => $invoice->id,
        'nomor_invoice' => $invoice->nomor_invoice,
        'is_disabled' => false
      ]);
    }

    $invoices = Invoice::whereIn('id', $invTagih)->whereHas('linkOrder', function($q) {
      $q->whereHas('linkOrderTrack', function($q) {
        $q->where('status_enum','4');
      });
    })->get();

    foreach($invoices as $invoice){
      array_push($AllInvoices,[
        'id' => $invoice->id,
        'nomor_invoice' => $invoice->nomor_invoice,
        'is_disabled' => true
      ]);
    }
    
    $staffs = Staff::where('status_enum','1')->whereIn('role', [3, 4])->get();
    $histories = LaporanPenagihan::all();

    return view('administrasi.lp3.index',[
      'invoices' => $AllInvoices,  
      'staffs' => $staffs,   
      'histories' => $histories          
    ]);
  }

  public function storeLp3(Request $request){
    $rules = [
      'id_invoice' => ['required'],
      'id_staff_penagih' => ['required'],
      'tanggal' => ['required'],
    ];
    
    $validatedData = $request->validate($rules);
    $validatedData['status_enum'] = '-1';
    $validatedData['created_at'] = now();
    LaporanPenagihan::insert($validatedData);

    return redirect('/administrasi/lp3')->with('pesanSukses','Berhasil membuat LP3'); 
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

  public function getPenagihanLapanganAPI(Staff $staff){
    $tagihans = LaporanPenagihan::where('id_staff_penagih',$staff->id)->where('status_enum','-1')
    ->orderBy('tanggal', 'ASC')
    ->with('linkInvoice')
    ->get();

    return response()->json([
      'data' => $tagihans,
      'status' => 'success',
    ]);
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
}

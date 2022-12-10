<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\Invoice;
use App\Models\Kas;
use App\Models\Customer;

class ReportAnalisaPenjualan implements FromView, ShouldAutoSize
{
  use Exportable;

  private $request;

  function __construct($request) {
    $this->request = $request;
  }

  public function view(): View
  {
    $data = [];

    for ($i = 5; $i >= 0; $i--){
      $dateStart = date("Y-m-01", strtotime("-".$i." months"))." 00:00:00";
      $dateEnd = date("Y-m-t", strtotime("-".$i." months"))." 23:59:59";
      $customers = Customer::select('id','nama')->where('status_enum', '1')->with([
        'linkOrder' => function($q) use($dateStart, $dateEnd){
              $q->whereHas('linkInvoice', function($q) use($dateStart, $dateEnd){
                $q->whereBetween('created_at', [$dateStart, $dateEnd]);
              });
          }])->get()->toArray();
     
      $data['bulan-'.$i] = $customers;
      array_merge($data, $data['bulan-'.$i]);
    }

    // dd($data);

    return view('excel.analisa_penjualan',[
      'dateStart' => $dateStart,
      'dateEnd' => $dateEnd,
      'customers' => Customer::where('status_enum', '1')->get(),
      'data' => $data
    ]);
  }

  // public function view(): View
  // {
  //   $data = [];
  //   $id_cust = [];

  //   for ($i = 5; $i >= 0; $i--){
  //     $dateStart = date("Y-m-01", strtotime("-".$i." months"))." 00:00:00";
  //     $dateEnd = date("Y-m-t", strtotime("-".$i." months"))." 23:59:59";
  //     $invoices = Invoice::whereBetween('created_at', [$dateStart, $dateEnd])->with(['linkOrder'])->get();

  //     if(count($invoices) > 0){
  //       foreach($invoices as $invoice){
  //         if (!in_array($invoice->linkOrder->id_customer, $id_cust)){
  //           array_push($id_cust, $invoice->linkOrder->id_customer);
  //         }
  
  //         array_push($data, [
  //           'bulan-'.$i => [
  //             'id_customer' => $invoice->linkOrder->id_customer,
  //             'nama_customer' => $invoice->linkOrder->linkCustomer->nama,
  //             'nomor_invoice' => $invoice->nomor_invoice,
  //             'harga_total' =>  $invoice->harga_total,
  //             'bulan' => 'bulan-'.$i
  //           ]
  //         ]);
  //       }
  //     }
  //   }

  //   $grouped_data = [];
  //   foreach ($data as $element) {
  //     foreach ($element as $key => $value) {
  //         if (!isset($grouped_data[$key])) $grouped_data[$key] = [];
  //         $grouped_data[$key][] = $value;
  //     }
  //   }

  //   $generated_data = [];
  //   foreach ($grouped_data as $grouped_data_key => $value){
  //     foreach ($grouped_data[$grouped_data_key] as $dt) {
  //       $key = $dt['id_customer'];
  //       if (!array_key_exists($key, $generated_data)) {
  //         $generated_data[$key] = [
  //           $grouped_data_key => [
  //             'id_customer' => $dt['id_customer'],
  //             'nama_customer' => $dt['nama_customer'],
  //             'nomor_invoice' => $dt['nomor_invoice'],
  //             'harga_total' => $dt['harga_total'],
  //             'bulan' => $dt['bulan'],
  //           ]
  //         ];
  //       } else {
  //         $generated_data[$key][$grouped_data_key]['harga_total'] = $generated_data[$key][$grouped_data_key]['harga_total'] + $dt['harga_total'];
  //       }
  //     }
  //   }

  //   $grouped_generated_data = [];
  //   foreach ($generated_data as $element) {
  //     foreach ($element as $key => $value) {
  //         if (!isset($grouped_generated_data[$key])) $grouped_generated_data[$key] = [];
  //         $grouped_generated_data[$key][] = $value;
  //     }
  //   }

  //   // dd($grouped_generated_data);

  //   return view('excel.analisa_penjualan',[
  //     'dateStart' => $dateStart,
  //     'dateEnd' => $dateEnd,
  //     'customers' => Customer::all(),
  //     'data' => $grouped_generated_data
  //   ]);
  // }
}

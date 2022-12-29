<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\Trip;
use App\Models\Invoice;
use App\Models\Pembayaran;
use App\Models\Staff;

class ReportAktivitasKunjungan implements FromView, ShouldAutoSize
{
  use Exportable;

  private $request;

  function __construct($request) {
    $this->request = $request;
  }

  public function view(): View
  {
      if (!$this->request->dateTrip ?? null) {
          $dateTrip = date('Y-m-d');  
      }else {
          $dateTrip = $this->request->dateTrip;  
      }
      $sales = $this->request->salesmanAktivitasKunjungan;

      $data = Trip::where('id_staff', $sales)->whereDate('waktu_masuk', $dateTrip)->get();

      $invoice = Invoice::whereDate('created_at', $dateTrip)->whereHas('linkOrder', function($q) use($sales) {
        $q->where('id_staff', $sales);
      })->with(['linkOrder','linkPembayaran'])->get();
      // dd($invoice->toArray());  

      $group_invoice = [];
      foreach ($invoice as $item) {
        $key = $item->linkOrder->id_customer;
        if (!array_key_exists($key, $group_invoice)) {
          // jika tidak ada yang sama
          $group_invoice[$key] = array(
            'id_customer' => $item->linkOrder->id_customer,
            'harga_total' => $item->harga_total
          );
        } else {
          // jika ada yang sama
          $group_invoice[$key]['id_customer'] = $group_invoice[$key]['id_customer'];
          $group_invoice[$key]['harga_total'] = $group_invoice[$key]['harga_total'] + $item->harga_total;
        }
      }
      // dd($group_invoice);
    
      return view('excel.aktivitas_kunjungan',[
        'trip' => [
          'date' => $dateTrip,
          'sales' => Staff::find($sales)->nama,
        ],
        'data' => $data,
        'group_invoice' => $group_invoice
      ]);
  }
}

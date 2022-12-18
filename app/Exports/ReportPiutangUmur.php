<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\Invoice;
use App\Models\Pembayaran;

class ReportPiutangUmur implements FromView, ShouldAutoSize
{
  use Exportable;

  private $request;

  function __construct($request) {
    $this->request = $request;
  }

  public function view(): View
  {
      if (!$this->request->dateStartPiutangUmur ?? null) {
          $dateStart = date('Y-m-01');  
      }else {
          $dateStart = $this->request->dateStartPiutangUmur;  
      }
      if (!$this->request->dateEndPiutangUmur ?? null) {
          $dateEnd = date('Y-m-t'); 
      }else {
          $dateEnd = $this->request->dateEndPiutangUmur;  
      }

      $dateStart = $dateStart." 00:00:00";
      $dateEnd = $dateEnd." 23:59:59";

      $data = Invoice::whereBetween('created_at', [$dateStart, $dateEnd])->whereHas('linkOrder', function($q) use($dateStart, $dateEnd) {
        $q->whereHas('linkOrderTrack', function($q) use($dateStart, $dateEnd){
          $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$dateStart, $dateEnd]);
        });
      });

      $fakturs = $data->get();
      $total_faktur = $data->select(\DB::raw('SUM(harga_total) as total'))->get()->sum('total');

      $total_pembayaran = Pembayaran::whereHas('linkInvoice', function($q) use($dateStart, $dateEnd){
        $q->whereBetween('created_at', [$dateStart, $dateEnd])->whereHas('linkOrder', function($q) use($dateStart, $dateEnd) {
          $q->whereHas('linkOrderTrack', function($q) use($dateStart, $dateEnd){
            $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$dateStart, $dateEnd]);
          });
        });
      })->select(\DB::raw('SUM(jumlah_pembayaran) as total_pembayaran'))->get()->sum('total_pembayaran');
    
      return view('excel.piutang_umur_piutang',[
        'dateStart' => $dateStart,
        'dateEnd' => $dateEnd,
        'fakturs' => $fakturs,
        'total' => [
          'total_faktur' => $total_faktur,
          'total_pembayaran' => $total_pembayaran
        ]
      ]);
  }
}

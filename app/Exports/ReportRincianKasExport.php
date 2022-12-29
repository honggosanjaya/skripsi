<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\Kas;

class ReportRincianKasExport implements FromView, ShouldAutoSize
{
  use Exportable;

  private $request;

  function __construct($request) {
    $this->request = $request;
  }

  public function view(): View
  {
      if (!$this->request->dateStartRincianKas ?? null) {
          $dateStart = date('Y-m-01');  
      }else {
          $dateStart = $this->request->dateStartRincianKas;  
      }
      if (!$this->request->dateEndRincianKas ?? null) {
          $dateEnd = date('Y-m-t'); 
      }else {
          $dateEnd = $this->request->dateEndRincianKas;  
      }

      // $dateStart = $dateStart." 00:00:00";
      // $dateEnd = $dateEnd." 23:59:59";

      $all_kas = Kas::where('kas', $this->request->kas)->whereBetween('tanggal', [$dateStart, $dateEnd])
      ->with(['linkCashAccount'])->get()->groupBy('id_cash_account');
      
      $total_perkas = Kas::where('kas', $this->request->kas)->whereBetween('tanggal', [$dateStart, $dateEnd])->select('id_cash_account', \DB::raw('SUM(uang) as total_kas'))->groupBy('id_cash_account')
      ->get()->groupBy('id_cash_account');

      $total_kas = Kas::where('kas', $this->request->kas)->whereBetween('tanggal', [$dateStart, $dateEnd])->select(\DB::raw('SUM(uang) as total_kas'))->get()->sum('total_kas');

      // dd($total_perkas);

      return view('excel.rincian_kas',[
        'dateStart' => $dateStart,
        'dateEnd' => $dateEnd,
        'all_kas' => $all_kas,
        'total_perkas' => $total_perkas,
        'total_kas' => $total_kas
      ]);
  }
}

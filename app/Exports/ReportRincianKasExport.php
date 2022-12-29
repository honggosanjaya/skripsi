<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\CashAccount;
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
      ->orderBy('tanggal', 'ASC')->with(['linkCashAccount'])->get();
      
      $total_kredit = Kas::where('kas', $this->request->kas)->where('debit_kredit', '-1')
      ->whereBetween('tanggal', [$dateStart, $dateEnd])
      ->select(\DB::raw('SUM(uang) as total_kredit'))->get()->sum('total_kredit');

      $total_debit = Kas::where('kas', $this->request->kas)->where('debit_kredit', '1')
      ->whereBetween('tanggal', [$dateStart, $dateEnd])
      ->select(\DB::raw('SUM(uang) as total_debit'))->get()->sum('total_debit');

      return view('excel.rincian_kas',[
        'dateStart' => $dateStart,
        'dateEnd' => $dateEnd,
        'all_kas' => $all_kas,
        'total_kas' => $total_debit - $total_kredit,
        'nama_kas' => CashAccount::find($this->request->kas)->nama
      ]);
  }
}

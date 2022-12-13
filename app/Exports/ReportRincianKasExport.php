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
      if (!$this->request->dateStart ?? null) {
          $dateStart = date('Y-m-01');  
      }else {
          $dateStart = $this->request->dateStart;  
      }
      if (!$this->request->dateEnd ?? null) {
          $dateEnd = date('Y-m-t'); 
      }else {
          $dateEnd = $this->request->dateEnd;  
      }

      // $dateStart = $dateStart." 00:00:00";
      // $dateEnd = $dateEnd." 23:59:59";

      $kas = Kas::where('kas', $this->request->id)->where('debit_kredit', '-1')->whereBetween('tanggal', [$dateStart, $dateEnd])->with(['linkCashAccount'])->get();
      $total_kas = Kas::where('kas', $this->request->id)->where('debit_kredit', '-1')->whereBetween('tanggal', [$dateStart, $dateEnd])->select(\DB::raw('SUM(uang) as total_kas'))->get()->sum('total_kas');

      return view('excel.rincian_kas',[
        'dateStart' => $dateStart,
        'dateEnd' => $dateEnd,
        'kas' => $kas,
        'total_kas' => $total_kas
      ]);
  }
}

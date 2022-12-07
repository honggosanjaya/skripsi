<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\Invoice;

class ReportPenjualanSalesExport implements FromView, ShouldAutoSize
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

        $dateStart = $dateStart." 00:00:00";
        $dateEnd = $dateEnd." 23:59:59";

        $fakturs = Invoice::whereBetween('created_at', [$dateStart, $dateEnd])->whereHas('linkOrder', function($q) use($dateStart, $dateEnd) {
                      $q->whereHas('linkOrderTrack', function($q) use($dateStart, $dateEnd){
                        $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$dateStart, $dateEnd]);
                      });
                    })->with(['linkRetur'])->get();

        // $fakturs = Invoice::whereBetween('created_at', [$dateStart, $dateEnd])->with(['linkRetur'])->get();

        return view('excel.penjualan_per_sales',[
          'dateStart' => $dateStart,
          'dateEnd' => $dateEnd,
          'fakturs' => $fakturs
        ]);
    }
}

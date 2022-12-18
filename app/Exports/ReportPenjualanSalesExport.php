<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\Invoice;
use App\Models\Pembayaran;
use App\Models\Retur;

class ReportPenjualanSalesExport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $request;

    function __construct($request) {
      $this->request = $request;
    }

    public function view(): View
    {
        if (!$this->request->dateStartPenjualanSales ?? null) {
            $dateStart = date('Y-m-01');  
        }else {
            $dateStart = $this->request->dateStartPenjualanSales;  
        }
        if (!$this->request->dateEndPenjualanSales ?? null) {
            $dateEnd = date('Y-m-t'); 
        }else {
            $dateEnd = $this->request->dateEndPenjualanSales;  
        }

        $dateStart = $dateStart." 00:00:00";
        $dateEnd = $dateEnd." 23:59:59";

        $invoice = Invoice::whereBetween('created_at', [$dateStart, $dateEnd])->whereHas('linkOrder', function($q) use($dateStart, $dateEnd) {
                      $q->whereHas('linkOrderTrack', function($q) use($dateStart, $dateEnd){
                        $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$dateStart, $dateEnd]);
                      });
                    });

        $fakturs = $invoice->with(['linkRetur', 'linkPembayaran'])->get();

        $total_faktur = $invoice->select(\DB::raw('SUM(harga_total) as total'))
                        ->get()->sum('total');

        $total_pembayaran = Pembayaran::whereHas('linkInvoice', function($q) use($dateStart, $dateEnd) {
          $q->whereBetween('created_at', [$dateStart, $dateEnd])->whereHas('linkOrder', function($q) use($dateStart, $dateEnd) {
            $q->whereHas('linkOrderTrack', function($q) use($dateStart, $dateEnd){
              $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$dateStart, $dateEnd]);
            });
          });
        })->select(\DB::raw('SUM(jumlah_pembayaran) as total_pembayaran'))->get()->sum('total_pembayaran');

        $total_retur = Retur::where('status_enum','1')->where('tipe_retur','1')->whereHas('linkInvoice', function($q) use($dateStart, $dateEnd) {
          $q->whereBetween('created_at', [$dateStart, $dateEnd])->whereHas('linkOrder', function($q) use($dateStart, $dateEnd) {
            $q->whereHas('linkOrderTrack', function($q) use($dateStart, $dateEnd){
              $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$dateStart, $dateEnd]);
            });
          });
        })->select(\DB::raw('SUM(kuantitas*harga_satuan) as total_retur'))->get()->sum('total_retur');

        return view('excel.penjualan_per_sales',[
          'dateStart' => $dateStart,
          'dateEnd' => $dateEnd,
          'fakturs' => $fakturs,
          'total' => [
            'total_faktur' => $total_faktur,
            'total_retur' => $total_retur,
            'total_hutang' => $total_faktur - $total_pembayaran,
          ]
        ]);
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\Pembayaran;

class ReportPenerimaanPelanggan implements FromView, ShouldAutoSize
{
    use Exportable;

    private $request;

    function __construct($request) {
      $this->request = $request;
    }

    public function view(): View
    {
        if (!$this->request->dateStartPenerimaanPelanggan ?? null) {
            $dateStart = date('Y-m-01');  
        }else {
            $dateStart = $this->request->dateStartPenerimaanPelanggan;  
        }
        if (!$this->request->dateEndPenerimaanPelanggan ?? null) {
            $dateEnd = date('Y-m-t'); 
        }else {
            $dateEnd = $this->request->dateEndPenerimaanPelanggan;  
        }

        $dateStart = $dateStart." 00:00:00";
        $dateEnd = $dateEnd." 23:59:59";

        $pembayarans = Pembayaran::whereBetween('created_at', [$dateStart, $dateEnd])->with(['linkInvoice'])->orderBy('tanggal', 'ASC')->get();
        $first_pembayaran = Pembayaran::orderBy('created_at', 'ASC')->first();
        $total_pembayaran = Pembayaran::whereBetween('created_at', [$dateStart, $dateEnd])->select(\DB::raw('SUM(jumlah_pembayaran) as total_pembayaran'))->get()->sum('total_pembayaran');

        if($first_pembayaran ?? null){
          $first_pembayaran_date = $first_pembayaran->created_at->format('Y-m-d H:i:s');
          $date_before_dateStart = date('Y-m-d',strtotime($dateStart . "-1 days"))." 23:59:59";
          $previous_pembayarans_count = Pembayaran::whereBetween('created_at', [$first_pembayaran_date, $date_before_dateStart])->count();
        }else{
          $previous_pembayarans_count = 0;
        }

        return view('excel.penerimaan_pelanggan',[
          'dateStart' => $dateStart,
          'dateEnd' => $dateEnd,
          'pembayarans' => $pembayarans,
          'previous_pembayarans_count' => $previous_pembayarans_count,
          'total_pembayaran' => $total_pembayaran
        ]);
    }
}

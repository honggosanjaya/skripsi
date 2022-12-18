<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Retur;

class ReportPenjualanBersihExport implements FromView, ShouldAutoSize
{
    use Exportable;

    private $request;

    function __construct($request) {
      $this->request = $request;
    }

    public function view(): View
    {
        if (!$this->request->dateStartPenjualanBersih ?? null) {
            $dateStart = date('Y-m-01');  
        }else {
            $dateStart = $this->request->dateStartPenjualanBersih;  
        }
        if (!$this->request->dateEndPenjualanBersih ?? null) {
            $dateEnd = date('Y-m-t'); 
        }else {
            $dateEnd = $this->request->dateEndPenjualanBersih;  
        }
        $dateStart = $dateStart." 00:00:00";
        $dateEnd = $dateEnd." 23:59:59";

        $groupfakturs = [];
        $fakturs = [];
        $data = Invoice::whereBetween('created_at', [$dateStart, $dateEnd])->whereHas('linkOrder', function($q) use($dateStart, $dateEnd) {
          $q->whereHas('linkOrderTrack', function($q) use($dateStart, $dateEnd){
            $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$dateStart, $dateEnd]);
          });
        });

        $id_invoices = $data->pluck('id');

        $total_faktur = $data->select(\DB::raw('SUM(harga_total) as total'))
                        ->get()->sum('total');

        $total_retur = Retur::where('status_enum','1')->where('tipe_retur',1)->whereHas('linkInvoice', function($q) use($dateStart, $dateEnd) {
          $q->whereBetween('created_at', [$dateStart, $dateEnd])->whereHas('linkOrder', function($q) use($dateStart, $dateEnd) {
            $q->whereHas('linkOrderTrack', function($q) use($dateStart, $dateEnd){
              $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$dateStart, $dateEnd]);
            });
          });
        })->select(\DB::raw('SUM(kuantitas*harga_satuan) as total_retur'))->get()->sum('total_retur');
        
        foreach($id_invoices as $dt){
          $returs = Invoice::find($dt)->linkRetur ?? null;
          $totalRetur = 0;
          if ($returs != null) {
            foreach ($returs as $retur) {
              if ($retur->status_enum == '1' && $retur->tipe_retur == 1) {
                $totalRetur += $retur->kuantitas * $retur->harga_satuan;
              }
            }
          }
          array_push($fakturs, [
            'id_customer' => Invoice::find($dt)->linkOrder->linkCustomer->id,
            'nama_customer' => Invoice::find($dt)->linkOrder->linkCustomer->nama,
            'nama_kontak' => Invoice::find($dt)->linkOrder->linkCustomer->linkDistrict->kode_wilayah . '-' . Invoice::find($dt)->linkOrder->linkCustomer->linkDistrict->nama,
            'nilai_penjualan' => Invoice::find($dt)->harga_total,
            'jumlah_retur' => $totalRetur,
          ]);
        }

        foreach ($fakturs as $dt) {
          $key = $dt['id_customer'];
          if (!array_key_exists($key, $groupfakturs)) {
            $groupfakturs[$key] = array(
              'id_customer' => $dt['id_customer'],
              'nama_customer' => $dt['nama_customer'],
              'nama_kontak' => $dt['nama_kontak'],
              'nilai_penjualan' => $dt['nilai_penjualan'],
              'jumlah_retur' => $dt['jumlah_retur'],
            );
          } else {
            $groupfakturs[$key]['nilai_penjualan'] = $groupfakturs[$key]['nilai_penjualan'] + $dt['nilai_penjualan'];
            $groupfakturs[$key]['jumlah_retur'] = $groupfakturs[$key]['jumlah_retur'] + $dt['jumlah_retur'];
          }
        }

        // dd($groupfakturs);
        return view('excel.rekap_penjualan_bersih',[
          'dateStart' => $dateStart,
          'dateEnd' => $dateEnd,
          'fakturs' => $groupfakturs,
          'total' => [
            'total_faktur' => $total_faktur,
            'total_retur' => $total_retur
          ]
        ]);
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\Kas;
use App\Models\CashAccount;

class ReportLabaRugi implements FromView, ShouldAutoSize
{
  use Exportable;

  private $request;

  function __construct($request) {
    $this->request = $request;
  }

  public function view(): View
  {
      if (!$this->request->dateStartLabaRugi ?? null) {
          $dateStart = date('Y-m-01');  
      }else {
          $dateStart = $this->request->dateStartLabaRugi;  
      }
      if (!$this->request->dateEndLabaRugi ?? null) {
          $dateEnd = date('Y-m-t'); 
      }else {
          $dateEnd = $this->request->dateEndLabaRugi;  
      }

      // $dateStart = $dateStart." 00:00:00";
      // $dateEnd = $dateEnd." 23:59:59";

      // $data = [];

      // $cash_account = CashAccount::whereHas('linkKas', function($q) use($dateStart, $dateEnd) {
      //   $q->where('debit_kredit', '-1')->whereBetween('tanggal', [$dateStart, $dateEnd]);
      // })->with(['linkKas'])->orderBy('account_parent')->get();

      // foreach($cash_account as $ca){
      //   $pengeluaran = 0;
      //   foreach($ca->linkKas as $kas){
      //     $pengeluaran += $kas->uang;
      //   }

      //   array_push($data, [
      //     'account' => $ca->account,
      //     'account_parent' => $ca->account_parent,
      //     'nama_account' => $ca->nama,
      //     'nama_account_parent' => $ca->linkCashAccount->nama,
      //     'pengeluaran' => $pengeluaran
      //   ]);
      // }

      // function _group_by($array, $key) {
      //   $return = array();
      //   foreach($array as $val) {
      //       $return[$val[$key]][] = $val;
      //   }
      //   return $return;
      // }

      // $grouped_data = _group_by($data, 'account_parent');
      // dd($grouped_data);

      $all_kas = Kas::whereBetween('tanggal', [$dateStart, $dateEnd])->with(['linkCashAccount'])
                  ->orderBy('id_cash_account')->orderBy('tanggal')->get()->groupBy('id_cash_account');
      // dd($all_kas->toArray());
      $total_kredit_perkas = Kas::where('debit_kredit', '-1')
                      ->whereBetween('tanggal', [$dateStart, $dateEnd])
                      ->select('id_cash_account', \DB::raw('SUM(uang) as total_kredit_perkas'))
                      ->orderBy('id_cash_account')->groupBy('id_cash_account')
                      ->get()->groupBy('id_cash_account')->toArray();
      // dd($total_kredit_perkas);
      $total_debit_perkas = Kas::where('debit_kredit', '1')
                      ->whereBetween('tanggal', [$dateStart, $dateEnd])
                      ->select('id_cash_account', \DB::raw('SUM(uang) as total_debit_perkas'))
                      ->orderBy('id_cash_account')->groupBy('id_cash_account')
                      ->get()->groupBy('id_cash_account')->toArray();
      // dd($total_debit_perkas);
      $total_kredit_allkas = Kas::where('debit_kredit', '-1')
                      ->whereBetween('tanggal', [$dateStart, $dateEnd])
                      ->select(\DB::raw('SUM(uang) as total_kas'))->get()->sum('total_kas');
      $total_debit_allkas = Kas::where('debit_kredit', '1')
                      ->whereBetween('tanggal', [$dateStart, $dateEnd])
                      ->select(\DB::raw('SUM(uang) as total_kas'))->get()->sum('total_kas');

      return view('excel.laba_rugi',[
        'dateStart' => $dateStart,
        'dateEnd' => $dateEnd,
        'all_kas' => $all_kas,
        'total' => [
          'total_kredit_perkas' => $total_kredit_perkas,
          'total_debit_perkas' => $total_debit_perkas,
          'total_allkas' => $total_debit_allkas - $total_kredit_allkas
        ]
      ]);
  }
}

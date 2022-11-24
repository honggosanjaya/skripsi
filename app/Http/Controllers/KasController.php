<?php

namespace App\Http\Controllers;

use App\Models\CashAccount;
use App\Models\Kas;
use App\Models\Staff;
use Illuminate\Http\Request;
use PDF;

class KasController extends Controller
{

  public function bukuKas(Request $request, CashAccount $cashaccount){
    $counter = $request->session()->increment('counterAdminVisitKas');
    if($counter > 1){
      $allKas = Kas::all();

      foreach($allKas as $kas){
        if($kas->status == '-1'){
          Kas::find($kas->id)->update([
            'status_pengajuan' => '1',
            'status' => null
          ]);
        }elseif($kas->status == '1'){
          Kas::find($kas->id)->update([
            'status_pengajuan' => '-1',
            'status' => null
          ]);
        }
      }
    }

    $saldoKas = [];
    $bukuKas = Kas::where('kas', $cashaccount->id)
              ->where(function ($query) {
                $query->where('status_pengajuan','0')->orWhere('status_pengajuan','-1')->orWhereNull('status_pengajuan');                  
              })
              ->orderBy('tanggal','ASC')->orderBy('created_at','ASC')->get();

    $saldoDebitKas = Kas::where('kas', $cashaccount->id)
                      ->where('debit_kredit', '1')
                      ->where(function ($query) {
                        $query->where('status','1')->orWhereNull('status');                   
                      })
                      ->where(function ($query) {
                        $query->where('status_pengajuan','0')->orWhere('status_pengajuan','-1')->orWhereNull('status_pengajuan');                  
                      })
                      ->select(\DB::raw('SUM(uang) as saldo_debit'))
                      ->get()->sum('saldo_debit');

    $saldoKreditKas = Kas::where('kas', $cashaccount->id)
                      ->where('debit_kredit', '-1')
                      ->where(function ($query) {
                        $query->where('status','1')->orWhereNull('status');                  
                      })
                      ->where(function ($query) {
                        $query->where('status_pengajuan','0')->orWhere('status_pengajuan','-1')->orWhereNull('status_pengajuan');                  
                      })
                      ->select(\DB::raw('SUM(uang) as saldo_debit'))
                      ->get()->sum('saldo_debit');

    $saldoAkhirKas = $saldoDebitKas - $saldoKreditKas;                 
    $totalKas = 0;

    foreach ($bukuKas as $kas) {
      if($kas->status != '-1'){
        if($kas->debit_kredit == '-1'){
          $totalKas = $totalKas - $kas->uang;
        }else{
          $totalKas = $totalKas + $kas->uang;
        }
      }

      array_push($saldoKas, [
        'original' => $kas, 
        'totalKas' => $totalKas
      ]);
    }

    // dd($saldoKas);

    return view('administrasi.kas.bukukas', [
      'listsofkas' => $saldoKas,
      'title' => $cashaccount->nama,
      'idCashaccount' => $cashaccount->id
    ]);
  }

  public function index(){
    $bukuKas = CashAccount::where('account', '<=', 100)
                ->where(function ($query) {
                  $query->whereNull('default')->orWhereIn('default', ['1', '2']);                  
                })->get();

    return view('administrasi.kas.index', [
      'bukuKas' => $bukuKas
    ]);
  }

  public function createKas (CashAccount $cashaccount){
    $staffs = Staff::where('status_enum','1')->whereIn('role', [3, 4])->get();
    $namaCashAccount = CashAccount::find($cashaccount->id)->nama;
    $debitkredits = [
      1 => 'debit',
      -1 => 'kredit',
    ];

    $cashaccounts = CashAccount::all();
    $temp = array();
    
    for($i=0; $i<count($cashaccounts); $i++){
      $get1 = '';
      $get2 = '';
      $value = 0;
      
      if($cashaccounts[$i]->account_parent == null){
        $get1 = $cashaccounts[$i]->nama;
        $value = $cashaccounts[$i]->id;
        $default = $cashaccounts[$i]->default;
        $account = $cashaccounts[$i]->account;
        array_push($temp, [$get1, $value, $default, $account]);
      }

      else if($cashaccounts[$i]->account_parent != null){
        for($j=count($cashaccounts)-1; $j>=0; $j--){
          if($cashaccounts[$i]->account_parent == $cashaccounts[$j]->account){
            $get2 = $temp[$j][0] . " - " .$cashaccounts[$i]->nama;
            $value = $cashaccounts[$i]->id;
            $default = $cashaccounts[$i]->default;
            $account = $cashaccounts[$i]->account;
            array_push($temp, [$get2, $value, $default, $account]);
          }
        }
      }
    }
    usort($temp, function($a, $b) {
        return $a[0] <=> $b[0];
    });

    // dd($temp);

    return view('administrasi.kas.tambahkas', [
      "debitkredits" => $debitkredits,
      "staffs" => $staffs,
      "cash_accounts" => $temp,
      'idCashaccount' => $cashaccount->id,
      'title' => 'Tambah Kas - '. $namaCashAccount
    ]);
  }

  public function storeKas(Request $request){
    $rules = [
      'tanggal' => ['required'],
      'no_bukti' => ['nullable', 'string', 'max:255', 'unique:kas'],
      'id_cash_account' => ['required'],
      'debit_kredit' => ['required'],
      'uang' => ['required', 'numeric'],
      'kontak' => ['nullable', 'string', 'max:255'],
      'keterangan_1' => ['nullable'],
      'keterangan_2' => ['nullable'],
      'kas' => ['required'],
    ];

    $validatedData = $request->validate($rules);
    $validatedData['id_staff'] = auth()->user()->id_users;
    $validatedData['created_at'] = now();

    $cashaccount = CashAccount::find($request->id_cash_account);

    if($cashaccount->account <= 100){    
      Kas::insert($validatedData);

      if($request->debit_kredit == '1'){
        $debitOrKredit = '-1';
      } else if($request->debit_kredit == '-1'){
        $debitOrKredit = '1';
      }

      Kas::insert([
        'id_staff' => auth()->user()->id_users,
        'tanggal' => $request->tanggal,
        'no_bukti' => $request->no_bukti,
        'id_cash_account' => $request->kas,
        'debit_kredit' =>  $debitOrKredit,
        'uang' => $request->uang,
        'kontak' => $request->kontak,
        'keterangan_1' => $request->keterangan_1,
        'keterangan_2' => $request->keterangan_2,
        'kas' => $request->id_cash_account,
        'created_at' => now()
      ]);

      return redirect('/administrasi/kas/'. $request->kas)->with('successMessage','Berhasil Melakukan Pindah Saldo Kas'); 
    }else{
      Kas::insert($validatedData);
      return redirect('/administrasi/kas/'. $request->kas)->with('successMessage','Berhasil Menambahkan Kas'); 
    }   
  }

  public function pindahSaldoAPI(CashAccount $cashaccount){
    $account = CashAccount::find($cashaccount->id)->account;
    $nama = Staff::where('id', auth()->user()->id_users)->first()->nama;
    
    if($account <= 100){
      return response()->json([
        'data' => [
          'nama' => $nama,
        ],
        'status' => 'success',
      ]);
    }else{
      return response()->json([
        'data' => [
          'nama' => null,
        ],
        'status' => 'success',
      ]);
    }
  }

  public function pengajuanPenghapusanKas(Kas $kas){
    session(['counterAdminVisitKas' => 0]);
    $selectedKas = $kas->kas;
    Kas::find($kas->id)->update([
      'status_pengajuan' => '0',
      'updated_at' => now()
    ]);
 
    return redirect('/administrasi/kas/'.$selectedKas)->with('successMessage', 'Berhasil mengajukan penghapusan kas');
  }

  public function perubahanKasSpv(){
    $pengajuansKas = Kas::where('status_pengajuan', '0')->where('status', null)->get();
    
    return view('supervisor.kas.pengajuanPerubahan', [
      "pengajuansKas" => $pengajuansKas,
    ]);
  }

  public function setujuPerubahanKasSpv(Kas $kas){
    Kas::find($kas->id)->update([
      // 'status_pengajuan' => '1',
      'status' => '-1',
      'updated_at' => now()
    ]);

    return redirect('/supervisor/perubahankas')->with('successMessage', 'Berhasil menyetujui penghapusan kas');
  }

  public function tolakPerubahanKasSpv(Kas $kas){
    Kas::find($kas->id)->update([
      // 'status_pengajuan' => '-1',
      'status' => '1',
      'updated_at' => now()
    ]);

    return redirect('/supervisor/perubahankas')->with('successMessage', 'Berhasil menolak penghapusan kas');
  }

  public function cetakKas(CashAccount $cashaccount){
    $input=[
      'dateStart'=>date('Y-m-01'),
      'dateEnd'=>date('Y-m-t')
    ];
 
    $namaCashAccount = CashAccount::find($cashaccount->id)->nama;
    $cashaccounts = CashAccount::all();
    $temp = array();
    
    for($i=0; $i<count($cashaccounts); $i++){
      $get1 = '';
      $get2 = '';
      $value = 0;
      
      if($cashaccounts[$i]->account_parent == null){
        $get1 = $cashaccounts[$i]->nama;
        $value = $cashaccounts[$i]->id;
        $default = $cashaccounts[$i]->default;
        $account = $cashaccounts[$i]->account;
        array_push($temp, [$get1, $value, $default, $account]);
      }
  
      else if($cashaccounts[$i]->account_parent != null){
        for($j=count($cashaccounts)-1; $j>=0; $j--){
          if($cashaccounts[$i]->account_parent == $cashaccounts[$j]->account){
            $get2 = $temp[$j][0] . " - " .$cashaccounts[$i]->nama;
            $value = $cashaccounts[$i]->id;
            $default = $cashaccounts[$i]->default;
            $account = $cashaccounts[$i]->account;
            array_push($temp, [$get2, $value, $default, $account]);
          }
        }
      }
    }
    usort($temp, function($a, $b) {
        return $a[0] <=> $b[0];
    });  

    return view('administrasi.kas.cetakKas', [
      'input' => $input,
      'cashaccounts' => $temp,
      'cashaccount' => $cashaccount,
      'title' => 'Cetak Kas - '. $namaCashAccount
    ]);
  }

  public function cetakKasPDF(Request $request, CashAccount $cashaccount){
    $namaCashAccount = CashAccount::find($cashaccount->id)->nama;
    $completeKas = [];

    if($request->id_akun == null){
      $kas = Kas::where('kas', $cashaccount->id)
              ->where(function ($query) {
                $query->where('status_pengajuan','0')->orWhere('status_pengajuan','-1')->orWhereNull('status_pengajuan');                  
              })
              ->where(function ($query) {
                $query->where('status','1')->orWhereNull('status');                  
              })
              ->whereBetween('tanggal',[$request->dateStart, $request->dateEnd])
              ->orderBy('tanggal','ASC')->orderBy('created_at','ASC')
              ->get();
      
              
      $dateOfFirstKas = Kas::where('kas', $cashaccount->id)
                        ->where(function ($query) {
                          $query->where('status_pengajuan','0')->orWhere('status_pengajuan','-1')->orWhereNull('status_pengajuan');                  
                        })
                        ->where(function ($query) {
                          $query->where('status','1')->orWhereNull('status');                  
                        })
                        ->orderBy('tanggal','ASC')->orderBy('created_at','ASC')->select('tanggal')->first();
      
      if($dateOfFirstKas > $request->dateStart){
        $oneDateBeforeStart = date('Y-m-d', strtotime($request->dateStart .' -1 day'));
        $saldoDebitKas = Kas::where('kas', $cashaccount->id)
                ->where('debit_kredit', '1')
                ->where(function ($query) {
                  $query->where('status_pengajuan','0')->orWhere('status_pengajuan','-1')->orWhereNull('status_pengajuan');                  
                })
                ->where(function ($query) {
                  $query->where('status','1')->orWhereNull('status');                  
                })
                ->whereBetween('tanggal',[$dateOfFirstKas, $oneDateBeforeStart])
                ->select(\DB::raw('SUM(uang) as saldo_debit'))
                ->get()->sum('saldo_debit');
    
        $saldoKreditKas = Kas::where('kas', $cashaccount->id)
                ->where('debit_kredit', '-1')
                ->where(function ($query) {
                  $query->where('status_pengajuan','0')->orWhere('status_pengajuan','-1')->orWhereNull('status_pengajuan');                  
                })
                ->where(function ($query) {
                  $query->where('status','1')->orWhereNull('status');                  
                })
                ->whereBetween('tanggal',[$dateOfFirstKas, $oneDateBeforeStart])
                ->select(\DB::raw('SUM(uang) as saldo_kredit'))
                ->get()->sum('saldo_kredit');
    
        $saldoAwalKas = $saldoDebitKas - $saldoKreditKas;   
      }else{
        $saldoAwalKas = 0;
      }
    }else{
      $kas = Kas::where('kas', $cashaccount->id)
      ->where('id_cash_account', $request->id_akun)
      ->where(function ($query) {
        $query->where('status_pengajuan','0')->orWhere('status_pengajuan','-1')->orWhereNull('status_pengajuan');                  
      })
      ->where(function ($query) {
        $query->where('status','1')->orWhereNull('status');                  
      })
      ->whereBetween('tanggal',[$request->dateStart, $request->dateEnd])
      ->orderBy('tanggal','ASC')->orderBy('created_at','ASC')
      ->get();

      $saldoAwalKas = 0;
    }
    
    $totalKas = $saldoAwalKas;

    foreach ($kas as $dt) {
      if($dt->status != '-1'){
        if($dt->debit_kredit == '-1'){
          $totalKas = $totalKas - $dt->uang;
        }else{
          $totalKas = $totalKas + $dt->uang;
        }
      }

      array_push($completeKas, [
        'original' => $dt, 
        'totalKas' => $totalKas
      ]);
    }

    $pdf = PDF::loadview('administrasi.kas.cetakKasPDF',[
        'manykas' => $completeKas,
        'title' => 'Kas - '. $namaCashAccount     
    ]);

    $pdf->setPaper('A4', 'landscape');

    return $pdf->stream('kas-'.$namaCashAccount.'.pdf'); 
  }
}

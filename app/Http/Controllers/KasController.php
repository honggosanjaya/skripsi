<?php

namespace App\Http\Controllers;

use App\Models\CashAccount;
use App\Models\Kas;
use App\Models\Staff;
use Illuminate\Http\Request;

class KasController extends Controller
{

  public function bukuKas(CashAccount $cashaccount){
    $saldoKas = [];
    $bukuKas = Kas::where('id_cash_account', $cashaccount->id)->where('status', null)->orWhere('status', '1')->orderBy('tanggal','DESC')->orderBy('created_at','DESC')->get();

    $totalKas = 0;
    foreach($bukuKas as $kas){
      if($kas->debit_kredit == '-1'){
        $totalKas = $totalKas - $kas->uang;
      }else if($kas->debit_kredit == '1'){
        $totalKas = $totalKas + $kas->uang;
      }
      array_push($saldoKas, [
        'original' => $kas, 
        'totalKas' => $totalKas
      ]);
    }

    return view('administrasi.kas.bukukas', [
      'listsofkas' => $saldoKas,
      'title' => $cashaccount->nama,
    ]);
  }

  public function index(){
    $bukuKas = CashAccount::where('account', '<', 100)->get();

    return view('administrasi.kas.index', [
      'bukuKas' => $bukuKas
    ]);
  }

  public function createKas (){
    $staffs = Staff::where('status_enum','1')->whereIn('role', [3, 4])->get();
    $cash_accounts = CashAccount::all();

    $debitkredits = [
      1 => 'debit',
      -1 => 'kredit',
    ];

    return view('administrasi.kas.tambahkas', [
      "debitkredits" => $debitkredits,
      "staffs" => $staffs,
      "cash_accounts" => $cash_accounts
    ]);
  }

  public function storeKas(Request $request){
    $rules = [
      'id_staff' => ['required'],
      'tanggal' => ['required'],
      'no_bukti' => ['nullable', 'string', 'max:255', 'unique:kas'],
      'id_cash_account' => ['required'],
      'debit_kredit' => ['required'],
      'uang' => ['required', 'numeric'],
      'kontak' => ['nullable', 'string', 'max:255'],
      'keterangan_1' => ['nullable'],
      'keterangan_2' => ['nullable'],
      'kas' => ['nullable'],
    ];

    $validatedData = $request->validate($rules);
    $validatedData['created_at'] = now();

    Kas::insert($validatedData);
    
    return redirect('/administrasi/kas')->with('pesanSukses','Berhasil Menambahkan Kas'); 
  }
}

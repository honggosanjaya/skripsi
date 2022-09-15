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
    $bukuKas = Kas::where('kas', $cashaccount->id)->where('status', null)->orWhere('status', '1')->orderBy('tanggal','DESC')->orderBy('created_at','DESC')->get();

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
      'idCashaccount' => $cashaccount->id
    ]);
  }

  public function index(){
    $bukuKas = CashAccount::where('account', '<=', 100)->get();

    return view('administrasi.kas.index', [
      'bukuKas' => $bukuKas
    ]);
  }

  public function createKas (CashAccount $cashaccount){
    $staffs = Staff::where('status_enum','1')->whereIn('role', [3, 4])->get();
    $cash_accounts = CashAccount::all();

    $debitkredits = [
      1 => 'debit',
      -1 => 'kredit',
    ];

    $namaCashAccount = CashAccount::find($cashaccount->id)->nama;

    return view('administrasi.kas.tambahkas', [
      "debitkredits" => $debitkredits,
      "staffs" => $staffs,
      "cash_accounts" => $cash_accounts,
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

      return redirect('/administrasi/kas/'. $request->kas)->with('pesanSukses','Berhasil Melakukan Pindah Saldo Kas'); 
    }else{
      Kas::insert($validatedData);
      return redirect('/administrasi/kas/'. $request->kas)->with('pesanSukses','Berhasil Menambahkan Kas'); 
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
}

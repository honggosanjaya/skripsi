<?php

namespace App\Http\Controllers;

use App\Models\CashAccount;
use App\Models\Kas;
use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class CashAccountController extends Controller
{
  public function cashAccountIndex(){
      $cashaccounts = CashAccount::orderBy('account', 'ASC')->paginate(10);
      return view('supervisor.cashaccount.index',[
          'cashaccounts' => $cashaccounts            
      ]);
  }

  public function cashAccountSearch(){
    $cashaccounts =  CashAccount::where(strtolower('nama'),'like','%'.request('cari').'%')->paginate(10);
    
    return view('supervisor.cashaccount.index',[
      'cashaccounts' => $cashaccounts
    ]);
  }

  public function cashAccountCreate(){
    $defaults = [
      1 => 'pengadaan',
      2 => 'penjualan',
      3 => 'parent'
    ];

    return view('supervisor.cashaccount.addcashaccount',[
      'defaults' => $defaults
    ]);
  }

  public function cashAccountStore(Request $request){
    $rules = ([
      'nama' => 'required|max:255',
      'keterangan' => 'nullable',
      'account' => 'required|numeric|unique:cash_accounts',
      'default' => 'nullable'            
    ]);

    $validatedData = $request->validate($rules);
    $validatedData['created_at'] = now();

    $idCashaccount = CashAccount::insertGetId($validatedData);
    $cashaccount = CashAccount::find($idCashaccount);

    if ($cashaccount->default == '1') {
      if (CashAccount::where('default', '1')->count()>1) {
        $another = CashAccount::where('default', '1')->where('id','!=',$idCashaccount)->first();
        $another->default = null;
        $another->save();
      }
    } else if ($cashaccount->default == '2'){
      if (CashAccount::where('default', '2')->count()>1) {
        $another = CashAccount::where('default', '2')->where('id','!=',$idCashaccount)->first();
        $another->default = null;
        $another->save();
      }
    }
      
    return redirect('/supervisor/cashaccount')->with('addCashAccountSuccess','Tambah Cash Account Berhasil');
  }

  public function cashAccountEdit(CashAccount $cashaccount){
    $defaults = [
      1 => 'pengadaan',
      2 => 'penjualan',
      3 => 'parent'
    ];

    return view('supervisor.cashaccount.editcashaccount',[
      'cashaccount' => $cashaccount,
      'defaults' => $defaults
    ]);
  }

  public function cashAccountUpdate(Request $request, CashAccount $cashaccount){
    $validation = ([
      'nama' => 'required|max:255',
      'keterangan' => 'nullable',
      'default' => 'nullable'    
    ]);

    if($request->account != CashAccount::where('id', $cashaccount->id)->first()->account){
      $validation['account'] = 'required|numeric|unique:cash_accounts';
    }

    $validatedData = $request->validate($validation);
    $validatedData['updated_at'] = now();

    CashAccount::where('id', $cashaccount->id)->update($validatedData);

    if (CashAccount::find($cashaccount->id)->default == '1') {
      if (CashAccount::where('default', '1')->count()>1) {
        $another = CashAccount::where('default', '1')->where('id','!=',$cashaccount->id)->first();
        $another->default = null;
        $another->save();
      }
    } else if (CashAccount::find($cashaccount->id)->default == '2'){
      if (CashAccount::where('default', '2')->count()>1) {
        $another = CashAccount::where('default', '2')->where('id','!=',$cashaccount->id)->first();
        $another->default = null;
        $another->save();
      }
    }

    return redirect('/supervisor/cashaccount')->with('updateCashAccountSuccess','Update Cash Account Berhasil');        
  }


  public function cashAccountOptionAPI(){
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

    return response()->json([
      'cashaccount' => $temp
    ]);
  }

  public function simpanReimbursementAPI(Request $request){
    $rules = [
      'id_staff_pengaju' => ['required'],
      'jumlah_uang' => ['required','numeric'],
      'keterangan_pengajuan' => ['required'],
      'id_cash_account' => ['required'],
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()){
      return response()->json([
        'validate_err' => $validator->errors()
      ]);
    }

    $reimbursement = Reimbursement::insertGetId([
      'id_staff_pengaju' => $request->id_staff_pengaju,
      'jumlah_uang' => $request->jumlah_uang,
      'keterangan_pengajuan' => $request->keterangan_pengajuan,
      'id_cash_account' => $request->id_cash_account,
      'status_enum' => '0',
      'created_at'=>now()
    ]);

    return response()->json([
      'status' => 'success',
      'data' => Reimbursement::find($reimbursement),
    ]);
  }


  public function simpanReimbursementFotoAPI(Request $request, $id){
    $fileFoto = $request->foto;
    if($fileFoto == "null"){
      $fileFoto = null;
    }

    $reimbursement = Reimbursement::find($id);

    if($fileFoto !== null){
      $validator = Validator::make($request->all(), [
        'foto' => 'image|nullable',
      ]);
  
      if ($validator->fails()) {
        return response()->json([
          'message' => 'validation fails',
          'errors' => $validator->errors()
        ]);
      }
    }

    if ($fileFoto !== null) {
      $nama_pengaju = str_replace(" ", "-", $reimbursement->linkStaffPengaju->nama);
      $file_name = 'RBS-' . $nama_pengaju . '-' .date_format(now(),"YmdHis"). '.' . $request->foto->extension();
      Image::make($request->file('foto'))->resize(350, null, function ($constraint) {
        $constraint->aspectRatio();
      })->save(public_path('storage/reimbursement/') . $file_name);
      $reimbursement->foto = $file_name;
    }
        
    $reimbursement->update();

    return response()->json([
      'data' => $reimbursement,
      'status' => 'success',
    ]);
  }

  public function getHistoryReimbursementAPI($id){
    $reimbursements = Reimbursement::where('id_staff_pengaju',$id)
                      ->orderBy('id','DESC')
                      ->with(['linkStaffPengonfirmasi', 'linkCashAccount'])
                      ->get();

    return response()->json([
      'data' => $reimbursements,
      'status' => 'success',
    ]);
  }

  public function adminReimbursementIndex(){
    $reimbursements = Reimbursement::orderBy("status_enum", "ASC")->get();
    return view('administrasi.reimbursement.index', [
      'reimbursements' => $reimbursements,
    ]);
  }


  public function adminReimbursementPengajuan(){
    $reimbursements = Reimbursement::where('status_enum', '0')->get();
    return view('administrasi.reimbursement.pengajuanReimbursement', [
      'reimbursements' => $reimbursements,
      'type' => 'pengajuan'
    ]);
  }

  public function adminReimbursementPembayaran(){
    $reimbursements = Reimbursement::where('status_enum', '1')->get();
    return view('administrasi.reimbursement.pengajuanReimbursement', [
      'reimbursements' => $reimbursements,
      'type' => 'pembayaran'
    ]);
  }
  
  public function adminReimbursementPengajuanDetail(Reimbursement $reimbursement){
    $reimbursement = Reimbursement::find($reimbursement->id);
    $listskas = CashAccount::where('account', '<=', '100')
                  ->where(function ($query) {
                    $query->whereNull('default')->orWhereIn('default', ['1', '2']);                  
                  })->get();


    return view('administrasi.reimbursement.detailReimbursement', [
      'reimbursement' => $reimbursement,
      'listskas' => $listskas
    ]);
  }

  public function setujuReimbursement(Request $request, Reimbursement $reimbursement){
    $request->validate([
      'keterangan_konfirmasi' => 'required'            
    ]);

    Reimbursement::find($reimbursement->id)->update([
      'keterangan_konfirmasi' => $request->keterangan_konfirmasi,
      'id_staff_pengonfirmasi' => auth()->user()->id_users,
      'status_enum' => '1'
    ]);
    return redirect('/administrasi/reimbursement') -> with('pesanSukses', 'Berhasil menyetujui pengajuan' );
  }

  public function tolakReimbursement(Request $request, Reimbursement $reimbursement){
    $request->validate([
      'keterangan_konfirmasi' => 'required'            
    ]);

    Reimbursement::find($reimbursement->id)->update([
      'keterangan_konfirmasi' => $request->keterangan_konfirmasi,
      'id_staff_pengonfirmasi' => auth()->user()->id_users,
      'status_enum' => '-1'
    ]);
    return redirect('/administrasi/reimbursement') -> with('pesanSukses', 'Berhasil menolak pengajuan' );
  }

  public function bayarReimbursement(Request $request, Reimbursement $reimbursement){
    $request->validate([
      'idCashAccount' => 'required',
      'kas' => 'required'            
    ]);

    Kas::create([
      'id_staff' => auth()->user()->id_users,
      'id_cash_account' => $request->idCashAccount,
      'tanggal' => date("Y-m-d"),
      'debit_kredit' => '-1',
      'keterangan_1' => 'reimbursement',
      'keterangan_2' => $reimbursement->keterangan_pengajuan,
      'uang' => $reimbursement->jumlah_uang,
      'kas' => $request->kas,
      'created_at' => now()
    ]); 

    Reimbursement::find($reimbursement->id)->update([
      'status_enum' => '2'
    ]);
    return redirect('/administrasi/reimbursement') -> with('pesanSukses', 'Berhasil membayar pengajuan' );
  }
}

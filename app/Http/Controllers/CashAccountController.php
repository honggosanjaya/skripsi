<?php

namespace App\Http\Controllers;

use App\Models\CashAccount;
use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CashAccountController extends Controller
{
  public function cashAccountIndex(){
      $cashaccounts = CashAccount::paginate(10);
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
      return view('supervisor.cashaccount.addcashaccount');
  }

  public function cashAccountStore(Request $request){
      $request->validate([
          'nama_cashaccount' => 'required|max:255',
          'keterangan' => 'required'            
      ]);

      CashAccount::create([
          'nama' => $request->nama_cashaccount,
          'keterangan' => $request->keterangan
      ]); 
      
      return redirect('/supervisor/cashaccount')->with('addCashAccountSuccess','Tambah Cash Account Berhasil');
  }

  public function cashAccountEdit(CashAccount $cashaccount){
      return view('supervisor.cashaccount.editcashaccount',[
          'cashaccount' => $cashaccount
      ]);
  }

  public function cashAccountUpdate(Request $request, CashAccount $cashaccount){
      $rules = $request->validate([
          'nama' => 'required|max:255',
          'keterangan' => 'required'                   
      ]);

      CashAccount::Where('id', $cashaccount->id)
          ->update($rules);

      return redirect('/supervisor/cashaccount')->with('updateCashAccountSuccess','Update Cash Account Berhasil');        
  }


  public function cashAccountOptionAPI(){
    return response()->json([
      'cashaccount' => CashAccount::get()
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
      'status' => 27,
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
        'foto' => 'image|nullable|max:1024',
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
      $request->foto->move(public_path('storage/reimbursement'), $file_name);
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
                      ->with(['linkStatus', 'linkStaffPengonfirmasi', 'linkCashAccount'])
                      ->get();

    return response()->json([
      'data' => $reimbursements,
      'status' => 'success',
    ]);
  }

  public function adminReimbursementIndex(){
    $reimbursements = Reimbursement::orderBy("status", "ASC")->paginate(10);
    return view('administrasi.reimbursement.index', [
      'reimbursements' => $reimbursements,
    ]);
  }


  public function adminReimbursementPengajuan(){
    $reimbursements = Reimbursement::where('status', 27)->paginate(10);
    return view('administrasi.reimbursement.pengajuanReimbursement', [
      'reimbursements' => $reimbursements,
      'type' => 'pengajuan'
    ]);
  }

  public function adminReimbursementPembayaran(){
    $reimbursements = Reimbursement::where('status', 28)->paginate(10);
    return view('administrasi.reimbursement.pengajuanReimbursement', [
      'reimbursements' => $reimbursements,
      'type' => 'pembayaran'
    ]);
  }
  
  public function adminReimbursementPengajuanDetail(Reimbursement $reimbursement){
    $reimbursement = Reimbursement::find($reimbursement->id);
    return view('administrasi.reimbursement.detailReimbursement', [
      'reimbursement' => $reimbursement,
    ]);
  }

  public function setujuReimbursement(Reimbursement $reimbursement){
    Reimbursement::find($reimbursement->id)->update([
      'id_staff_pengonfirmasi' => auth()->user()->id_users,
      'status' => 28
    ]);
    return redirect('/administrasi/reimbursement') -> with('pesanSukses', 'Berhasil menyetujui pengajuan' );
  }

  public function tolakReimbursement(Reimbursement $reimbursement){
    Reimbursement::find($reimbursement->id)->update([
      'id_staff_pengonfirmasi' => auth()->user()->id_users,
      'status' => 30
    ]);
    return redirect('/administrasi/reimbursement') -> with('pesanSukses', 'Berhasil menolak pengajuan' );
  }

  public function bayarReimbursement(Reimbursement $reimbursement){
    Reimbursement::find($reimbursement->id)->update([
      'status' => 29
    ]);
    return redirect('/administrasi/reimbursement') -> with('pesanSukses', 'Berhasil membayar pengajuan' );
  }
}

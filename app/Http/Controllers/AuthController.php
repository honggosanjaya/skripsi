<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
  public function check(Request $request, User $user){
    $validatePassword = $request->validate([
      'passwordLama' => 'required'
    ]);
    $dataUser = '';
    if($user->tabel == 'staffs'){
      $dataUser = Staff::firstWhere('id',$user->id_users);
    }
    else{
      $dataUser = Customer::firstWhere('id',$user->id_users);
    }
    
    if(Hash::check($validatePassword['passwordLama'],$dataUser->password) && $user->tabel == 'staffs'){
      return redirect($user->linkStaff->linkStaffRole->nama.'/profil/ubahpasswordbaru/'.$user->id_users)
      ->with('passwordSuccess','Password ditemukan, silakan ganti password baru');            
    }
    else if(Hash::check($validatePassword['passwordLama'],$dataUser->password) && $user->tabel == 'customers'){
      return redirect('customer/profil/ubahpasswordbaru/'.$user->id_users)
      ->with('passwordSuccess','Password ditemukan, silakan ganti password baru');   
    }
    return back()->with('passwordError','Password tidak sama');
  }

  public function passwordBaru(User $user){
    if($user->tabel == 'staffs'){
      return view($user->linkStaff->linkStaffRole->nama.'/profil.ubahpasswordbaru');
    }
    else{
      return view('customer/profil.ubahpasswordbaru');
    }
  }

  public function gantiPassword(Request $request, User $user){
    $validateData = $request->validate([
      'password' => 'required',                      
    ]);
    
    if($validateData['password'] != $request->konfirmasiPasswordBaru){
      return back()->with('matchError','Password tidak sama');
    }

    $validateData['password'] = Hash::make($validateData['password']);
    
    if($user->tabel == 'staffs'){
      $user->password = $validateData['password'];
      $data = Staff::where('id','=',auth()->user()->id_users)->first();

      $user->save();
      Staff::where('id',$user->id_users)
      ->update($validateData);
      return redirect($user->linkStaff->linkStaffRole->nama.'/profil')
      ->with('passwordSuccess','Password berhasil diubah');
    }
    else{
      $user->password = $validateData['password'];
      $data = Customer::where('id','=',auth()->user()->id_users)->first();

      $user->save();
      Customer::where('id',$user->id_users)
      ->update($validateData);
      return redirect('customer/profil')
      ->with('passwordSuccess','Password berhasil diubah');
    }       
  }

  public function checkPasswordAPI(Request $request, Staff $staff){
    $validator = Validator::make($request->all(), [
      'old_password' => 'required|string|min:8',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'validation fails',
        'validate_err' => $validator->errors()
      ]);
    }

    if (Hash::check($request->old_password, $staff->password)) {
        return response()->json([
          'status' => 'success',
          'message' => 'Password lama sesuai',
        ], 200);
    } else {
      return response()->json([
        'status' => 'error',
        'message' => 'Password lama tidak sesuai',
      ], 400);
    }
  }

  public function changePasswordAPI(Request $request, Staff $staff){
    $validator = Validator::make($request->all(), [
      'new_password' => 'required|string|min:8',
      'confirm_newpassword' => 'required|same:new_password'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'validation fails',
        'validate_err' => $validator->errors()
      ]);
    }

    $user = User::where('tabel', 'staffs')->where('id_users', $staff->id);
    $user->update([
      'password' => Hash::make($request->new_password),
    ]);

    $staff->update([
      'password' => Hash::make($request->new_password),
    ]);
    
    return response()->json([
      'status' => 'success',
      'message' => 'Password berhasil diubah',
    ], 200);
  }
}

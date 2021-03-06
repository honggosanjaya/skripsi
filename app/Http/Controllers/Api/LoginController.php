<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'email' => ['required', 'string', 'email', 'max:255'],
        'password' => ['required', 'string', 'max:255']
      ]);

      User::with('linkStaff.linkStaffRole')->where('email',$request->email)->first()->linkStaff->linkStaffRole->nama;

      if ($validator->fails()){
        return response()->json([
          'validate_err' => $validator->errors()
        ]);
      }

      $user = User::where('email', $request->email)->first();
      if (!$user || !Hash::check($request->password, $user->password)){
        return response() -> json([
          'status' => 'error',
          'message' => 'email atau password anda salah'
        ], 401);
      }
      if ($user->linkStaff->status==9){
        return response() -> json([
          'status' => 'error',
          'message' => 'akun sudah tidak aktif'
        ], 401);
      }

      $detail_user = Staff::find($user->id_users);
      $data = (object) [
        "id_staff" => $detail_user->id,
        "nama" => $detail_user->nama,
        "email" => $detail_user->email,
        "telepon" => $detail_user->telepon,
        "foto_profil" => $detail_user->foto_profil,
        "role" => $detail_user->linkStaffRole->nama,
        "status" => $detail_user->linkStatus->nama
      ];

      if($detail_user->linkStaffRole->nama == 'salesman' || $detail_user->linkStaffRole->nama == 'shipper')
      {
        return response()->json([
          'data' => $data,
          'status' => 'success',
          'token' => $user->createToken('api-token')->plainTextToken,
        ],200);
      } 
      else{
        return response() -> json([
          'status' => 'error',
          'message' => 'Anda mengakses halaman login yang salah'
        ], 401);
      }
    }

    public function logoutApi(Request $request)
    {
      $request->user()->currentAccessToken()->delete();

      return response()->json([
        'status' => 'success',
        'message' => 'Berhasil logout'
      ]);
    }

    public function checkUser(Request $request){
      $user = $request->user();
      $detail_user = Staff::find($user->id_users);

      $data = (object) [
        "id_staff" => $detail_user->id,
        "nama" => $detail_user->nama,
        "email" => $detail_user->email,
        "telepon" => $detail_user->telepon,
        "foto_profil" => $detail_user->foto_profil,
        "role" => $detail_user->linkStaffRole->nama,
        "status" => $detail_user->linkStatus->nama
      ];

      if($detail_user->linkStaffRole->nama == 'salesman' || $detail_user->linkStaffRole->nama == 'shipper')
      {
        return response()->json([
          'status' => 'success',
          'data' => $data,
        ]);
      }
      else{
        return response()->json([
          'status' => 'error',
          'data' => 'Unauthorized User',
        ]);
      }
    }

    public function logoutUnauthorizedSPAApi(Request $request)
    {
      Auth::guard('web')->logout();
      $request->session()->invalidate();
      $request->session()->regenerateToken();

      return redirect('/spa/login');
    }
}

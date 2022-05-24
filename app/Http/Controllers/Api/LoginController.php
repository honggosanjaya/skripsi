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

      $role=User::with('linkStaff.linkStaffRole')->where('email',$request->email)->first()->linkStaff->linkStaffRole->nama;

    // $request->session()->put('role', $role);
    // $request->session()->put('id_staff', User::where('email',$request->email)->first()->id);
    // dd(session('id_staff'));

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

      // $request->session()->put('role', $detail_user->linkStaffRole->nama);

      if($detail_user->linkStaffRole->nama == 'salesman' || $detail_user->linkStaffRole->nama == 'shipper')
      {
        return response()->json([
          'data' => $detail_user,
          'role' => $detail_user->linkStaffRole->nama,
          'status_pegawai' => $detail_user->linkStatus->nama,
          'status' => 'success',
          'token' => $user->createToken('api-token')->plainTextToken,
        ],200);
      } else{
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

      if($detail_user->linkStaffRole->nama == "administrasi" || $detail_user->linkStaffRole->nama == "supervisor" || $detail_user->linkStaffRole->nama == "owner"){
        return response() -> json([
          'status' => 'error',
          'message' => 'Anda mengakses halaman login yang salah'
        ], 401);
      } else if($detail_user->linkStaffRole->nama == "salesman" || $detail_user->linkStaffRole->nama == "shipper"){
        return response()->json([
          'status' => 'success',
          'data' => $detail_user,
          'role' => $detail_user->linkStaffRole->nama,
          'status_pegawai' => $detail_user->linkStatus->nama,
        ]);
      }
    }
}

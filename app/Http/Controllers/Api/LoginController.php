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

      if ($validator->fails()){
        return response()->json([
          'validate_err' => $validator->errors()
        ]);
      }

      $user = User::where('email', $request->email)->first();
      if (!$user || !Hash::check($request->password, $user->password)){
        return response() -> json([
          'status' => 'fail',
          'message' => 'Unauthorised access'
        ], 401);
      }

      $detail_user = Staff::find($user->id_users);

      

      return response()->json([
        'data' => $detail_user,
        'role' => $detail_user->linkStaffRole->nama,
        'status_pegawai' => $detail_user->linkStatus->nama,
        'status' => 'success',
        'token' => $user->createToken('api-token')->plainTextToken,
      ],200);
    }

    public function logoutApi(Request $request)
    {
      $request->user()->currentAccessToken()->delete();

      return response()->json([
        'status' => 'success',
        'message' => 'Berhasil logout'
      ]);
    }
}

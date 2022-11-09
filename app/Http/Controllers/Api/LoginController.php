<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class LoginController extends Controller
{
  // public function __construct()
  // {
  //   $this->middleware(['auth', 'verified']);
  // }

    public function index(Request $request)
    {
      try {
        $validator = Validator::make($request->all(), [
          'email' => ['required', 'string', 'email', 'max:255'],
          'password' => ['required', 'string', 'max:255']
        ]);

        if(config('app.enabled_email_confirmation')==true){
          Auth::guard('web')->logout();
          $request->session()->invalidate();
          $request->session()->regenerateToken();
        }

        $user = User::where('email', $request->email)->first();

        if(config('app.enabled_email_confirmation')==false && $user!=null){
          User::with('linkStaff.linkStaffRole')->where('email',$request->email)->first()->linkStaff->linkStaffRole->nama;
        }

        if ($validator->fails()){
          return response()->json([
            'validate_err' => $validator->errors()
          ]);
        }

        if (!$user || !Hash::check($request->password, $user->password)){
          return response() -> json([
            'status' => 'error',
            'message' => 'email atau password anda salah'
          ], 401);
        }

        if(config('app.enabled_email_confirmation')==true){
          if($user != null){
            if($user->email_verified_at == null){
              return response() -> json([
                'status' => 'error',
                'message' => 'Anda Belum Mengonfirmasi Email'
              ], 401);
            }
          }
        }

        if ($user->linkStaff->status_enum=='-1'){
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
          "status_enum" => $detail_user->status_enum
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
      } catch (Exception $e) {
        $user = User::where('email', $request->email)->first();

        if($user != null){
          return response()->json([
            'status' => 'error',
            'message' => 'please remove local storage'
          ]);
        }
      }
    }

    public function logoutApi(Request $request)
    {
      try {
        if ($request->user()->currentAccessToken() ?? null)
        {
          $request->user()->currentAccessToken()->delete();

          return response()->json([
            'status' => 'success',
            'message' => 'Berhasil logout'
          ]);
        }else{
          throw new Exception("Current access token not found.");
        }
      
      } catch (Exception $e) {
        return response()->json([
          'status' => 'error',
          'message' => $e->getMessage()
        ]);
      }
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
        "status_enum" => $detail_user->status_enum
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

    public function logoutUserAPI(Request $request){
      if(auth()->user()->linkStaff->linkStaffRole->nama == 'salesman' || auth()->user()->linkStaff->linkStaffRole->nama == 'shipper'){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
      }
    }
}

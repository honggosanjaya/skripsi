<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function simpanCustomerApi(Request $request){
      $rules = [
        'nama' => ['required', 'string', 'max:255'],
        'id_jenis' => ['required'],
        'id_wilayah' => ['required'],
        'alamat_utama' => ['required', 'string', 'max:255'],
        'alamat_nomor' => ['required', 'string', 'max:255'],
        'keterangan_alamat' => ['required', 'string', 'max:255'],
        'telepon' => ['required', 'string', 'max:15'],
        'durasi_kunjungan' => ['required', 'integer'],
      ];


      $validator = Validator::make($request->all(), $rules);

      if($validator->fails()){
        return response()->json($validator->errors(),400);
      }
      
      $customer = Customer::create($request->all()+['id_staff' => 1, 'status' => 3]);
      // dd($request);
      return response()->json([
        'data' => $customer,
        'status' => 'success'
      ]);
    }


    public function simpanCustomerFotoApi(Request $request, $id){
      $rules = [
        'foto' => 'max:1024',
      ];

      $validator = Validator::make($request->all(), $rules);

      if($validator->fails()){
        return response()->json($validator->errors(),400);
      }

      $customer = Customer::find($id);

      if ($request->foto) {
        $file_name = time() . '.' . $request->foto->extension();
        $request->foto->move(public_path('storage/customer'), $file_name);
        // $path = "public/storage/customer/$file_name";
        $customer->foto = $file_name;
    }

    
    $customer->update();
    dd($request);

      return response()->json([
        'data' => $customer,
        'status' => 'success'
      ]);
    }



    public function ubahCustomerApi(Request $request){
      try{
        $validator = Validator::make($request->all(),[
          'nama' => ['required', 'string', 'max:255'],
          'id_jenis' => ['required'],
          'id_wilayah' => ['required'],
          'alamat_utama' => ['required', 'string', 'max:255'],
          'alamat_nomor' => ['required', 'string', 'max:255'],
          'keterangan_alamat' => ['required', 'string', 'max:255'],
          'telepon' => ['required', 'string', 'max:15'],
          'durasi_kunjungan' => ['required', 'integer'],
          'foto' => ['nullable', 'image', 'max:1024']
        ]);
        if($validator->fails()){
          $error = $validator->errors()->all()[0];
          return response()->json(['status'=>'fail','message'=>$error, 'data'=>[]], 422);
        }
        else{
          $customer = Customer::find($request->id);
          $customer->id_jenis = $request->id_jenis;
          $customer->id_wilayah = $request->id_wilayah;
          $customer->alamat_utama = $request->alamat_utama;
          $customer->alamat_nomor = $request->alamat_nomor;
          $customer->keterangan_alamat = $request->keterangan_alamat;
          $customer->telepon = $request->telepon;
          $customer->durasi_kunjungan = $request->durasi_kunjungan;
          if($request->foto && $request->foto->isValid()){
            $file_name = time().'.'.$request->foto->extenction();
            $request->foto->move(public_path('images'),$file_name);
            $path = 'public/images/$file_name';
            $customer->foto = $path;
          }
          $customer->update();
          return response()->json(['status'=>'success','message'=>'updated data', 'data'=>$customer]); 
        }
      } catch (\Exception $e){
        return response()->json(['status'=>'fail','message'=>$e->getMessage(), 'data'=>[]], 500);
      }
    }
}

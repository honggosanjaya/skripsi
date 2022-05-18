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
    // public function simpanCustomerFotoApi(Request $request){
      $validator = Validator::make($request->all(), [
        'foto' => 'nullable|image|mimes:jpg,bmp,png'
      ]);

      if ($validator->fails()) {
        return response()->json([
            'message' => 'validation fails',
            'errors' => $validator->errors()
        ]);
      }

      $customer = Customer::find($id);
      // if ($request->foto && $request->foto->isValid()) {
      if ($request->foto) {
        $file_name = time() . '.' . $request->foto->extension();
        $request->foto->move(public_path('storage/customer'), $file_name);
        $customer->foto = $file_name;
      }    
      $customer->update();
      // dd($request);

      return response()->json([
        'data' => $customer,
        'status' => 'success'
      ]);
    }
}

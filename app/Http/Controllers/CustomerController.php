<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function simpanCustomerApi(Request $request){
      // $request->validate([
      //   'nama' => ['required', 'string', 'max:255'],
      //   'id_jenis' => ['required'],
      //   'id_wilayah' => ['required'],
      //   'alamat_utama' => ['required', 'string', 'max:255'],
      //   'alamat_nomor' => ['required', 'string', 'max:255'],
      //   'keterangan_alamat' => ['required', 'string', 'max:255'],
      //   'telepon' => ['required', 'string', 'max:15'],
      //   'durasi_kunjungan' => ['required', 'integer'],
      //   'counter_to_effective_call' => ['integer'],
      //   'foto' => 'max:1024',
      // ]);
      dd($request->all() + ['id_staff' => 1]);

      $rules = [
        'nama' => ['required', 'string', 'max:255'],
        'id_jenis' => ['required'],
        'id_wilayah' => ['required'],
        'alamat_utama' => ['required', 'string', 'max:255'],
        'alamat_nomor' => ['required', 'string', 'max:255'],
        'keterangan_alamat' => ['required', 'string', 'max:255'],
        'telepon' => ['required', 'string', 'max:15'],
        'durasi_kunjungan' => ['required', 'integer'],
        'counter_to_effective_call' => ['integer'],
        'foto' => 'max:1024',
      ];

      if($request->file('foto')){
        $file= $request->file('foto');
        $filename=  date('Y-m-d').'-'.$request->nama.'-'.$request->alamat_utama.'.'.$file->getClientOriginalExtension();
        $request->foto= $filename;
        $file=$request->file('foto')->storeAs('customer', $filename);
      }


      $validator = Validator::make($request->all(), $rules);

      if($validator->fails()){
        return response()->json($validator->errors(),400);
      }
      

      dd($request->all() + ['id_staff' => 1]);
      
      $customer = Customer::create($request->all());
      return response()->json($customer, 201);
    }
}

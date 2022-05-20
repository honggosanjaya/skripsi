<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Trip;
use App\Models\District;
use App\Models\CustomerType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function cariCustomerApi(Request $request){
      $customer=Customer::where(strtolower('nama'),'like','%'.$request->nama.'%')->where(strtolower('alamat_utama'),'like','%'.$request->alamat_utama.'%')->with('linkDistrict')->get();

      if ($customer->count()>0) {
        return response()->json([
          'data' => $customer,
          'status' => 'success'
        ]);
      }
      return response()->json([
        'message' => 'data not found',
        'status' => 'error'
      ],404);

    }

    public function dataCustomerApi($id){
      $customer=Customer::find($id);
      

      if ($customer->count()>0) {
        return response()->json([
          'data' => $customer,
          'status' => 'success'
        ]);
      }
      return response()->json([
        'message' => 'data not found',
        'status' => 'error'
      ],404);

    }

    public function dataFormTripApi(){
      return response()->json([
        'customerType' => CustomerType::get(),
        'district' => District::get()
      ]);

    }

    public function simpanCustomerApi(Request $request){
      $rules = [
        'nama' => ['required', 'string', 'max:255'],
        'email' => ['string', 'email', 'max:255', 'unique:users'],
        'id_jenis' => ['required'],
        'id_wilayah' => ['required'],
        'alamat_utama' => ['required', 'string', 'max:255'],
        'alamat_nomor' => ['required', 'string', 'max:255'],
        'keterangan_alamat' => ['required', 'string', 'max:255'],
        'telepon' => ['required', 'string', 'max:15'],
        'durasi_kunjungan' => ['required', 'integer'],
      ];
      
      $validator = Validator::make($request->all(), $rules);
      $data = $request->except(['jam_masuk','alasan_penolakan','status','koordinat'])+['id_staff' => session('id_staff'), 'status' => 3,'created_at' => now()];
      $status = $request->status=='trip'?1:2;
      $id=null;

      if($validator->fails()){
        return response()->json($validator->errors(),400);
      }
      if ($request->id==null) {
        $customer = Customer::insertGetId($data+['koordinat' =>  $request->koordinat]);
        $id=$customer;
        if ($request->email!=null) {
          $user = User::create([
            'id_users' =>  $id,
            'email' => $request->email,
            'password' => Hash::make(12345678),
            'tabel' => 'customers',
        ]);
        Customer::find($customer)->update(['password'=>Hash::make(12345678)]);
        }
      } else {
        $id=$request->id;
        if ($request->email!=null&&Customer::find($id)->email==null) {
          $user = User::create([
            'id_users' =>  $id,
            'email' => $request->email,
            'password' => Hash::make(12345678),
            'tabel' => 'customers',
          ]);
        }
        $customer = Customer::find($id)->update($data);
        Customer::find($id)->update(['password'=>Hash::make(12345678)]);

      }
      if (Trip::where('id_customer',$id)->where('status',2)->count()==0) {
        Customer::find($id)->update(['counter_to_effective_call' => $request->counter_to_effective_call+1]);
      }
      

      // dd($request->jam_masuk);
      Trip::create(
        [
          'id_customer' => $id,
          'id_staff' => session('id_staff') ,
          'alasan_penolakan' => $request->alasan_penolakan,
          'koordinat' => $request->koordinat,
          'waktu_masuk' => date('Y-m-d H:i:s', $request->jam_masuk),
          'waktu_keluar' => now(),
          'status' => $status,
          'created_at'=> now()
        ]
      );
            
      // dd($request);
      return response()->json([
        'status' => 'success',
        'data' => Customer::find($id)
      ]);
    }

    public function simpanCustomerFotoApi(Request $request, $id){
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

      if ($request->foto) {
        $file_name = time() . '.' . $request->foto->extension();
        $request->foto->move(public_path('storage/customer'), $file_name);
        $customer->foto = $file_name;
      }    
      $customer->update();

      return response()->json([
        'data' => $customer,
        'status' => 'success'
      ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use App\Models\Status;
use App\Models\Customer;
use App\Models\District;
use App\Models\ReturType;
use App\Models\CustomerType;
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
        'keterangan_alamat' => ['string', 'max:255'],
        'telepon' => ['string', 'max:15'],
        'durasi_kunjungan' => ['required', 'integer'],
      ];
      if ($request->id==null){
        $rules['email'] = ['string', 'email', 'max:255', 'unique:users'];
      }
      if (Customer::find($request->id)->email == null) {
        $rules['email'] = ['string', 'email', 'max:255', 'unique:users'];
      }
      
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


    public function administrasiIndex(){
      return view('administrasi.dataCustomer.index', [
        'customers' => Customer::paginate(5),
        "title" => "Data Customer"
      ]);

    }

    public function administrasiCreate(){
      return view('administrasi.dataCustomer.create', [
        'customer_types' => CustomerType::all(),
        'districts' => District::all(),
        'retur_types' => ReturType::all(),
        'statuses' =>  Status::where('tabel', 'customers')->get(),
        "title" => "Data Customer - Add"
      ]);
    }

    public function administrasiStore(Request $request){
      $rules = [
        'nama' => ['required', 'string', 'max:255'],
        'id_jenis' => ['required'],
        'id_wilayah' => ['required'],
        'alamat_utama' => ['required', 'string', 'max:255'],
        'alamat_nomor' => ['nullable', 'string', 'max:255'],
        'keterangan_alamat' => ['nullable','string', 'max:255'],
        'telepon' => ['nullable','string', 'max:15'],
        'pengajuan_limit_pembelian' => ['nullable'],
        'status' => ['required'],
        'foto' => 'image|file|max:1024',
      ];

      if($request->email){
        $rules['email'] = 'string|email|max:255|unique:users';
      }
      
      $validatedData = $request->validate($rules);
      $validatedData['tipe_retur'] = $request->tipe_retur;
      $validatedData['id_staff'] = session(auth()->user()->id);
      $validatedData['limit_pembelian'] = 200000;
      $validatedData['durasi_kunjungan'] = 7;
      $validatedData['counter_to_effective_call'] = 0;

      if($request->pengajuan_limit_pembelian) {
        $validatedData['status_limit_pembelian'] = 7;
        //kirim notif ke spv
      }

      if ($request->foto) {
        $file_name = time() . '.' . $request->foto->extension();
        $request->foto->move(public_path('storage/customer'), $file_name);
        $validatedData['foto'] = $file_name;
      }    

      $customer = Customer::insertGetId($validatedData);
      $id=$customer;

      if ($request->email){
        User::create([
          'id_users' =>  $id,
          'email' => $request->email,
          'password' => Hash::make(12345678),
          'tabel' => 'customers',
        ]);
        Customer::find($customer)->update(['password'=>Hash::make(12345678)]);
      }

      return redirect('/administrasi/datacustomer') -> with('pesanSukses', 'Data berhasil ditambahkan' );
    }

    public function administrasiShow(Customer $customer)
    {
      return view('administrasi.dataCustomer.detail', [
        'customer' => $customer,
        'customer_types' => CustomerType::all(),
        'districts' => District::all(),
        'retur_types' => ReturType::all(),
        'statuses' =>  Status::where('tabel', 'customers')->get(),
        "title" => "Data Customer - Detail"
      ]);
    }

    public function administrasiEdit(Customer $customer){
      return view('administrasi.dataCustomer.edit', [
        'customer' => $customer,
        'customer_types' => CustomerType::all(),
        'districts' => District::all(),
        'retur_types' => ReturType::all(),
        'statuses' =>  Status::where('tabel', 'customers')->get(),
        "title" => "Data Customer - Edit"
      ]);
    }

    public function administrasiUpdate(Request $request, Customer $customer){
      $rules = [
        'nama' => ['required', 'string', 'max:255'],
        'id_jenis' => ['required'],
        'id_wilayah' => ['required'],
        'alamat_utama' => ['required', 'string', 'max:255'],
        'alamat_nomor' => ['nullable', 'string', 'max:255'],
        'keterangan_alamat' => ['nullable','string', 'max:255'],
        'telepon' => ['nullable','string', 'max:15'],
        'pengajuan_limit_pembelian' => ['nullable'],
        'status' => ['required'],
        'foto' => 'image|file|max:1024',
      ];

      if($request->email!=null && $request->email !== $customer->email){
        $rules['email'] = 'string|email|max:255|unique:users';
      }

      $validatedData = $request->validate($rules);
      $validatedData['tipe_retur'] = $request->tipe_retur;
      $validatedData['id_staff'] = $customer->id_staff;
      $validatedData['limit_pembelian'] = $request->limit_pembelian;
      $validatedData['durasi_kunjungan'] = $customer->durasi_kunjungan;
      $validatedData['counter_to_effective_call'] = $customer->counter_to_effective_call;
      $validatedData['pengajuan_limit_pembelian'] = $request->pengajuan_limit_pembelian;

      if ($request->foto) {
        $file_name = time() . '.' . $request->foto->extension();
        $request->foto->move(public_path('storage/customer'), $file_name);
        $validatedData['foto'] = $file_name;
      }
      
      if ($request->pengajuan_limit_pembelian!=null){
        $validatedData['status_limit_pembelian'] = 7;
        //kirim notif ke spv
      }

      if ($request->email!=null && $customer->email==null){
        User::create([
          'id_users' =>  $customer->id,
          'email' => $request->email,
          'password' => Hash::make(12345678),
          'tabel' => 'customers',
        ]);
      }

      Customer::where('id', $customer->id)->update($validatedData);

      return redirect('/administrasi/datacustomer') -> with('pesanSukses', 'Data berhasil diubah' );
    }

    public function administrasiEditStatusCustomer(Customer $customer)
    {
      $status = $customer->status;
      $nama_status = Status::where('id', $status)->first()->nama; 

      if($nama_status === 'active'){
        Customer::where('id', $customer->id)->update(['status' => 4]);
      }else if($nama_status === 'inactive'){
        Customer::where('id', $customer->id)->update(['status' => 3]);
      }

      return redirect('/administrasi/datacustomer') -> with('pesanSukses', 'Berhasil ubah status' );
    } 
}

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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller
{
    public function cariCustomerApi(Request $request){
      $customer=Customer::where(strtolower('nama'),'like','%'.$request->nama.'%')->where(strtolower('alamat_utama'),'like','%'.$request->alamat_utama.'%')->with(['linkDistrict','linkCustomerType'])->get();
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
      $customer=Customer::with('linkCustomerType')->find($id);
      
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
        'id_jenis' => ['required'],
        'id_wilayah' => ['required'],
        'alamat_utama' => ['required', 'string', 'max:255'],
        'durasi_kunjungan' => ['required', 'integer'],
      ];

      if($request->alamat_nomor){
        $rules['alamat_nomor'] = ['string', 'max:255'];
      }

      if($request->keterangan_alamat){
        $rules['keterangan_alamat'] = ['string', 'max:255'];
      }

      if($request->telepon){
        $rules['telepon'] = ['string', 'max:15'];
      }

      if($request->id){
        if (Customer::find($request->id)->email == null && $request->email !== null) {
          $rules['email'] = ['string', 'email', 'max:255', 'unique:users'];
        }
      }else{
        if ($request->email !== null) {
          $rules['email'] = ['string', 'email', 'max:255', 'unique:users'];
        }
      }

      $validator = Validator::make($request->all(), $rules);
      if ($validator->fails()){
        return response()->json([
          'validate_err' => $validator->errors()
        ]);
      }

      $data = $request->except(['jam_masuk','alasan_penolakan','id_staff','status','koordinat'])+[
        'status' => 3,
        'created_at' => now()
      ];
      $status = $request->status == 'trip' ? 1:2;
      $id_customer = null;

      if ($request->id==null) {
        $customer = Customer::insertGetId($data+['koordinat' =>  $request->koordinat]);
        $id_customer = $customer;
        if ($request->email!=null) {
          User::create([
            'id_users' =>  $id_customer,
            'email' => $request->email,
            'password' => Hash::make(12345678),
            'tabel' => 'customers',
          ]);
          Customer::find($id_customer)->update(['password'=>Hash::make(12345678)]);
        }
      } else {
        $id_customer = $request->id;
        if ($request->email!=null&&Customer::find($id_customer)->email==null) {
          User::create([
            'id_users' =>  $id_customer,
            'email' => $request->email,
            'password' => Hash::make(12345678),
            'tabel' => 'customers',
          ]);
        }
        $customer = Customer::find($id_customer)->update($data);
        Customer::find($id_customer)->update(['password'=>Hash::make(12345678)]);
      }

      if ($request->status == 'trip') {
        if (Customer::find($id_customer)->time_to_effective_call==null) {
          Customer::find($id_customer)->update(['counter_to_effective_call' => $request->counter_to_effective_call+1]);
        }
      }

      if($status == 1){
        Trip::create([
          'id_customer' => $id_customer,
          'id_staff' => $request->id_staff,
          'alasan_penolakan' => $request->alasan_penolakan,
          'koordinat' => $request->koordinat,
          'waktu_masuk' => date('Y-m-d H:i:s', $request->jam_masuk),
          'waktu_keluar' => now(),
          'status' => $status,
          'created_at'=> now()
        ]);
        Customer::find($id_customer)->update(['updated_at'=> now()]);
      } 
      // else if($status == 2){
      //   Trip::create([
      //     'id_customer' => $id_customer,
      //     'id_staff' => $request->id_staff,
      //     'alasan_penolakan' => $request->alasan_penolakan,
      //     'koordinat' => $request->koordinat,
      //     'waktu_masuk' => date('Y-m-d H:i:s', $request->jam_masuk),
      //     'waktu_keluar' => null,
      //     'status' => $status,
      //     'created_at'=> now()
      //   ]);
      // }

      return response()->json([
        'status' => 'success',
        'data' => Customer::find($id_customer),
      ]);
    }

    public function simpanCustomerFotoApi(Request $request, $id){
      $fileFoto = $request->foto;
      if($fileFoto == "null"){
        $fileFoto = null;
      }

      $customer = Customer::find($id);

      if($fileFoto !== null){
        $validator = Validator::make($request->all(), [
          'foto' => 'image|nullable|max:1024',
        ]);
    
        if ($validator->fails()) {
          return response()->json([
            'message' => 'validation fails',
            'errors' => $validator->errors()
          ]);
        }
      }

      if ($fileFoto !== null) {
        if($customer->foto){
          Storage::delete($customer->foto);
        }

        $nama_customer = str_replace(" ", "-", $customer->nama);
        $file_name = 'CUST-' . $nama_customer . '-' .date_format(now(),"YmdHis"). '.' . $request->foto->extension();
        $request->foto->move(public_path('storage/customer'), $file_name);
        $customer->foto = $file_name;
      }
          
      $customer->update();

      return response()->json([
        'data' => $customer,
        'status' => 'success',
        'message' => 'Berhasil menyimpan data. Ayo tetap semangat bekerja'
      ]);
    }

    public function administrasiIndex(){
      return view('administrasi.dataCustomer.index', [
        'customers' => Customer::orderBy('status','ASC')->orderBy('id','DESC')->get(),
        "title" => "Data Customer"
      ]);
    }

    public function administrasiSearch(){
      $customers =  Customer::orderBy('status','ASC')->where(strtolower('nama'),'like','%'.request('cari').'%')
        ->orWhere(strtolower('email'),'like','%'.request('cari').'%')
        ->orderBy('status','ASC')->paginate(10);

      return view('administrasi.dataCustomer.index', [
        'customers' => $customers,
        "title" => "Data Customer"
      ]);
    }

    public function supervisorSearch(){
      $customers =  Customer::where(strtolower('nama'),'like','%'.request('cari').'%')
        ->orWhere(strtolower('email'),'like','%'.request('cari').'%')
        ->paginate(10);

      return view('supervisor.dataCustomer.dataCustomer', [
        'customers' => $customers,
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
        'keterangan_alamat' => ['nullable', 'string', 'max:255'],
        'telepon' => ['nullable', 'string', 'max:15'],
        'pengajuan_limit_pembelian' => ['nullable'],
        'status' => ['required'],
        'foto' => ['image', 'file', 'max:1024'],
      ];

      if($request->email){
        $rules['email'] = 'string|email|max:255|unique:users';
      }
      
      $validatedData = $request->validate($rules);
      $validatedData['tipe_retur'] = $request->tipe_retur;
      $validatedData['id_staff'] = auth()->user()->id_users;
      $validatedData['limit_pembelian'] = 200000;
      $validatedData['durasi_kunjungan'] = 7;
      $validatedData['counter_to_effective_call'] = 1;
      $validatedData['time_to_effective_call'] = now();

      if($request->pengajuan_limit_pembelian) {
        $validatedData['status_limit_pembelian'] = 7;
        //kirim notif ke spv
      }

      if ($request->foto) {
        $file_name = 'CUST-' . $nama_customer . '-' .date_format(now(),"YmdHis"). '.' . $request->foto->extension();
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
        $file_name = 'CUST-' . $nama_customer . '-' .date_format(now(),"YmdHis"). '.' . $request->foto->extension();
        $request->foto->move(public_path('storage/customer'), $file_name);
        $validatedData['foto'] = $file_name;
      }
      
      if ($request->pengajuan_limit_pembelian!=null){
        $validatedData['status_limit_pembelian'] = 7;
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

    // public function administrasiEditStatusCustomer(Customer $customer){
    //   $status = $customer->status;
    //   $nama_status = Status::where('id', $status)->first()->nama; 

    //   if($nama_status === 'active'){
    //     Customer::where('id', $customer->id)->update(['status' => 4]);
    //   }else if($nama_status === 'inactive'){
    //     Customer::where('id', $customer->id)->update(['status' => 3]);
    //   }

    //   return redirect('/administrasi/datacustomer') -> with('pesanSukses', 'Berhasil ubah status' );
    // } 

    public function dataCustomer(){
      $customers = Customer::orderBy("status", "ASC")->paginate(10);
      return view('supervisor.datacustomer.dataCustomer', [
        'customers' => $customers,
        "title" => "Seluruh Data Customer"
      ]);
    }

    public function dataPengajuanLimit(){
      $customers = Customer::where('status_limit_pembelian', 7)->get();

      return view('supervisor.datacustomer.pengajuanLimit', [
        'customers' => $customers,
        "title" => "Data Pengajuan Limit Pembelian Customer"
      ]);
    }

    public function detailDataPengajuanLimit(Customer $customer){
      $customer = Customer::find($customer->id);

      return view('supervisor.datacustomer.detailPengajuanLimit', [
        'customer' => $customer,
        "title" => "Detail Pengajuan Limit Pembelian Customer"
      ]);
    }

    public function setujuPengajuanLimit(Customer $customer){
      Customer::find($customer->id)->update([
        'limit_pembelian' => $customer->pengajuan_limit_pembelian,
        'pengajuan_limit_pembelian' => null,
        'status_limit_pembelian' => 5
      ]);
      return redirect('/supervisor/datacustomer') -> with('pesanSukses', 'Berhasil menyetujui pengajuan' );
    }

    public function tolakPengajuanLimit(Customer $customer){
      Customer::find($customer->id)->update([
        'pengajuan_limit_pembelian' => null,
        'status_limit_pembelian' => 6
      ]);
      return redirect('/supervisor/datacustomer') -> with('pesanSukses', 'Berhasil menolak pengajuan' );
    }
}

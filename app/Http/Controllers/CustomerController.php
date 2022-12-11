<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use App\Models\Customer;
use App\Models\District;
use App\Models\ReturType;
use App\Models\Invoice;
use App\Models\CustomerType;
use App\Models\OrderItem;
use App\Models\RencanaTrip;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Mail\ConfirmationEmail;
use Illuminate\Support\Facades\Mail;
use Jenssegers\Agent\Agent;

class CustomerController extends Controller
{
    public function cariCustomerApi(Request $request){
      $customer=Customer::where(strtolower('nama'),'like','%'.$request->nama.'%')
              ->where(strtolower('alamat_utama'),'like','%'.$request->alamat_utama.'%')
              ->where('status_enum', '1')
              ->with(['linkDistrict','linkCustomerType'])->get();
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
      $customer=Customer::with(['linkCustomerType','linkDistrict'])->find($id);
      
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
        'district' => District::orderBy('nama', 'ASC')->get()
      ]);
    }

    public function simpanCustomerApi(Request $request){
      $rules = [
        'nama' => ['required', 'string', 'max:255'],
        'id_jenis' => ['required'],
        'id_wilayah' => ['nullable'],
        'alamat_utama' => ['required', 'string', 'max:255'],
        'durasi_kunjungan' => ['required', 'integer'],
        'jatuh_tempo' => ['required', 'integer'],
        'metode_pembayaran' => ['required']
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

      $data = $request->except(['jam_masuk','alasan_penolakan','id_staff','status_enum','koordinat'])+[
        'status_enum' => '1',
        'created_at' => now()
      ];
      
      $status = $request->status_enum == 'trip' ? 1:2;

      $id_customer = null;

      if ($request->id==null) {
        $customer = Customer::insertGetId($data+['koordinat' =>  $request->koordinat]);
        $id_customer = $customer;
        if ($request->email!=null) {
          $user = User::create([
            'id_users' =>  $id_customer,
            'email' => $request->email,
            'password' => Hash::make(12345678),
            'tabel' => 'customers',
          ]);

          // $details = [
          //   'title' => 'Konfirmasi Customer'.config('app.company_name'),
          //   'body' => 'Anda hanya perlu mengonfirmasi email anda. Proses ini sangat singkat dan tidak rumit. Anda dapat melakukannya dengan sangat cepat.',
          //   'user' => Customer::find($id_customer)
          // ];
          
          // Mail::to($request->email)->send(new ConfirmationEmail($details));  
          event(new Registered($user));
          Customer::find($id_customer)->update(['password'=>Hash::make(12345678)]);
        }
      } else {
        $id_customer = $request->id;
        if ($request->email!=null && Customer::find($id_customer)->email==null) {
          $user = User::create([
            'id_users' =>  $id_customer,
            'email' => $request->email,
            'password' => Hash::make(12345678),
            'tabel' => 'customers',
          ]);

          
          // $details = [
          //   'title' => 'Konfirmasi Customer'.config('app.company_name'),
          //   'body' => 'Anda hanya perlu mengonfirmasi email anda. Proses ini sangat singkat dan tidak rumit. Anda dapat melakukannya dengan sangat cepat.',
          //   'user' => Customer::find($id_customer)
          // ];
          
          // Mail::to($request->email)->send(new ConfirmationEmail($details));  
          event(new Registered($user));
          Customer::find($id_customer)->update(['password'=>Hash::make(12345678)]);
        }
        if (Customer::find($id_customer)->koordinat==null || Customer::find($id_customer)->koordinat=="0@0") {
          $customer = Customer::find($id_customer)->update($data + ['koordinat' =>  $request->koordinat]);
        }else {
          $customer = Customer::find($id_customer)->update($data);
        }
      }

      if ($request->status_enum == 'trip') {
        if (Customer::find($id_customer)->time_to_effective_call==null) {
          Customer::find($id_customer)->update(['counter_to_effective_call' => $request->counter_to_effective_call+1]);
        }
      }

      if($status == 1){
        if($request->koordinat == null){
          $request->koordinat = "0@0";
        }
        
        Trip::create([
          'id_customer' => $id_customer,
          'id_staff' => $request->id_staff,
          'alasan_penolakan' => $request->alasan_penolakan,
          'koordinat' => $request->koordinat,
          'waktu_masuk' => date('Y-m-d H:i:s', $request->jam_masuk),
          'waktu_keluar' => now(),
          'status_enum' => '1',
          'created_at'=> now()
        ]);
        Customer::find($id_customer)->update(['updated_at'=> now()]);

        $date = date("Y-m-d");

        RencanaTrip::where('id_staff', $request->id_staff)
        ->where('id_customer', $id_customer)
        ->where('tanggal', $date)->update([
          'status_enum' => '1'
        ]);
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
        Image::make($request->file('foto'))->resize(350, null, function ($constraint) {
          $constraint->aspectRatio();
        })->save(public_path('storage/customer/') . $file_name);
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
      $customers = Customer::orderBy('status_enum','ASC')->orderBy('id','DESC');

      $agent = new Agent();
      if($agent->isMobile()){
        return view('mobile.administrasi.dataCustomer.index', [
          'customers' => $customers->paginate(10)
        ]);
      }else{
        return view('administrasi.dataCustomer.index', [
          'customers' => $customers->get()
        ]);
      }
    }

    public function administrasiSearch(){
      $customers =  Customer::orderBy('status_enum','ASC')->where(strtolower('nama'),'like','%'.request('cari').'%')
        ->orWhere(strtolower('email'),'like','%'.request('cari').'%')
        ->orderBy('status_enum','ASC')->paginate(10);

      return view('administrasi.dataCustomer.index', [
        'customers' => $customers,
        "title" => "Data Customer"
      ]);
    }

    public function administrasiCreate(){
      $tipe_hargas = array();
      $tipe_hargas['harga1'] = array("value"=>1, "name"=>"Harga 1");
      $tipe_hargas['harga2'] = array("value"=>2, "name"=>"Harga 2");
      $tipe_hargas['harga3'] = array("value"=>3, "name"=>"Harga 3");

      $statuses = [
        1 => 'active',
        0 => 'hide',
        -1 => 'inactive',
      ];

      $data = [
        'customer_types' => CustomerType::all(),
        'districts' => District::orderBy('nama', 'ASC')->get(),
        'retur_types' => ReturType::all(),
        'statuses' =>  $statuses,
        'tipe_hargas' => $tipe_hargas,
        "title" => "Data Customer - Add"
      ];

      $agent = new Agent();
      if($agent->isMobile()){
        return view('mobile.administrasi.dataCustomer.create', $data);
      }else{
        return view('administrasi.dataCustomer.create', $data);
      }
    }

    public function administrasiStore(Request $request){
      $rules = [
        'nama' => ['required', 'string', 'max:255'],
        'id_jenis' => ['required'],
        'id_wilayah' => ['nullable'],
        'alamat_utama' => ['required', 'string', 'max:255'],
        'alamat_nomor' => ['nullable', 'string', 'max:255'],
        'keterangan_alamat' => ['nullable', 'string', 'max:255'],
        'telepon' => ['nullable', 'string', 'max:15'],
        'pengajuan_limit_pembelian' => ['nullable'],
        'tipe_harga' => ['required', 'integer'],
        'status_enum' => ['required'],
        'foto' => ['image', 'file', 'max:1024'],
        'status_telepon' => ['nullable', 'string', 'max:255'],
        'kode_customer' => ['nullable', 'string', 'max:255', 'unique:customers']
      ];

      if($request->email){
        $rules['email'] = 'string|email|max:255|unique:users';
      }
      
      $validatedData = $request->validate($rules);
      $validatedData['tipe_retur'] = $request->tipe_retur;
      $validatedData['tipe_harga'] = $request->tipe_harga;
      $validatedData['id_staff'] = auth()->user()->id_users;
      if($request->status_telepon){
        $validatedData['status_telepon'] = $request->status_telepon;
      }
      // $validatedData['limit_pembelian'] = 200000;
      $validatedData['durasi_kunjungan'] = 7;
      $validatedData['counter_to_effective_call'] = 1;
      $validatedData['created_at'] = now();
      $validatedData['updated_at'] = now();
      $validatedData['time_to_effective_call'] = now();

      if($request->pengajuan_limit_pembelian) {
        $validatedData['status_limit_pembelian_enum'] = '0';
      }

      if ($request->foto) {
        $file_name = 'CUST-' . $request->nama . '-' .date_format(now(),"YmdHis"). '.' . $request->foto->extension();
        Image::make($request->file('foto'))->resize(350, null, function ($constraint) {
          $constraint->aspectRatio();
        })->save(public_path('storage/customer/') . $file_name);
        $validatedData['foto'] = $file_name;
      }    

      $customer = Customer::insertGetId($validatedData);
      $id=$customer;

      if ($request->email){
        $user = User::create([
          'id_users' =>  $id,
          'email' => $request->email,
          'password' => Hash::make(12345678),
          'tabel' => 'customers',
        ]);
        event(new Registered($user));
        Customer::find($customer)->update(['password'=>Hash::make(12345678)]);

        // $details = [
        //   'title' => 'Konfirmasi Customer'.config('app.company_name'),
        //   'body' => 'Anda hanya perlu mengonfirmasi email anda. Proses ini sangat singkat dan tidak rumit. Anda dapat melakukannya dengan sangat cepat.',
        //   'user' => Customer::find($customer)
        // ];

        // Mail::to($request->email)->send(new ConfirmationEmail($details));       
      }

      return redirect('/administrasi/datacustomer') -> with('successMessage', 'Data berhasil ditambahkan' );
    }

    public function administrasiShow(Request $request, Customer $customer)
    {
      $oldData = [];
      if($request->route == 'bacapengajuan'){
        $oldData['pengajuan_limit_pembelian'] =$customer->pengajuan_limit_pembelian;
        $oldData['status_limit_pembelian_enum'] =$customer->status_limit_pembelian_enum;

        $customer->update([
          'pengajuan_limit_pembelian' => null,
          'status_limit_pembelian_enum' => null
        ]);
      }

      $invoices = Invoice::whereHas('linkOrder', function($q) use($customer){
        $q->where('id_customer', $customer->id);
      })->orderBy('id', 'DESC')->paginate(20);

      $invoicesSampai = Invoice::whereHas('linkOrder',function($q){
        $q->whereHas('linkOrderTrack', function($q){
          $q->where('status_enum','4');
        });
      })->get();
      $invoiceJatuhTempo = [];
      foreach($invoicesSampai as $invoice){
        if($invoice->jatuh_tempo != null){
          $waktuSampai = $invoice->linkOrder->linkOrderTrack->waktu_sampai;
          $tanggalSampai = date("Y-m-d",strtotime($waktuSampai));
          $tanggalSampai2 = date_create($tanggalSampai);

          $interval = date_add($tanggalSampai2, date_interval_create_from_date_string($invoice->jatuh_tempo . " days"));
          $tanggalJatuhTempo = date_format($interval,"Y-m-d");

          array_push($invoiceJatuhTempo, [
            'id_invoice' => $invoice->id,
            'tanggalJatuhTempo' => $tanggalJatuhTempo
          ]);
        }
      }

      $data = [
        'customer' => $customer,
        'customer_types' => CustomerType::all(),
        'districts' => District::orderBy('nama', 'ASC')->get(),
        'retur_types' => ReturType::all(),
        "title" => "Data Customer - Detail",
        'old_data' => $oldData,
        "invoices" => $invoices,
        "invoiceJatuhTempo" => $invoiceJatuhTempo
      ];

      $agent = new Agent();
      if($agent->isMobile()){
        return view('mobile.administrasi.dataCustomer.detail', $data);
      }else{
        return view('administrasi.dataCustomer.detail', $data);
      }
    }

    public function administrasiEdit(Customer $customer){
      $tipe_hargas = array();
      $tipe_hargas['harga1'] = array("value"=>"1", "name"=>"Harga 1");
      $tipe_hargas['harga2'] = array("value"=>"2", "name"=>"Harga 2");
      $tipe_hargas['harga3'] = array("value"=>"3", "name"=>"Harga 3");

      $statuses = [
        1 => 'active',
        0 => 'hide',
        -1 => 'inactive',
      ];

      $data = [
        'customer' => $customer,
        'customer_types' => CustomerType::all(),
        'districts' => District::orderBy('nama', 'ASC')->get(),
        'retur_types' => ReturType::all(),
        'statuses' =>  $statuses,
        'tipe_hargas' => $tipe_hargas,
        "title" => "Data Customer - Edit"
      ];

      $agent = new Agent();
      if($agent->isMobile()){
        return view('mobile.administrasi.dataCustomer.edit', $data);
      }else{
        return view('administrasi.dataCustomer.edit', $data);
      }
    }

    public function administrasiUpdate(Request $request, Customer $customer){
      $rules = [
        'nama' => ['required', 'string', 'max:255'],
        'id_jenis' => ['required'],
        'id_wilayah' => ['nullable'],
        'alamat_utama' => ['required', 'string', 'max:255'],
        'alamat_nomor' => ['nullable', 'string', 'max:255'],
        'keterangan_alamat' => ['nullable','string', 'max:255'],
        'telepon' => ['nullable','string', 'max:15'],
        'pengajuan_limit_pembelian' => ['nullable'],
        'tipe_harga' => ['required', 'integer'],
        'status_enum' => ['required'],
        'foto' => 'image|file|max:1024',
        'status_telepon' => ['nullable', 'string', 'max:255']
      ];

      if($request->email!=null && $request->email !== $customer->email){
        $rules['email'] = 'string|email|max:255|unique:users';
      }

      if($request->kode_customer!=null && $request->kode_customer !== $customer->kode_customer){
        $rules['kode_customer'] = 'string|max:255|unique:customers';
      }

      $validatedData = $request->validate($rules);
      $validatedData['tipe_retur'] = $request->tipe_retur;
      $validatedData['id_staff'] = $customer->id_staff;
      $validatedData['limit_pembelian'] = $request->limit_pembelian;
      $validatedData['durasi_kunjungan'] = $customer->durasi_kunjungan;
      $validatedData['counter_to_effective_call'] = $customer->counter_to_effective_call;
      $validatedData['pengajuan_limit_pembelian'] = $request->pengajuan_limit_pembelian;
      $validatedData['tipe_harga'] = $request->tipe_harga;
      $validatedData['status_telepon'] = $request->status_telepon;

      if ($request->foto) {
        $file_name = 'CUST-' . $request->nama . '-' .date_format(now(),"YmdHis"). '.' . $request->foto->extension();
        Image::make($request->file('foto'))->resize(350, null, function ($constraint) {
          $constraint->aspectRatio();
        })->save(public_path('storage/customer/') . $file_name);
        $validatedData['foto'] = $file_name;
      }
      
      if ($request->pengajuan_limit_pembelian!=null){
        $validatedData['status_limit_pembelian_enum'] = '0';
      }

      if ($request->email!=null && $customer->email==null){
        $user = User::create([
          'id_users' =>  $customer->id,
          'email' => $request->email,
          'password' => Hash::make(12345678),
          'tabel' => 'customers',
        ]);
        event(new Registered($user));

        // $details = [
        //   'title' => 'Konfirmasi Customer'.config('app.company_name'),
        //   'body' => 'Anda hanya perlu mengonfirmasi email anda. Proses ini sangat singkat dan tidak rumit. Anda dapat melakukannya dengan sangat cepat.',
        //   'user' => Customer::find($customer->id)
        // ];
        
        // Mail::to($request->email)->send(new ConfirmationEmail($details));  
      }

      if ($request->koordinat=='on') {
        Customer::where('id', $customer->id)->update($validatedData+['koordinat' =>  null]);
      } else {
        Customer::where('id', $customer->id)->update($validatedData);
      }
      

      return redirect('/administrasi/datacustomer') -> with('successMessage', 'Data berhasil diubah' );
    }

    public function dataCustomer(){
      $customers = Customer::orderBy("status_enum", "ASC")->orderBy('id','DESC')->get();
      return view('supervisor.datacustomer.dataCustomer', [
        'customers' => $customers,
        "title" => "Seluruh Data Customer"
      ]);
    }

    public function dataPengajuanLimit(){
      $customers = Customer::where('status_limit_pembelian_enum', '0')->get();

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
        // 'pengajuan_limit_pembelian' => null,
        'status_limit_pembelian_enum' => '1'
      ]);
      return redirect('/supervisor/datacustomer') -> with('successMessage', 'Berhasil menyetujui pengajuan' );
    }

    public function tolakPengajuanLimit(Customer $customer){
      Customer::find($customer->id)->update([
        // 'pengajuan_limit_pembelian' => null,
        'status_limit_pembelian_enum' => '-1'
      ]);
      return redirect('/supervisor/datacustomer') -> with('successMessage', 'Berhasil menolak pengajuan' );
    }

    public function detailCustomerSPV(Customer $customer){
      $invoices = Invoice::whereHas('linkOrder', function($q) use($customer){
        $q->where('id_customer', $customer->id);
      })->orderBy('id', 'DESC')->get();

      $invoicesSampai = Invoice::whereHas('linkOrder',function($q){
        $q->whereHas('linkOrderTrack', function($q){
          $q->where('status_enum','4');
        });
      })->get();
      $invoiceJatuhTempo = [];
      foreach($invoicesSampai as $invoice){
        if($invoice->jatuh_tempo != null){
          $waktuSampai = $invoice->linkOrder->linkOrderTrack->waktu_sampai;
          $tanggalSampai = date("Y-m-d",strtotime($waktuSampai));
          $tanggalSampai2 = date_create($tanggalSampai);

          $interval = date_add($tanggalSampai2, date_interval_create_from_date_string($invoice->jatuh_tempo . " days"));
          $tanggalJatuhTempo = date_format($interval,"Y-m-d");

          array_push($invoiceJatuhTempo, [
            'id_invoice' => $invoice->id,
            'tanggalJatuhTempo' => $tanggalJatuhTempo
          ]);
        }
      }

      return view('supervisor.datacustomer.detailCustomer', [
        'customer' => $customer,
        'invoices' => $invoices,
        "invoiceJatuhTempo" => $invoiceJatuhTempo
      ]);
    }

    public function generateQRCustomer(Customer $customer)
    {
      $agent = new Agent();
      if($agent->isMobile()){
        return view('mobile.administrasi.dataCustomer.qrCode', [
          "customer" => $customer
        ]);
      }else{
        return view('administrasi.dataCustomer.qrCode', [
          "customer" => $customer
        ]);
      }
    }

    public function cetakQRCustomer(Customer $customer){  
      $nama_customer = str_replace(" ", "-", $customer->nama);

      $exists = Storage::disk('local')->exists('/public/customer/QR-CUST-' . $nama_customer . '.svg');
      if($exists){
        Storage::delete('/public/customer/QR-CUST-' . $nama_customer . '.svg');
      }

      $image = \QrCode::format('svg')
                ->size(300)
                ->generate(env('APP_URL') . '/salesman/trip/' . $customer->id );
                // ->generate('https://salesman-dev.suralaya.web.id/salesman/trip/' . $customer->id );

      $output_file = '/public/customer/QR-CUST-' . $nama_customer . '.svg';

      Storage::disk('local')->put($output_file, $image); 

      $pdf = PDF::loadview('administrasi.dataCustomer.cetakQR',[
          'customer' => $customer,   
          'nama_customer' => $nama_customer
      ]);

      $pdf->setPaper('A5');
  
      return $pdf->stream('qr-'.$customer->id.'-'.$customer->nama.'.pdf'); 
    }
}

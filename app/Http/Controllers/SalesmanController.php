<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\District;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Kanvas;
use App\Models\LaporanPenagihan;
use App\Models\RencanaTrip;
use App\Models\Staff;
use App\Models\Target;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\DB;
use Util;

class SalesmanController extends Controller
{
  public function index(Request $request){
    $request->session()->increment('count');

    return view('salesman.dashboard',[
      'isDashboard' => true,
      'isSalesman' => true,
    ]);
  }

  public function profil(){
    $data = Staff::find(auth()->user()->id_users);
    return view('react.profil',[
      'page' => 'Profil Saya',
      'linkback' => '/salesman',
      'data' => $data,
      'isSalesman' => true
    ]);
  }

  public function changepassword(){
    return view('react.changepassword');
  }

  public function riwayatInvoice(Request $request){
    $date_start = $request->date_start ?? now()->subDays(30)->format('Y-m-d');
    $date_end = $request->date_end ?? now()->format('Y-m-d');
    $id_staff = auth()->user()->id_users;

    $datas = Invoice::whereBetween('created_at', [$date_start, $date_end])
              ->whereHas('linkOrder', function($q) use($id_staff) {
                  $q->where('id_staff', $id_staff);
                })
              ->orderBy('created_at','DESC')
              ->with(['linkOrder', 'linkOrder.linkOrderItem', 'linkOrder.linkCustomer'])
              ->get();

    return view('salesman.riwayatInvoice',[
      'page' => 'Riwayat Invoice',
      'linkback' => '/salesman',
      'invoices' => $datas,
      'date_start' => $date_start,
      'date_end' => $date_end
    ]);
  }

  public function tambahCustomer(){
    return view('salesman.tambahCustomer',[
      'page' => "Trip",
      'linkback' => '/salesman',
      'jenises' => CustomerType::get(),
      'wilayah' => District::orderBy('nama', 'ASC')->get()
    ]);
  }

  public function trip($id){
    $customer = Customer::find($id);
    return view('salesman.tambahCustomer',[
      'page' => "Trip",
      'linkback' => '/salesman',
      'customer' => $customer,
      'jenises' => CustomerType::get(),
      'wilayah' => District::orderBy('nama', 'ASC')->get()
    ]);
  }

  public function simpanCustomer(Request $request){
    // dd($request->all());
    $rules = [
      'nama' => ['required', 'string', 'max:255'],
      'alamat_utama' => ['required', 'string', 'max:255'],
      'alamat_nomor' => ['nullable', 'string', 'max:255'],
      'id_jenis' => ['required'],
      'id_wilayah' => ['nullable'],
      'keterangan_alamat' => ['nullable', 'string', 'max:255'],
      'telepon' => ['nullable', 'string', 'max:15'],
      'durasi_kunjungan' => ['required', 'integer'],
      'jatuh_tempo' => ['required', 'integer'],
      'metode_pembayaran' => ['required'],
    ];

    if($request->id){
      if (Customer::find($request->id)->email == null && $request->email !== null) {
        $rules['email'] = ['string', 'email', 'max:255', 'unique:users'];
      }
    }else{
      if ($request->email !== null) {
        $rules['email'] = ['string', 'email', 'max:255', 'unique:users'];
      }
    }
    
    $request->validate($rules);

    $data = $request->except(['jam_masuk','alasan_penolakan','id_staff','status_enum','koordinat','_token','bukti_galeri','bukti_kamera'])+[
      'status_enum' => '1',
      'created_at' => now()
    ];
    
    $status = $request->status_enum == 'trip' ? 1:2;
    $id_customer = null;

    if ($request->id==null) {
      $id_customer = Customer::insertGetId($data+['koordinat' =>  $request->koordinat, 'counter_to_effective_call'=>0]);
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

      $today_trip = Trip::where('id_customer', $id_customer)->where('id_staff', $request->id_staff)
                    ->whereDate('waktu_masuk', date('Y-m-d'))->latest()->first();

      if($today_trip ?? null){
        $today_trip->update([
          'alasan_penolakan' => $request->alasan_penolakan,
          'koordinat' => $request->koordinat,
          'waktu_keluar' => now(),
          'updated_at'=> now()
        ]);
      }else{
        Trip::create([
          'id_customer' => $id_customer,
          'id_staff' => $request->id_staff,
          'alasan_penolakan' => $request->alasan_penolakan,
          'koordinat' => $request->koordinat,
          'waktu_masuk' => $request->jam_masuk,
          'waktu_keluar' => now(),
          'status_enum' => '1',
          'created_at'=> now()
        ]);
      }

      Customer::find($id_customer)->update(['updated_at'=> now()]);
      $date = date("Y-m-d");
      RencanaTrip::where('id_staff', $request->id_staff)
                  ->where('id_customer', $id_customer)
                  ->where('tanggal', $date)->update([
                    'status_enum' => '1'
                  ]);
    } 

    $fileFoto = $request->bukti_galeri ?? $request->bukti_kamera ?? null;
    $customer = Customer::find($id_customer);

    if ($fileFoto ?? null) {
      if($customer->foto){
        Storage::delete($customer->foto);
      }
      $file = $request->file('bukti_galeri') ?? $request->file('bukti_kamera');
      $nama_customer = str_replace(" ", "-", $customer->nama);
      $file_name = 'CUST-' . $nama_customer. '-' .date_format(now(),"YmdHis"). '.' . $file->getClientOriginalExtension();
      Image::make($file)->resize(350, null, function ($constraint) {
        $constraint->aspectRatio();
      })->save(public_path('storage/customer/'). $file_name, 80);
      $customer->foto = $file_name;
      Util::backupFile(public_path('storage/customer/'.$file_name),'salesman-surya/storage/customer/');
    }  
    $customer->update();

    if($request->status_enum == 'trip'){
      return redirect('/salesman')->with('successMessage', 'Berhasil menyimpan data. Ayo tetap semangat bekerja');
    }else{
      return redirect('/salesman/order/'.$id_customer);
    }
  }

  public function riwayatKunjungan(Request $request){
    $tanggal_kunjungan = $request->tanggal_kunjungan ?? now()->format('Y-m-d');

    $targetkunjungan = Target::whereNull('tanggal_berakhir')->where('jenis_target','3')->first();
    $targetec = Target::whereNull('tanggal_berakhir')->where('jenis_target','4')->first();

    $trips = Trip::whereDate('waktu_masuk', $tanggal_kunjungan)
                ->where('id_staff', auth()->user()->id_users)
                ->with(['linkCustomer', 'linkCustomer.linkDistrict'])
                ->get();

    $ectrips = Trip::whereDate('waktu_masuk', $tanggal_kunjungan)
              ->where('id_staff', auth()->user()->id_users)
              ->where('status_enum','2')
              ->with(['linkCustomer', 'linkCustomer.linkDistrict'])
              ->get();

    return view('salesman.riwayatkunjungan',[
      'page' => 'Riwayat Kunjungan',
      'linkback' => '/salesman',
      'targetkunjungan' => $targetkunjungan,
      'targetec' => $targetec,
      'trips' => $trips,
      'ectrips' => $ectrips,
      'tanggal_kunjungan' => $tanggal_kunjungan,
      'status' => 'success'
    ]);

  }
  
  public function kanvas(){
    $kanvas = Kanvas::where('id_staff_yang_membawa', auth()->user()->id_users)
              ->whereNull('waktu_dikembalikan')
              ->with(['linkItem'])
              ->get();

    return view('salesman.kanvas',[
      'page' => 'Item Kanvas',
      'linkback' => '/salesman',
      'kanvas' => $kanvas,
    ]);
  }

  public function historyKanvas(Request $request){
    $listkanvas = Kanvas::where('id_staff_yang_membawa', auth()->user()->id_users)
                  ->select(DB::raw('GROUP_CONCAT(id) as ids'),'nama','id_staff_pengonfirmasi_pembawaan','id_staff_pengonfirmasi_pengembalian','waktu_dibawa','waktu_dikembalikan', DB::raw('COUNT(id_item) as banyak_jenis_item')) 
                  ->groupBy('nama','id_staff_pengonfirmasi_pembawaan','id_staff_pengonfirmasi_pengembalian','waktu_dibawa','waktu_dikembalikan')
                  ->orderBy('waktu_dibawa', 'DESC')
                  ->get();

    return view('salesman.historykanvas',[
      'page' => 'History Kanvas',
      'linkback' => '/salesman/itemkanvas',
      'listkanvas' => $listkanvas,
    ]);
  }

  public function catalog($idCust){
    $listItems = [];
    $tipeHarga = Customer::find($idCust)->tipe_harga;

    $items = Item::where('status_enum', '1')
            ->select('id', 'nama', 'kode_barang', 'stok', 'satuan', 'harga1_satuan', 'harga2_satuan', 'harga3_satuan', 'deskripsi') 
            ->with(['linkGaleryItem'])
            ->get();

    foreach($items as $item){
      $data = [
        'id' => $item->id,
        'nama' => $item->nama,
        'kode_barang' => $item->kode_barang,
        'stok' => $item->stok,
        'satuan' => $item->satuan,
        'gambar' => $item->linkGaleryItem,
        'deskripsi' => $item->deskripsi
      ];

      if($tipeHarga == 2 && $item->harga2_satuan ?? null){
        $data['harga_satuan'] = $item->harga2_satuan;
      }if($tipeHarga == 3 && $item->harga3_satuan ?? null){
        $data['harga_satuan'] = $item->harga3_satuan;
      } else{
        $data['harga_satuan'] = $item->harga1_satuan;
      }

      array_push($listItems, $data);
    }

    // dd($listItems);
    return view('salesman.catalog',[
      'page' => 'Katalog',
      'linkback' => '/salesman/trip/'.$idCust,
      'idCust' => $idCust,
      'data' => $listItems,
    ]);
  }

  public function detailCatalog($idCust, $idItem){
    $item = Item::where('id', $idItem)
              ->with(['linkGaleryItem'])
              ->first();
    $tipeHarga = Customer::find($idCust)->tipe_harga;

    $detailItem = [
      'id' => $item->id,
      'nama' => $item->nama,
      'kode_barang' => $item->kode_barang,
      'stok' => $item->stok,
      'satuan' => $item->satuan,
      'deskripsi' => $item->deskripsi,
      'link_item' => $item->link_item,
      'link_galery_item' => $item->linkGaleryItem,
    ];

    if($tipeHarga == 3 && $item->harga3_satuan != null){
      $detailItem['harga_satuan'] = $item->harga3_satuan;
    }else if($tipeHarga == 2  && $item->harga2_satuan != null){
      $detailItem['harga_satuan'] = $item->harga2_satuan;
    }else{
      $detailItem['harga_satuan'] = $item->harga1_satuan;
    }
    
    $relatedItem = Item::where('link_item', $item->link_item)
                    ->where('id', '!=', $item->id)
                    ->take(15)->get();

    $tenNewProduct = Item::latest()->where('id', '!=', $item->id)->take(10)->get();

    if($item->id_category != null){
      $fiveCategoryProduct = Item::where('id_category', $item->id_category)
      ->where('id', '!=', $idItem)->take(5)->get();
    }else{
      $fiveCategoryProduct = [];
    }

    return view('salesman.detailcatalog',[
      'page' => 'Detail Katalog',
      'linkback' => '/salesman/catalog',
      'item' => $detailItem,
      'tipeHarga' => $tipeHarga,
      'idCust' => $idCust,
      'related_item' => $relatedItem,
      'new_item' => $tenNewProduct,
      'category_item' => $fiveCategoryProduct
    ]); 
  }
  
  public function order(Request $request, $idCust){
    $customer=Customer::with(['linkCustomerType','linkDistrict'])->find($id);

    // return view('salesman.order',[
    //   'page' => 'Item Kanvas',
    //   'linkback' => '/salesman',
    //   'kanvas' => $kanvas,
    // ]);
  }
}

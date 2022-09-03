<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\OrderItem;
use App\Models\Staff;
use App\Models\Item;
use App\Models\Trip;
use App\Models\Event;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Vehicle;
use App\Models\Pembayaran;
use App\Models\History;
use App\Models\RencanaTrip;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class OrderController extends Controller
{
  public function simpanDataOrderSalesmanAPI(Request $request){
    $totalPesanan = $request->totalHarga;
    $keranjangItems = $request->keranjang;
    $idStaf = $request->idStaf;
    $estimasiWaktuPengiriman = $request->estimasiWaktuPengiriman;
    $keterangan = $request->keterangan;
    $id_order = $request->kodePesanan ?? 'belum ada';
    $id_customer = $request->idCustomer;
    $tipeRetur = $request -> tipeRetur;
    
    $customertype=Customer::with(['linkCustomerType'])->find($id_customer);

    if($id_order == "belum ada"){
      $limitPembelian = Customer::find($id_customer)->limit_pembelian;
      if($limitPembelian == null || $limitPembelian>=$totalPesanan){
        $id_order=Order::insertGetId([
          'id_customer' => $id_customer,
          'id_staff' => $idStaf,
          'status_enum' => '-1',
          'created_at'=> now(),
        ]);
        
        $data = [];
        foreach($keranjangItems as $item){
          array_push($data,[
            'id_order' => $id_order,
            'id_item' => $item['id'],
            'kuantitas' => $item['jumlah'],
            'harga_satuan' => $item['harga']-($item['harga']*$customertype->linkCustomerType->diskon/100),
            'created_at' =>  now(),
            'keterangan' => $request->keterangan??null,
          ]);
        }

        OrderTrack::insert([
          'id_order' => $id_order,
          'status_enum' => '1',
          'waktu_order'=> now(),
          'created_at'=> now(),
          'waktu_diteruskan' => now(),
          'estimasi_waktu_pengiriman' => $estimasiWaktuPengiriman,
        ]);
        OrderItem::insert($data);
        Customer::find($id_customer) -> update([
          'tipe_retur' => $tipeRetur,
          'metode_pembayaran' => $request->metode_pembayaran
        ]);
      }else{
        return response()->json([
          'status' => 'error',
          'error_message' => 'Total pesanan melebihi limit pembelian'
        ]);
      }
    }
    else{   
      $limitPembelian = Customer::find($id_customer)->limit_pembelian;
      if($limitPembelian == null || $limitPembelian>=$totalPesanan){
        foreach($keranjangItems as $item){
          $updateitem=OrderItem::where('id_order', $id_order)->where('id_item', $item['id'])->first();
          //jika data order item di database ditemukan
          if ($updateitem??null) {
            # update...
            $updateitem->update([
              'id_order' => $id_order,
              'id_item' => $item['id'],
              'kuantitas' => $item['jumlah'],
              'created_at' =>  now(),
              'harga_satuan' => $item['harga']-($item['harga']*$customertype->linkCustomerType->diskon/100),
              'keterangan' => $keterangan,
            ]);
          } else {
            # create...
            OrderItem::insert([
              'id_order' => $id_order,
              'id_item' => $item['id'],
              'kuantitas' => $item['jumlah'],
              'created_at' =>  now(),
              'harga_satuan' => $item['harga']-($item['harga']*$customertype->linkCustomerType->diskon/100),
              'keterangan' => $keterangan,
            ]);
          } 
        }

        $orderItems=OrderItem::where('id_order', $id_order)->get();
        foreach($orderItems as $orderItem){
          // cari $orderItem->id_item ada tidak di id item di cart, jika tidak ada jalankan if
          $key = array_search($orderItem->id_item, array_column($keranjangItems, 'id'));
            if($key === false){
              # delete...
              $orderItem->delete();
            }
        }

        Order::where('id', $id_order)->update([
          'id_staff' => $idStaf,
        ]);

        OrderTrack::where('id_order', $id_order)->update([
          'waktu_diteruskan' => now(),
          'status_enum' => '1',
          'estimasi_waktu_pengiriman' => $estimasiWaktuPengiriman,
        ]);
        Customer::find($id_customer) -> update([
          'tipe_retur' => $tipeRetur,
          'metode_pembayaran' => $request->metode_pembayaran
        ]);    
      }else{
        return response()->json([
          'status' => 'error',
          'error_message' => 'Total pesanan melebihi limit pembelian'
        ]);
      }   
    }

    $kode_event = $request -> kodeEvent ?? null;
    $id_event = null;
    if ($kode_event != null) {
      $id_event = Event::where('kode', $kode_event)->first()->id;
    }
    $invoice_count="INV-". explode("-",Invoice::orderBy("id", "DESC")->first()->nomor_invoice ?? 'INV-0')[1] + 1 ."-".date_format(now(),"YmdHis");
    Invoice::insert([
      'id_order' => $id_order,
      'id_event' => $id_event,
      'nomor_invoice' => $invoice_count,
      'harga_total' => $totalPesanan,
      'counter_unduh' => 0,
      'metode_pembayaran' => $request->metode_pembayaran,
      'created_at' => now()
    ]);

    Trip::find($request->idTrip)->update([
      'waktu_keluar' => now(),
      'updated_at' => now(),
      'alasan_penolakan' => $request->alasan_penolakan
    ]);
    
    if (Customer::find($id_customer)->koordinat==null) {
      Customer::find($id_customer)->update(['koordinat' =>  $request->koordinat]);
    }else{
      Customer::find($id_customer)->update(['updated_at'=> now()]);
    }


    if (Customer::find($id_customer)->time_to_effective_call==null) {
      Customer::find($id_customer)->update([
        'time_to_effective_call' => now(),
        'id_staff' => $idStaf 
      ]);
    }

    return response()->json([
      'status' => 'success',
      'success_message' => 'berhasil membuat pesanan'
    ]);
  }

  public function keluarTripOrderApi(Request $request, $id){
    $idCust = Trip::find($id)->id_customer; 
    Trip::find($id)->update([
      'waktu_keluar' => now(),
      'updated_at' => now(),
      'status_enum' => '1',
      'alasan_penolakan' => $request->alasan_penolakan
    ]);
    Customer::where('id', $idCust)->update(['updated_at'=> now()]);

    return response()->json([
      'status' => 'success',
      'message'=>'Jam keluar berhasil dicatat',
      'data' => Trip::find($id)
    ]);
  }

  public function catatTripOrderApi(Request $request){
    $id_customer = $request->idCustomer;
    $id_staff = $request->idStaff;
    $customer = Customer::find($id_customer);

    // if (Customer::find($id_customer)->time_to_effective_call==null) {
    //   Customer::find($id_customer)->update([
    //     'counter_to_effective_call' => $customer->counter_to_effective_call+1
    //   ]);
    // }
    // Customer::update(['updated_at'=> now()]);

    $trip=Trip::where('id_customer',$id_customer)->orderby('id','desc')->first();
    $trip_data = [
      'id_customer' => $id_customer,
      'id_staff' => $id_staff,
      'alasan_penolakan' => null,
      'koordinat' => $request->koordinat,
      'waktu_masuk' => date('Y-m-d H:i:s', $request->jam_masuk),
      'waktu_keluar' => null,
      'status_enum' => '2',
      'created_at'=> now()
    ];
    if($trip == null){
      $tripOrder = Trip::insertGetId($trip_data);
      $id_trip = $tripOrder;
    }
    else if ($trip->waktu_keluar!=null) {
      $tripOrder = Trip::insertGetId($trip_data);
      $id_trip = $tripOrder;
    }else{
      $id_trip = $trip->id;
    }

    if (Customer::find($id_customer)->time_to_effective_call==null) {
      Customer::find($id_customer)->update([
        'counter_to_effective_call' => $customer->counter_to_effective_call+1
      ]);
    }
    if (Customer::find($id_customer)->koordinat==null) {
      Customer::find($id_customer)->update(['koordinat' =>  $request->koordinat]);
    }else{
      Customer::find($id_customer)->update(['updated_at'=> now()]);
    }

    $date = date("Y-m-d");
    RencanaTrip::where('id_staff', $id_staff)
    ->where('id_customer', $id_customer)
    ->where('tanggal', $date)->update([
      'status_enum' => '1'
    ]);

    return response()->json([
      'status' => 'success',
      'message'=>'Trip Order berhasil dicatat',
      'data' => Trip::find($id_trip)
    ]); 
  }

  public function dataKodeCustomer($id){
    $order = Order::whereHas('linkOrderTrack',function($q) {
      $q->where('waktu_diteruskan', null);
    })->with(['linkOrderTrack'])
    ->where('id', $id)->first();

    $order_item = OrderItem::where('id_order', $id)->with(['linkItem'])->get();

    if($order!==null){
      return response()->json([
        'status' => 'success',
        'dataOrder' => $order,
        'dataOrderItem' => $order_item,
        'message' => 'berhasil memasukkan produk dalam keranjang'
      ]);
    } else{
      return response()->json([
        'status' => 'error',
        'message' => 'kode customer tidak ditemukan'
      ]);
    }
  }

  public function index(){
    $orders = Order::orderBy('created_at','DESC')->with(['linkOrderTrack'])->where('id_customer','>','0')
    ->whereHas('linkOrderTrack',function($q) {
      $q->where('id_staff_pengonfirmasi', auth()->user()->id_users)->orWhere('id_staff_pengonfirmasi', null);
    })->get();

    return view('administrasi.pesanan.index',[
      'orders' => $orders,                      
    ]);
  }

  // public function search(){
  //   //ini take out sementara
  //   $orders = Order::join('order_tracks','orders.id','=','order_tracks.id_order')
  //     ->join('statuses','order_tracks.status','=','statuses.id')
  //     ->join('invoices','orders.id','=','invoices.id_order')
  //     ->where(strtolower('nomor_invoice'),'like','%'.request('cari').'%')
  //     ->paginate(10);  
    
  //     $statuses = Status::where('tabel','=','order_tracks')->get();

  //     return view('administrasi.pesanan.index',[
  //         'orders' => $orders,
  //         'statuses' => $statuses
  //     ]);
  // }

  // public function filter(){
  //   if(request('status') != ""){
  //     $orders = Order::whereHas('linkOrderTrack',function($q) {
  //         $q->where('status', request('status'));
  //       })->with(['linkOrderTrack.linkStatus','linkInvoice'])->paginate(10);
  //   }
  //   else{
  //     $orders = Order::with(['linkOrderTrack.linkStatus','linkInvoice'])->paginate(10);
  //   }
    
  //   $statuses = Status::where('tabel','=','order_tracks')       
  //   ->get();

  //   return view('administrasi/pesanan.index',[
  //       'orders' => $orders,            
  //       'statuses' => $statuses
  //   ]);      
  // }

  public function viewDetail(Order $order){
    $items = OrderItem::where('id_order','=',$order->id)->get();
    $inactiveVehicles = Vehicle::where('is_active',false)->get();
    $activeVehicles = Vehicle::where('is_active',true)->get();
    $invoice = Invoice::where('id_order','=',$order->id)->first();

    $total_bayar = Invoice::where('invoices.id', $invoice->id)
    ->join('pembayarans','invoices.id','=','pembayarans.id_invoice')
    ->whereHas('linkOrder', function($q) {
      $q->whereHas('linkOrderTrack', function($q) {
        $q->whereIn('status_enum',['4','5','6']);
      });
    })
    ->select('pembayarans.id_invoice', \DB::raw('SUM(pembayarans.jumlah_pembayaran) as total_bayar'))
    ->groupBy('pembayarans.id_invoice')->get()->sum('total_bayar');

    if($total_bayar==null){
      $total_bayar = 0;
    }
    
     $pembayaran_terakhir = Pembayaran::where('id_invoice',$invoice->id)
     ->orderBy('id', 'DESC')->first();

    return view('administrasi.pesanan.detailpesanan',[
      'order' => $order,
      'items' => $items,
      'total_bayar' => $total_bayar,
      'pembayaran_terakhir' => $pembayaran_terakhir,
      'inactiveVehicles' => $inactiveVehicles,
      'activeVehicles' => $activeVehicles
    ]);
  }

  public function viewKapasitas(Order $order){
    $kendaraans = Vehicle::get();
    $tempDataOrders = array();
    $tempDataIdOrders = array();

    function getPersentaseVolume($vehicleId, $orderId){
      // get persentase volume kendaraan thdp seuatu order
      $kendaraan = Vehicle::where('id', $vehicleId)->first();
      $tempVolume = array();
      $order = Order::where('id', $orderId)->first();
      $orderItemDatas = $order->linkOrderItem;
      $itemData = '';
      $volume = 0;
      $totalVolume = 0;
                        
      for($i=0; $i<$orderItemDatas->count(); $i++){
        $itemData = Item::where('id','=',$orderItemDatas[$i]->id_item)->first();
        $volume = $itemData->volume * $orderItemDatas[$i]->kuantitas;
        array_push($tempVolume, $volume);
      }   

      $totalVolume = array_sum($tempVolume);

      return ($totalVolume/$kendaraan->kapasitas_volume)*100;
    }

    function getPersentaseHarga($vehicleId, $orderId){
      // get persentase harga kendaraan thdp seuatu order
      $kendaraan = Vehicle::where('id', $vehicleId)->first();
      $invoice = Invoice::where('id_order',$orderId)->first();
                 
      return ($invoice->harga_total/$kendaraan->kapasitas_harga)*100;
    }

     // get all order yang menggunakan kendaraan tertentu
    foreach($kendaraans as $kendaraan){
      $dataOrder = Order::whereHas('linkOrderTrack', function($q) use($kendaraan){
        $q->where([
          ['id_vehicle', '=', $kendaraan->id],
          ['status_enum', '>', '1'],
          ['status_enum', '<', '4'],
        ]);
      })->pluck('id');
      array_push($tempDataOrders, $dataOrder);
    }

    // dd($tempDataOrders);

    for($i=0; $i<$kendaraans->count(); $i++){
      if(sizeof($tempDataOrders[$i]) != 0){
        // jika ada pesanan yang di assign ke kendaraan
        for($j=0; $j<sizeof($tempDataOrders[$i]); $j++){
          $persentaseVolume = getPersentaseVolume($kendaraans[$i]->id, $tempDataOrders[$i][$j]);
          $persentaseHarga = getPersentaseHarga($kendaraans[$i]->id, $tempDataOrders[$i][$j]);

          if($j % 5 == 0){
            $color = 'primary';
          }else if($j % 5 == 1){
            $color = 'success';
          }else if($j % 5 == 2){
            $color = 'info';
          }else if($j % 5 == 3){
            $color = 'warning';
          }else{
            $color = 'danger';
          }

          $inv = Invoice::where('id_order',$tempDataOrders[$i][$j])->first()->nomor_invoice;
          $invoice = substr($inv, 0, 5);

          array_push($tempDataIdOrders,[
            'id_vehicle' => $kendaraans[$i]->id,
            'nama_vehicle' => $kendaraans[$i]->nama,
            'kode_vehicle' => $kendaraans[$i]->kode_kendaraan,
            'id_order' => $tempDataOrders[$i][$j],
            'invoice' => $invoice,
            'color' => $color,
            'persentase_volume' =>  $persentaseVolume,
            'persentase_harga' => $persentaseHarga,
            'total_persentase_volume' => $persentaseVolume,
            'total_persentase_harga' => $persentaseHarga
          ]);
        }
      }else{
        // jika tidak ada pesanan yang di assign ke kendaraan
        array_push($tempDataIdOrders,[
          'id_vehicle' => $kendaraans[$i]->id,
          'nama_vehicle' => $kendaraans[$i]->nama,
          'kode_vehicle' => $kendaraans[$i]->kode_kendaraan,
          'id_order' => null,
          'invoice' => null,
          'color' => null,
          'persentase_volume' =>  0,
          'persentase_harga' => 0,
          'total_persentase_volume' => 0,
          'total_persentase_harga' => 0
        ]);
      }
    }

    // dd($tempDataIdOrders);

    $sameVehicle = array();
    foreach ($tempDataIdOrders as $item) {
      $key = $item['id_vehicle'];
      if (!array_key_exists($key, $sameVehicle)) {
        // jika tidak ada yang sama
        $sameVehicle[$key] = array(
          'id_vehicle' => $item['id_vehicle'],
          'nama_vehicle' => $item['nama_vehicle'],
          'kode_vehicle' => $item['kode_vehicle'],
          'id_order' => $item['id_order'],
          'invoice' => $item['invoice'],
          'color' => $item['color'],
          'persentase_volume' => $item['persentase_volume'],
          'persentase_harga' => $item['persentase_harga'],
          'total_persentase_volume' => $item['total_persentase_volume'],
          'total_persentase_harga' => $item['total_persentase_harga'],
        );
      } else {
        // jika ada yang sama
        $sameVehicle[$key]['color'] = $sameVehicle[$key]['color'] . '+' . $item['color'];
        $sameVehicle[$key]['id_order'] = $sameVehicle[$key]['id_order'] . '+' . $item['id_order'];
        $sameVehicle[$key]['invoice'] = $sameVehicle[$key]['invoice'] . '+' . $item['invoice'];
        $sameVehicle[$key]['persentase_volume'] = $sameVehicle[$key]['persentase_volume'] . '+' . $item['persentase_volume'];
        $sameVehicle[$key]['persentase_harga'] = $sameVehicle[$key]['persentase_harga'] . '+' . $item['persentase_harga'];
        $sameVehicle[$key]['total_persentase_volume'] = $sameVehicle[$key]['total_persentase_volume'] + $item['total_persentase_volume'];
        $sameVehicle[$key]['total_persentase_harga'] = $sameVehicle[$key]['total_persentase_harga'] + $item['total_persentase_harga'];
      }
    }

    // dd($sameVehicle);

    $selectedVehicle = OrderTrack::where('id_order', $order->id)->first()->id_vehicle;

    return view('administrasi.pesanan.kapasitaskendaraan',[
        'order' => $order,
        'datas' => $sameVehicle,
        'selectedVehicle' => $selectedVehicle
    ]);
  }

  public function cetakInvoice(Order $order){
    $items = OrderItem::where('id_order','=',$order->id)->get();
    $administrasi = Staff::select('nama')->where('id','=',auth()->user()->id_users)->first();
    $invoice = Invoice::where('id_order', $order->id)->first();

    Invoice::where('id_order', $order->id)->update([
      'counter_unduh' => $invoice->counter_unduh+1
    ]);

    $pdf = PDF::loadview('administrasi.pesanan.detail.cetakInvoice',[
        'order' => $order,
        'items' => $items,
        'administrasi' => $administrasi           
      ]);

    return $pdf->stream('invoice-'.$order->linkInvoice->nomor_invoice.'.pdf');  
  }

  public function cetakSJ(Order $order){
    $items = OrderItem::where('id_order','=',$order->id)->get();
    $todayDate = date("d M Y");
    $orderTrack = OrderTrack::where('id_order','=',$order->id)->first();
                
    $mengetahui = Staff::select('nama')->where('id','=',$orderTrack->id_staff_pengonfirmasi)->first();
    $pengirim = Staff::select('nama')->where('id','=',$orderTrack->id_staff_pengirim)->first();
   
    $pdf = PDF::loadview('administrasi.pesanan.detail.cetakSJ',[
        'order' => $order,
        'items' => $items,
        'date' => $todayDate,
        'pengirim' => $pengirim,
        'mengetahui' => $mengetahui            
      ]);

    return $pdf->stream('Surat Jalan-'.date("d F Y").'.pdf'); 
  }

  public function cetakMemo(Order $order){
    $items = OrderItem::where('id_order','=',$order->id)->get();
    $todayDate = date("d-m-Y");
    $administrasi = Staff::select('nama')->where('id','=',auth()->user()->id_users)->first();

    $pdf = PDF::loadview('administrasi/pesanan/detail.cetakMemo',[
        'order' => $order,
        'items' => $items,
        'date' => $todayDate,
        'administrasi' => $administrasi          
      ]);

    return $pdf->stream('memo-'.$order->linkInvoice->nomor_invoice.'.pdf'); 
  }
  
  public function simpanDataOrderCustomer(Request $request){
    $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();

    $order_id=Order::insertGetId([
        'id_customer' => auth()->user()->id_users,
        'status_enum' => '-1',
        'created_at'=> now(),
    ]);

    $data = [];
    foreach($cartItems as $item){
    array_push($data,[
        'id_item' => $item->id,
        'id_order' => $order_id,
        'kuantitas' => $item->quantity,
        'created_at' =>  now(),
        'harga_satuan' => $item->price,
        'keterangan' => $request->keterangan??null,
    ]);
    }

    OrderTrack::insert([
        'id_order' => $order_id,
        'status_enum' => '0',
        'waktu_order'=> now(),
        'created_at'=> now(),
    ]);
    OrderItem::insert($data);

    \Cart::session(auth()->user()->id.$request->route)->clear();

    return redirect('/customer')->with('pesanSukses', 'Pesanan Berhasil Dibuat');
  }

  public function getListShippingAPI(Request $request){
    $id_staff=$request->id_staff;

    $first_data=Order::
    whereHas('linkOrderTrack',function($q) use($id_staff) {
      $q->where('id_staff_pengirim', $id_staff);
    })->with(['linkOrderTrack','linkInvoice','linkCustomer','linkOrderItem']);

    $data = $first_data->where(function ($query) {
      $query->whereHas('linkOrderTrack',function($q) {
              $q->where('status_enum', '3');
            })
            ->orWhereHas('linkOrderTrack',function($q) {
              $q->where('status_enum','>', '3')->where('status_enum','<=', '6')->whereBetween('waktu_sampai',[now()->subDays(2),now()]);
            });
    })->orderBy('id','DESC');

    if($request->nama_customer != null){
      $data->where(function ($query) use($request){
        $query->whereHas('linkCustomer', function($q) use($request){
          $q->where(strtolower('nama'),'like','%'.$request->nama_customer.'%');
        });
      });
    }

    $data = $data->get();

    $perludikirim=$first_data->where(function ($query) {
      $query->whereHas('linkOrderTrack',function($q) {
        $q->where('status_enum', '3');
      });
    })->count();

    $sudahsampai=$data->count() - $perludikirim;

    return response()->json([
      'data' => [
        'data' => $data,
        'perludikirim' => $perludikirim,
        'sudahsampai' => $sudahsampai,
      ],
      'status' => 'success'
    ]);
  }

  public function getDetailShippingAPI(Request $request){
    $data=Order::with(['linkOrderTrack','linkInvoice.linkRetur','linkCustomer','linkOrderItem.linkItem'])->find($request->id);
    return response()->json([
      'data' => $data,
      'status' => 'success'
    ]);
  }

  public function setujuPesanan(Order $order, Request $request){
    $order = Order::find($order->id);
    $totalHarga = 0;
    $orderItems = OrderItem::where('id_order', $order->id)->get();
    $jumlahItem = $orderItems->count();
    $i = 0;
    $itemYangKurang = [];

    foreach($orderItems as $orderItem){
      $item = Item::find($orderItem->id_item);
      $item->stok -= $orderItem->kuantitas;
      if($item->stok >= 0){
        $i += 1;
      }else{
        array_push($itemYangKurang,
          $item->nama,
        );
      }
    }

    if(sizeof($itemYangKurang) > 0){
      return redirect('/administrasi/pesanan/detail/'.$order->id) 
      -> with('pesanError', 'Tidak dapat menyetujui pesanan jumlah stok kurang');
    }

    if($i == $jumlahItem){
      foreach($orderItems as $orderItem){
        $item = Item::find($orderItem->id_item);
        $totalHarga = $totalHarga + ($orderItem->kuantitas * $orderItem->harga_satuan);
        $item->stok -= $orderItem->kuantitas;
        $item->save();

        $stokMaksimalTerakhir = History::where("id_item", $item->id)->orderBy("id", "DESC")->first()->stok_maksimal_customer ?? 0;
        $stokSekarang = (History::where("id_item", $item->id)->orderBy("id", "DESC")->first()->stok_terakhir_customer ?? 0) + $orderItem->kuantitas;


        if($stokSekarang > $stokMaksimalTerakhir){
          History::updateOrCreate(
            ['id_customer' => $order->id_customer, 'id_item' => $item->id],
            ['stok_maksimal_customer' => $stokSekarang, 'stok_terakhir_customer' => $stokSekarang]
          );
        }else{
          History::updateOrCreate(
            ['id_customer' => $order->id_customer, 'id_item' => $item->id],
            ['stok_maksimal_customer' => $stokMaksimalTerakhir, 'stok_terakhir_customer' => $stokSekarang]
          );
        }
      }

      Invoice::where('id_order', $order->id)->update([
        'updated_at' => now()
      ]);

      Order::where('id', $order->id)->update([
        'status_enum' => '1'
      ]);

      $rules = [
        'id_vehicle' => ['required']
      ];
      $validatedData = $request->validate($rules);
      $validatedData['id_staff_pengonfirmasi'] = auth()->user()->id_users;
      $validatedData['status_enum'] = '2';
      $validatedData['waktu_dikonfirmasi'] = now();
      OrderTrack::where('id_order', $order->id)->update($validatedData); 

      return redirect('/administrasi/pesanan/detail/'.$order->id) -> with('addPesananSuccess', 'Berhasil menyetujui pesanan');
    } 
  }

  public function tolakPesanan(Order $order){
    $order = Order::find($order->id);

    OrderTrack::where('id_order', $order->id)->update([
      'status_enum' => '-1',
      'waktu_dikonfirmasi'=> now()
    ]);


    return redirect('/administrasi/pesanan/detail/'.$order->id) -> with('addPesananSuccess', 'Berhasil menolak pesanan');
  }

  public function viewPengiriman(order $order){
    $orderItems = OrderItem::where('id_order', $order->id)->get();
    $stafs = Staff::where('status_enum', '1')->whereIn('role', [3,4])->get();

    // $kapasitas_harga = 0;
    // $kapasitas_volume = 0;
    // foreach($orderItems as $orderItem){
    //   $item = Item::where('id', $orderItem->id_item)->first();
    //   $kapasitas_harga += $orderItem->harga_satuan * $orderItem->kuantitas;
    //   $kapasitas_volume += $item->volume * $orderItem->kuantitas;
    // };

    // $vehicleLoads = Vehicle::where('kapasitas_volume', '>=', $kapasitas_volume)
    //             ->orWhere('kapasitas_harga', '>=', $kapasitas_harga)->get();

    // $vehicles = $vehicleLoads->where('is_active',true);

    $activeVehicles = Vehicle::where('is_active',true)->get();
    $inactiveVehicles = Vehicle::where('is_active',false)->get();
    $selectedVehicle = OrderTrack::where('id_order', $order->id)->first()->id_vehicle;

    return view('administrasi.pesanan.pengiriman.keberangkatan',[
      'order' => $order,
      'stafs' => $stafs,
      'activeVehicles' => $activeVehicles,
      'inactiveVehicles' => $inactiveVehicles,
      'selectedVehicle' => $selectedVehicle
    ]);
  }

  public function konfirmasiPengiriman(Request $request, order $order){
    if($request && $order->linkOrderTrack->status_enum == '2'){
      $rules = [
        'id_staff_pengirim' => ['required'],
        'id_vehicle' => ['required']
      ];
      $validatedData = $request->validate($rules);
      $validatedData['status_enum'] = '3';
      $validatedData['waktu_berangkat'] = now();
      OrderTrack::where('id_order', $order->id)->update($validatedData);        

      Vehicle::where('id', $request->id_vehicle)->update([
        'is_active' => false
      ]);

      return redirect('/administrasi/pesanan/detail/'.$order->id) -> with('addPesananSuccess', 'Berhasil mengonfirmasi keberangkatan pengiriman untuk '.$order->linkCustomer->nama);
    }

    if($order->linkOrderTrack->status_enum == '3'){
      $rules = [
        'foto' => 'image|file',
      ];

      $file= $request->file('foto');
      $file_name=  'DLV-'.$order->id.'.'.$file->getClientOriginalExtension();
      Image::make($request->file('foto'))->resize(350, null, function ($constraint) {
        $constraint->aspectRatio();
      })->save(public_path('storage/pengiriman/') . $file_name);
      $validatedData['foto_pengiriman'] = $file_name;
      $validatedData['status_enum'] = '4';
      $validatedData['waktu_sampai'] = now();
      OrderTrack::where('id_order', $order->id)->update($validatedData);

      $ordertracks = OrderTrack::all();
      $vehicleInRoads = array();

      foreach($ordertracks as $ordertrack){
        if($ordertrack->status_enum == '3'){
          array_push($vehicleInRoads, [
            'id_vehicle' => $ordertrack->id_vehicle
          ]);
        }
      }

      DB::table('vehicles')->update(['is_active' => true]);

      foreach ($vehicleInRoads as $vehicle) {
        Vehicle::where('id', $vehicle['id_vehicle'])->update([
          'is_active' => false
        ]);
      }

      return response()->json([
        'status' => 'success',
        'message' => 'Pengiriman untuk '.$order->linkCustomer->nama.' telah sampai'
      ]);
    }

    if($order->linkOrderTrack->status_enum == '5'){
      OrderTrack::where('id_order', $order->id)->update([
        'status_enum' => '6'
      ]);
      return redirect('/administrasi/pesanan/detail/'.$order->id) -> with('addPesananSuccess', 'Pesanan untuk '.$order->linkCustomer->nama.' telah selesai');
    }
  }

  public function hapusKodeCustomer(Order $order){
    Order::where('id', $order->id)->delete();
    OrderItem::where('id_order', $order->id)->delete();
    OrderTrack::where('id_order', $order->id)->delete();

    return redirect('/customer') -> with('pesanSukses', 'Berhasil membatalkan pesanan' );
  }

  public function dataPengajuanOpname(){
    $opnames = Order::where('id_customer', 0)->where('status_enum', '-1')->get();

    return view('supervisor.opname.pengajuanOpname', [
      'opnames' => $opnames,
    ]);
  }

  public function detailPengajuanOpname(Order $order){
    $opname = Order::where('id',$order->id)->with(['linkOrderItem'])->first();

    return view('supervisor.opname.detailPengajuanOpname',[
      'opname' => $opname
    ]);
  }

  public function konfirmasiPengajuanOpname(Order $order){
    $opnameItems = OrderItem::where('id_order', $order->id)->get();

    foreach($opnameItems as $item){
      $barang = Item::find($item->id_item);
      $barang->stok +=  $item->kuantitas;
      $barang->save();
    }

    Order::find($order->id)->update([
      'status_enum' => '1'
    ]);
    return redirect('/supervisor/stokopname') -> with('pesanSukses', 'Berhasil mengonfirmasi pengajuan stok opname');
  }

  public function tolakPengajuanOpname(Order $order){
    Order::find($order->id)->update([
      'status_enum' => '1'
    ]);
    return redirect('/supervisor/stokopname') -> with('pesanSukses', 'Berhasil menolak pengajuan stok opname');
  }

  public function getInvoiceAPI(Request $request){
    $id_staff = $request->id_staff;
    $request->dateStart = $request->dateStart." 00:00:00";
    $request->dateEnd = $request->dateEnd." 23:59:59";

    $datas = Invoice::whereBetween('created_at', [$request->dateStart, $request->dateEnd])
    ->whereHas('linkOrder', function($q) use($id_staff) {
        $q->where('id_staff', $id_staff);
      })
    ->with(['linkOrder'])
    ->get();

    return response()->json([
      'data' => $datas,
      'status' => 'success'
    ]);
  }

  public function inputPembayaran(order $order){
    $stafs = Staff::where('status_enum', '1')->whereIn('role', [3,4])->get();

    $metodes_pembayaran = [
      1 => 'tunai',
      2 => 'giro',
      3 => 'dicicil',
    ];

    return view('administrasi.pesanan.pembayaran.index',[
      'order' => $order,
      'stafs' => $stafs,
      'metodes_pembayaran' => $metodes_pembayaran,
    ]);
  }

  public function konfirmasiPembayaran(Request $request, order $order){
     $rules = [
        'id_invoice' => ['required'],
        'id_staff_penagih' => ['required'],
        'tanggal' => ['required'],
        'jumlah_pembayaran' => ['required'],
        'metode_pembayaran' => ['required']
      ];
      
      $validatedData = $request->validate($rules);
      $validatedData['created_at'] = now();
      Pembayaran::insert($validatedData);
      $invoice = Invoice::where('id_order','=',$order->id)->first();

      $total_bayar = Invoice::where('invoices.id', $invoice->id)
      ->join('pembayarans','invoices.id','=','pembayarans.id_invoice')
      ->whereHas('linkOrder', function($q) {
        $q->whereHas('linkOrderTrack', function($q) {
          $q->whereIn('status_enum',['4','5','6']);
        });
      })
      ->select('pembayarans.id_invoice', \DB::raw('SUM(pembayarans.jumlah_pembayaran) as total_bayar'))
      ->groupBy('pembayarans.id_invoice')->get()->sum('total_bayar');
      
      if($total_bayar==null){
        $total_bayar = 0;
      }
      
      if($total_bayar >= $invoice->harga_total){
        OrderTrack::where('id_order', $order->id)->update([
          'status_enum' => '5'
        ]);
      }
      
      return redirect('/administrasi/pesanan/detail/'.$order->id) -> with('addPesananSuccess', 'Berhasil mengonfirmasi pembayaran untuk '.$order->linkCustomer->nama);
  }
}

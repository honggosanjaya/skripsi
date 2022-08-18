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
use App\Models\Status;
use App\Models\History;

use PDF;
use Illuminate\Support\Facades\Validator;

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
          'status' => 15,
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
          'status' => 20,
          'waktu_order'=> now(),
          'created_at'=> now(),
          'waktu_diteruskan' => now(),
          'estimasi_waktu_pengiriman' => $estimasiWaktuPengiriman,
        ]);
        OrderItem::insert($data);
        Customer::find($id_customer) -> update([
          'tipe_retur' => $tipeRetur
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
          'status' => 20,
          'estimasi_waktu_pengiriman' => $estimasiWaktuPengiriman,
        ]);
        Customer::find($id_customer) -> update([
          'tipe_retur' => $tipeRetur
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
      'status' => 1,
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
      'status' => 2,
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
    $statuses = Status::where('tabel','=','order_tracks')->get();

    return view('administrasi.pesanan.index',[
      'orders' => $orders,                      
      'statuses' => $statuses
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
    
    return view('administrasi.pesanan.detailpesanan',[
      'order' => $order,
      'items' => $items
    ]);
  }

  public function viewKapasitas(Order $order){
    $kendaraans = Vehicle::get();
    $invoice = Invoice::where('id_order','=',$order->id)->first();
    $tempVolume = array();
    $tempPersentaseVolume = array();
    $tempPersentaseHarga = array();
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

    for($j=0; $j<$kendaraans->count();$j++){
      array_push($tempPersentaseVolume, 
      [$kendaraans[$j]->nama,$kendaraans[$j]->kode_kendaraan,(($totalVolume/$kendaraans[$j]->kapasitas_volume)*100)]);
      
      array_push($tempPersentaseHarga, 
      [$kendaraans[$j]->nama,$kendaraans[$j]->kode_kendaraan,(($invoice->harga_total/$kendaraans[$j]->kapasitas_harga)*100)]);
    }
    
    return view('administrasi.pesanan.kapasitaskendaraan',[
        'order' => $order,
        'persentaseVolumes' => $tempPersentaseVolume,
        'persentaseHargas' => $tempPersentaseHarga
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
        'status' => 15,
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
        'status' => 19,
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
              $q->where('status', 22);
            })
            ->orWhereHas('linkOrderTrack',function($q) {
              $q->where('status','>', 22)->where('status','<', 25)->whereBetween('waktu_sampai',[now()->subDays(2),now()]);
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
        $q->where('status', 22);
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

  public function setujuPesanan(Order $order){
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
      // for($i=0; $i<sizeof($itemYangKurang);$i++){
      //   echo $itemYangKurang[$i].', ';
      // }
      return redirect('/administrasi/pesanan/detail/'.$order->id) 
      -> with('pesanError', 'Tidak dapat menyetujui pesanan jumlah stok kurang');
    }

    if($i == $jumlahItem){
      // if($order->status == 15){
      //   $order->update([
      //     'status' => 14,
      //   ]);
      // }

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
        'status' => 14
      ]);

      OrderTrack::where('id_order', $order->id)->update([
        'id_staff_pengonfirmasi' => auth()->user()->id_users,
        'status' => 21,
        'waktu_dikonfirmasi' => now()
      ]);
      
      return redirect('/administrasi/pesanan/detail/'.$order->id) -> with('addPesananSuccess', 'Berhasil menyetujui pesanan');
    } 
  }

  public function tolakPesanan(Order $order){
    $order = Order::find($order->id);

    OrderTrack::where('id_order', $order->id)->update([
      'status' => 25,
      'waktu_dikonfirmasi'=> now()
    ]);


    return redirect('/administrasi/pesanan/detail/'.$order->id) -> with('addPesananSuccess', 'Berhasil menolak pesanan');
  }

  public function viewPengiriman(order $order){
    $orderItems = OrderItem::where('id_order', $order->id)->get();
    $stafs = Staff::where('status', 8)->where('role', 4)->get();

    $kapasitas_harga = 0;
    $kapasitas_volume = 0;
    foreach($orderItems as $orderItem){
      $item = Item::where('id', $orderItem->id_item)->first();
      $kapasitas_harga += $orderItem->harga_satuan * $orderItem->kuantitas;
      $kapasitas_volume += $item->volume * $orderItem->kuantitas;
    };

    $vehicles = Vehicle::where('kapasitas_volume', '>=', $kapasitas_volume)
                ->orWhere('kapasitas_harga', '>=', $kapasitas_harga)->get();

    return view('administrasi.pesanan.pengiriman.keberangkatan',[
      'order' => $order,
      'stafs' => $stafs,
      'vehicles' => $vehicles
    ]);
  }

  public function konfirmasiPengiriman(Request $request, order $order){
    if($request && $order->linkOrderTrack->status == 21){
      $rules = [
        'id_staff_pengirim' => ['required'],
        'id_vehicle' => ['required']
      ];
      $validatedData = $request->validate($rules);
      $validatedData['status'] = 22;
      $validatedData['waktu_berangkat'] = now();
      OrderTrack::where('id_order', $order->id)->update($validatedData);        
      return redirect('/administrasi/pesanan/detail/'.$order->id) -> with('addPesananSuccess', 'Berhasil mengonfirmasi keberangkatan pengiriman untuk '.$order->linkCustomer->nama);
    }

    if($order->linkOrderTrack->status == 22){
      $rules = [
        'foto' => 'image|file|max:1024',
      ];

      $file= $request->file('foto');
      $file_name=  'DLV-'.$order->id.'.'.$file->getClientOriginalExtension();
      $request->foto->move(public_path('storage/pengiriman'), $file_name);
      $validatedData['foto_pengiriman'] = $file_name;
      $validatedData['status'] = 23;
      $validatedData['waktu_sampai'] = now();
      OrderTrack::where('id_order', $order->id)->update($validatedData);

      return response()->json([
        'status' => 'success',
        'message' => 'Pengiriman untuk '.$order->linkCustomer->nama.' telah sampai'
      ]);
    }

    if($order->linkOrderTrack->status == 23){
      OrderTrack::where('id_order', $order->id)->update([
        'status' => 24
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
}

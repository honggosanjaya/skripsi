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
    $id_order = null;
    $id_customer = null;
    $tipeRetur = $request -> tipeRetur;

    if(sizeof($keranjangItems) > 0){
      foreach($keranjangItems as $item){
        $id_customer = $item['customer'];
        if($item['orderId'] != 'belum ada'){
          $id_order = $item['orderId'];
        }
        if($item['orderId'] == 'belum ada'){
          $id_order = 'belum ada';
        }
      }
    }

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
            'harga_satuan' => $item['harga'],
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
              'harga_satuan' => $item['harga'],
              'keterangan' => $keterangan,
            ]);
          } else {
            # create...
            OrderItem::insert([
              'id_order' => $id_order,
              'id_item' => $item['id'],
              'kuantitas' => $item['jumlah'],
              'harga_satuan' => $item['harga'],
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

    Trip::find($request->idTrip)->update([
      'waktu_keluar' => now(),
      'updated_at' => now(),
      'alasan_penolakan' => $request->alasan_penolakan
    ]);

    $kode_event = $request -> kodeEvent ?? null;
    $id_event = null;
    if ($kode_event != null) {
      $id_event = Event::where('kode', $kode_event)->first()->id;
    }

    Invoice::insert([
      'id_order' => $id_order,
      'id_event' => $id_event,
      'nomor_invoice' => (Invoice::orderBy("nomor_invoice", "DESC")->first()->nomor_invoice ?? 0) + 1,
      'harga_total' => $totalPesanan,
      'created_at' => now()
    ]);

    return response()->json([
      'status' => 'success',
      'success_message' => 'berhasil membuat pesanan'
    ]);
  }

    public function keluarTripOrderApi(Request $request, $id){ 
        Trip::find($id)->update([
          'waktu_keluar' => now(),
          'updated_at' => now(),
          'alasan_penolakan' => $request->alasan_penolakan
        ]);

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

      if (Trip::where('id_customer',$id_customer)->where('status',2)->count()==0) {
        Customer::find($id_customer)->update([
          'counter_to_effective_call' => $customer->counter_to_effective_call+1
        ]);
      }

      $tripOrder = Trip::insertGetId(
        [
          'id_customer' => $id_customer,
          'id_staff' => $id_staff,
          'alasan_penolakan' => null,
          'koordinat' => $request->koordinat,
          'waktu_masuk' => date('Y-m-d H:i:s', $request->jam_masuk),
          'waktu_keluar' => null,
          'status' => 2,
          'created_at'=> now()
        ]
      );

      $id_trip = $tripOrder;

      return response()->json([
        'status' => 'success',
        'message'=>'Trip Order berhasil dicatat',
        'data' => Trip::find($id_trip)
      ]); 
    }

    public function dataKodeCustomer($id){
      $order = Order::find($id);
      $order_item = OrderItem::where('id_order', $id)->get();

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
      $orders = Order::paginate(5);
      $statuses = Status::where('tabel','=','order_tracks')->get();

      return view('administrasi.pesanan.index',[
        'orders' => $orders,                      
        'statuses' => $statuses
      ]);
    }

    public function search(){
      $orders = Order::join('order_tracks','orders.id','=','order_tracks.id_order')
        ->join('statuses','order_tracks.status','=','statuses.id')
        ->join('invoices','orders.id','=','invoices.id_order')
        ->where(strtolower('nomor_invoice'),'like','%'.request('cari').'%')
        ->paginate(5);  
      
        $statuses = Status::where('tabel','=','order_tracks')->get();

        return view('administrasi/pesanan.index',[
            'orders' => $orders,
            'statuses' => $statuses
        ]);
    }

    public function filter(){
      if(request('status') != ""){
        $orders = Order::join('order_tracks','orders.id','=','order_tracks.id_order')
        ->join('statuses','order_tracks.status','=','statuses.id')
        ->join('invoices','orders.id','=','invoices.id_order')
        ->where('statuses.id','=',request('status'))
        ->paginate(5);
      }
      else{
        $orders = Order::join('order_tracks','orders.id','=','order_tracks.id_order')
        ->join('statuses','order_tracks.status','=','statuses.id')
        ->join('invoices','orders.id','=','invoices.id_order')        
        ->paginate(5);
      }
      
        $statuses = Status::where('tabel','=','order_tracks')       
        ->get();

        return view('administrasi/pesanan.index',[
            'orders' => $orders,            
            'statuses' => $statuses
        ]);      
    }

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

        $pdf = PDF::loadview('administrasi/pesanan/detail.cetakInvoice',[
            'order' => $order,
            'items' => $items,
            'administrasi' => $administrasi           
          ]);
  
        return $pdf->download('invoice-'.$order->linkInvoice->nomor_invoice.'.pdf');  
    }

    public function cetakSJ(Order $order){
        $items = OrderItem::where('id_order','=',$order->id)->get();
        $todayDate = date("d-m-Y");
        $orderTrack = OrderTrack::first();
                
        $mengetahui = Staff::select('nama')->where('id','=',$orderTrack->id_staff_pengonfirmasi)->first();
        $pengirim = Staff::select('nama')->where('id','=',$orderTrack->id_staff_pengirim)->first();

        $pdf = PDF::loadview('administrasi/pesanan/detail.cetakSJ',[
            'order' => $order,
            'items' => $items,
            'date' => $todayDate,
            'pengirim' => $pengirim,
            'mengetahui' => $mengetahui            
          ]);
  
        return $pdf->download('Surat Jalan-'.date("d F Y").'.pdf'); 

        // return view('administrasi/pesanan/detail.cetakSJ', [
        //     'order' => $order,
        //     'items' => $items,
        //     'date' => $todayDate,
        //     'pengirim' => $pengirim,
        //     'mengetahui' => $mengetahui            
        // ]);
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
  
        return $pdf->download('memo-'.$order->linkInvoice->nomor_invoice.'.pdf'); 
        
        // return view('administrasi/pesanan/detail.cetakMemo',[
        //     'order' => $order,
        //     'items' => $items,
        //     'date' => $todayDate,
        //     'administrasi' => $adminsitrasi
        // ]);
        
    }
    
    public function simpanDataOrderCustomer(Request $request){
        $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();

        $order_id=Order::insertGetId([
            'id_customer' => auth()->user()->id,
            'status' => 15,
            'created_at'=> now(),
        ]);

        $data = [];
        foreach($cartItems as $item){
        array_push($data,[
            'id_item' => $item->id,
            'id_order' => $order_id,
            'kuantitas' => $item->quantity,
            'harga_satuan' => $item->price,
            'keterangan' => $request->keterangan??null,
        ]);
        }

        OrderTrack::insert([
            'id_order' => $order_id,
            'status' => 19,
            'waktu_order'=> now(),
            'created_at'=> now(),
            // 'estimasi_waktu_pengiriman' => '1'
        ]);
        OrderItem::insert($data);

        \Cart::session(auth()->user()->id.$request->route)->clear();

        return redirect('/customer/produk')->with('pesanSukses', 'Produk berhasil ditambahkan ke database');
    }

    public function getListShippingAPI(Request $request){
      $id_staff=$request->id_staff;

      $data=Order::
        whereHas('linkOrderTrack',function($q) use( $id_staff) {
          $q->where('status',22)->where('id_staff_pengirim', $id_staff);
        })
        ->with(['linkOrderTrack','linkInvoice','linkCustomer','linkOrderItem'])->get();

      // dd($data);
     
      return response()->json([
        'data' => $data,
        'status' => 'success'
      ]);
    }

    public function getDetailShippingAPI(Request $request){
      $data=Order::with(['linkOrderTrack','linkInvoice','linkCustomer','linkOrderItem.linkItem'])->find($request->id);
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
        if($order->status == 15){
          $order->update([
            'status' => 14,
          ]);
        }

        foreach($orderItems as $orderItem){
          $item = Item::find($orderItem->id_item);
          $totalHarga = $totalHarga + ($orderItem->kuantitas * $item->harga_satuan);
          $item->stok -= $orderItem->kuantitas;
          $item->save();

          $stokMaksimalTerakhir = History::where("id_item", $item->id)->orderBy("id", "DESC")->first()->stok_maksimal_customer ?? 0;
          $stokSekarang = (History::where("id_item", $item->id)->orderBy("id", "DESC")->first()->stok_terakhir_customer ?? 0) + $orderItem->kuantitas;

          if($stokSekarang > $stokMaksimalTerakhir){
            History::insert([
              'id_customer' => $order->id_customer,
              'id_item' => $item->id,
              'stok_maksimal_customer' => $stokSekarang,
              'stok_terakhir_customer' => $stokSekarang,
            ]);
          }else{
            History::insert([
              'id_customer' => $order->id_customer,
              'id_item' => $item->id,
              'stok_maksimal_customer' => $stokMaksimalTerakhir,
              'stok_terakhir_customer' => $stokSekarang,
            ]);
          }
        }

        Invoice::where('id_order', $order->id)->update([
          'updated_at' => now()
        ]);

        OrderTrack::where('id_order', $order->id)->update([
          'id_staff_pengonfirmasi' => auth()->user()->id,
          'status' => 21,
          'waktu_dikonfirmasi' => now()
        ]);
        
        return redirect('/administrasi/pesanan/detail/'.$order->id) -> with('addPesananSuccess', 'Berhasil menyetujui pesanan');
      } 
    }

    public function tolakPesanan(Order $order){
      $order = Order::find($order->id);

      OrderTrack::where('id_order', $order->id)->update([
        'status' => 25
      ]);

      Invoice::where('id_order', $order->id)->delete();
        
      return redirect('/administrasi/pesanan/detail/'.$order->id) -> with('addPesananSuccess', 'Berhasil menolak pesanan');
    }

    public function viewPengiriman(order $order){
      $orderItems = OrderItem::where('id_order', $order->id)->get();
      $stafs = Staff::where('status', 8)->where('role', 4)->get();

      $kapasitas_harga = 0;
      $kapasitas_volume = 0;
      foreach($orderItems as $orderItem){
        $item = Item::where('id', $orderItem->id_item)->first();
        $kapasitas_harga += $item->harga_satuan * $orderItem->kuantitas;
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

        $file_name = time() . '.' . $request->foto->extension();
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
}

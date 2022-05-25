<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\OrderItem;
use PDF;

class OrderController extends Controller
{

    public function simpanDataOrderSalesmanAPI(Request $request)
    {
        $keranjangItems = $request->keranjang;
        $idStaf = $request->idStaf;

        $id_order=null;
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
          $order_id=Order::insertGetId([
            'id_customer' => $id_customer,
            'id_staff' => $idStaf,
            'status' => 15,
            'created_at'=> now(),
          ]);
          
          $data = [];
          foreach($keranjangItems as $item){
            array_push($data,[
              'id_order' => $order_id,
              'id_item' => $item['id'],
              'kuantitas' => $item['jumlah'],
              'harga_satuan' => $item['harga'],
              'keterangan' => $request->keterangan??null,
            ]);
          }
  
          OrderTrack::insert([
            'id_order' => $order_id,
            'status' => 20,
            'waktu_order'=> now(),
            'created_at'=> now(),
            'waktu_diteruskan' => now(),
            'estimasi_waktu_pengiriman' => '1'
          ]);
          OrderItem::insert($data);
        } 
        else{
          //order item sudah ada jadi tinggal update      
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
                'keterangan' => null,
              ]);
            } else {
              # create...
              OrderItem::insert([
                'id_order' => $id_order,
                'id_item' => $item['id'],
                'kuantitas' => $item['jumlah'],
                'harga_satuan' => $item['harga'],
                'keterangan' => null,
              ]);
            } 
          }

          $orderItems=OrderItem::where('id_order', $id_order)->get();
          foreach($orderItems as $orderItem){
            // cari $orderItem->id_item ada tidak di id item di cart 
            // jika tidak ada jalankan if
            $key = array_search($orderItem->id_item, array_column($keranjangItems, 'id'));
              if($key === false){
                # delete...
                $orderItem->delete();
              }
          }
          
          OrderTrack::where('id_order', $id_order)->update('waktu_diteruskan', now());          
        }

                  // Trip::create(
          //   [
          //     'id_customer' => $id,
          //     'id_staff' => session('id_staff') ,
          //     'koordinat' => $request->koordinat,
          //     'waktu_masuk' => date('Y-m-d H:i:s', $request->jam_masuk),
          //     'waktu_keluar' => now(),
          //     'status' => $status,
          //     'created_at'=> now()
          //   ]
          // );
        
        

          return response()->json([
            'status' => 'success',
            'success_message' => 'berhasil membuat pesanan'
          ]);
    }

    public function dataKodeCustomer($id){
      $order=Order::find($id);

      $order_item = OrderItem::where('id_order', $id)->get();

      return response()->json([
        'status' => 'success',
        'dataOrder' => $order,
        'dataOrderItem' => $order_item,
        'message' => 'berhasil memasukkan produk dalam keranjang'
      ]);
    }

    public function keluarToko(){
      
    }

    public function index(){
        $orders = Order::paginate(5);
        return view('administrasi/pesanan.index',[
            'orders' => $orders
        ]);
    }

    public function search(){
        $orders = Order::where(strtolower('nomor_invoice'),'like','%'.request('cari').'%')->paginate(5);
               
        return view('administrasi/pesanan.index',[
            'orders' => $orders
        ]);
    }

    public function viewDetail(Order $order){
        $items = OrderItem::where('id_order','=',$order->id)->get();
        
        return view('administrasi/pesanan.detailpesanan',[
            'order' => $order,
            'items' => $items
        ]);
    }

    public function cetakInvoice(Order $order){
        $items = OrderItem::where('id_order','=',$order->id)->get();

        $pdf = PDF::loadview('administrasi/pesanan/detail.cetakInvoice',[
            'order' => $order,
            'items' => $items           
          ]);
  
        return $pdf->download('invoice-'.$order->linkInvoice->nomor_invoice.'.pdf');  
        
        // return view('administrasi/pesanan/detail.cetakInvoice',[
        //     'order' => $order,
        //     'items' => $items
        // ]);
    }

    
    public function simpanDataOrderCustomer(Request $request)
    {
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
}

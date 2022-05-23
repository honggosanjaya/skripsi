<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\OrderItem;
use App\Models\Staff;
use PDF;

class OrderController extends Controller
{

  public function simpanDataOrderSalesmanAPI(Request $request)
    {
      $keranjangItems = $request->keranjang;

      if(sizeof($keranjangItems) > 0){
        foreach($keranjangItems as $item){
          $id_customer = $item['customer'];
        }
      }
      
      
      $order_id=Order::insertGetId([
        'id_customer' => $id_customer,
        'id_staff' => auth()->user()->id,
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
            'estimasi_waktu_pengiriman' => '1'
        ]);
        OrderItem::insert($data);


        return response()->json([
          'status' => 'success',
          'success_message' => 'berhasil membuat pesanan'
        ]);
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
  
        return $pdf->download('SJ-'.date("d-m-Y").'.pdf'); 

        // return view('administrasi/pesanan/detail.cetakSJ', [
        //     'order' => $order,
        //     'items' => $items,
        //     'date' => $todayDate,
        //     'pengirim' => $pengirim,
        //     'mengetahui' => $mengetahui            
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
            'status' => 15,
            'waktu_order'=> now(),
            'created_at'=> now(),
            // 'estimasi_waktu_pengiriman' => '1'
        ]);
        OrderItem::insert($data);

        \Cart::session(auth()->user()->id.$request->route)->clear();

        return redirect('/customer/produk')->with('pesanSukses', 'Produk berhasil ditambahkan ke database');
    }
}

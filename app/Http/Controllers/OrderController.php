<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\OrderItem;
use PDF;

class OrderController extends Controller
{
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
            'status' => 15,
            'waktu_order'=> now(),
            'created_at'=> now(),
        ]);
        OrderItem::insert($data);

        \Cart::session(auth()->user()->id.$request->route)->clear();

        return redirect('/customer/produk')->with('pesanSukses', 'Produk berhasil ditambahkan ke database');
    }
}

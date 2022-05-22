<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTrack;
use App\Models\Staff;
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
}

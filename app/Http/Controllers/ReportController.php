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

class ReportController extends Controller
{
    public function penjualan(){
        $data = 
        Order::whereHas('linkOrderTrack',function($q) {
            $q->whereIn('status', [23,24]);
        })->with(['linkOrderTrack.linkStatus','linkInvoice','linkInvoice', 'linkStaff', 'linkCustomer.linkCustomerType']);
        if(request()->salesman){
            $data = $data->where('id_staff',request()->salesman);
        }
        if(request()->daterange){
            $data = $data->whereBetween('created_at',[request()->daterange->start,request()->daterange->end]);
        }
        $data = $data->paginate(10);

        dd($data);

        return view('supervisor.report.penjualan',compact('data'));
    }

    public function index(){
        $item =OrderItem::
        whereHas('linkOrder',function($q) {
            $q->whereHas('linkOrderTrack',function($q) {
                $q->whereIn('status', [23,24]);
            });
        })
        ->select('id_item', \DB::raw('SUM(kuantitas) as total'))
        ->groupBy('id_item');

        $item['produk_laris'] = $item->orderBy('total', 'DESC')->groupBy('total')->take(5)->pluck('total');

        $item['produk_slow'] = $item->orderBy('total', 'ASC')->groupBy('total')->take(5)->pluck('total');

        $data['produk_laris'] = $item->whereIn('total',  $item['produk_laris'])->get();

        $data['produk_slow'] = $item->whereIn('total',  $item['produk_slow'])->get();
        
        $data['omzet'] = Invoice::whereHas('linkOrder',function($q) {
            $q->whereHas('linkOrderTrack',function($q) {
                $q->whereIn('status', [23,24]);
            });
        })->whereBetween('created_at',[request()->daterange->start,request()->daterange->end])
        ->select(\DB::raw('SUM(harga_total) as total'))->first();

        $data['pembelian'] = Pengadaan::whereBetween('created_at',[request()->daterange->start,request()->daterange->end])
        ->select(\DB::raw('SUM(harga_total) as total'))->first();

        dd($data);
        return view('supervisor.report.penjualan',compact('data'));
    }
    public function kinerja(){
        // $data['mostSellItem']=Item::get();
        // dd($data);
        return view('supervisor.report.kinerja');
    }
}

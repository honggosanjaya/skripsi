<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\OrderItem;
use App\Models\Pengadaan;
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

    public function index(Request $request){
        if (!$request->dateStart??null) {
            request()->request->add(['dateStart'=>date('Y-m-01', strtotime("-1 months"))]);  
            request()->request->add(['dateEnd'=>date('Y-m-t', strtotime("-1 months"))]);  
            request()->request->add(['dateStart'=>date('Y-m-01')]);  
            request()->request->add(['dateEnd'=>date('Y-m-t')]);  
        }

        $item =OrderItem::
            whereHas('linkOrder',function($q) use($request){
                $q->whereHas('linkOrderTrack',function($q) use($request) {
                    $q->whereIn('status', [23,24])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
                });
            })
            ->whereHas('linkItem',function($q) use($request){
                $q->where('status',10);
            })
            ->select('id_item', \DB::raw('SUM(kuantitas) as total'))
            ->groupBy('id_item')->with('linkItem');

        $data=[];
        $data['produk_laris'] = $item->orderBy('total', 'DESC')->take(10)->get();
        $item =OrderItem::
            whereHas('linkOrder',function($q) use($request){
                $q->whereHas('linkOrderTrack',function($q) use($request) {
                    $q->whereIn('status', [23,24])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
                });
            })
            ->whereHas('linkItem',function($q) use($request){
                $q->where('status',10);
            })
            ->select('id_item', \DB::raw('SUM(kuantitas) as total'))
            ->groupBy('id_item')->with('linkItem');


        $data['produk_tidak_terjual'] = $item->pluck('id_item')->toArray();
        $data['produk_tidak_terjual'] = Item::where('status',10)->whereNotIn('id',$data['produk_tidak_terjual'])->get();

        $data['produk_slow'] = array_keys($item->orderBy('total', 'ASC')->get()->groupBy('total')->take(5)->toArray());

        $data['produk_slow'] = $item->orderBy('total', 'ASC')->get()->whereIn('total',  $data['produk_slow']);


        
        $data['omzet'] = Invoice::whereHas('linkOrder',function($q) use($request) {
            $q->whereHas('linkOrderTrack',function($q) use($request) {
                $q->whereIn('status', [23,24]);
            });
        })->whereBetween('created_at',[$request->dateStart,$request->dateEnd])
        ->select(\DB::raw('SUM(harga_total) as total'))->first();

        
        $data['pembelian'] = Pengadaan::whereBetween('created_at',[$request->dateStart,$request->dateEnd])
        ->select(\DB::raw('SUM(harga_total) as total'))->first();

        // dd($data);

        return view('owner.dashboard',compact('data'));
    }
    public function kinerja(){
        // $data['mostSellItem']=Item::get();
        // dd($data);
    return view('supervisor.report.kinerja');
    }
}

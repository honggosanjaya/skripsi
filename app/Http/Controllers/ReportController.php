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
    public function penjualan(Request $request){
        if (!$request->dateStart??null) {
            // request()->request->add(['dateStart'=>date('Y-m-01', strtotime("-1 months"))]);  
            // request()->request->add(['dateEnd'=>date('Y-m-t', strtotime("-1 months"))]);  
            request()->request->add(['dateStart'=>date('Y-m-01')]);  
            request()->request->add(['dateEnd'=>date('Y-m-t')]);  
        }
        $input=[
            'dateStart'=>$request->dateStart??null,
            'dateEnd'=>$request->dateEnd??null,
            'year'=>date('Y', strtotime($request->dateEnd??null)),
            'month'=>date('m', strtotime($request->dateEnd??null)),
            'salesman'=>$request->salesman??null,
        ];
        $data = 
        Order::whereHas('linkOrderTrack',function($q) {
            $q->whereIn('status', [23,24]);
        })->with(['linkOrderTrack.linkStatus','linkInvoice', 'linkStaff', 'linkCustomer.linkCustomerType']);
        if($request->salesman??null){
            $data = $data->whereHas('linkStaff',function($q) use($request){
                $q->where('nama', $request->salesman);
            });
        }
        if($request->dateStart??null){
            $data = $data->whereBetween('created_at',[$request->dateStart,$request->dateEnd]);
        }
        
        $data = $data->paginate(10);

        // dd($data);

        return view('supervisor.report.penjualan',compact('data','input'));
    }

    public function index(Request $request){
        if (!$request->dateStart??null) {
            // request()->request->add(['dateStart'=>date('Y-m-01', strtotime("-1 months"))]);  
            // request()->request->add(['dateEnd'=>date('Y-m-t', strtotime("-1 months"))]);  
            request()->request->add(['dateStart'=>date('Y-m-01')]);  
            request()->request->add(['dateEnd'=>date('Y-m-t')]);  
        }
        $input=[
            'dateStart'=>$request->dateStart,
            'dateEnd'=>$request->dateEnd,
            'year'=>date('Y', strtotime($request->dateEnd)),
            'month'=>date('m', strtotime($request->dateEnd)),
        ];

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

        $customersPengajuanLimit = Customer::where('status_limit_pembelian', 7)->get();

        return view('owner.dashboard',[
          'data' => $data,
          'input' => $input,
          'customersPengajuanLimit' => $customersPengajuanLimit,
        ])->with('datadua', [
          'lihat_notif_spv' => true
        ]);
    }

    
    public function kinerja(Request $request){
        if (!$request->dateStart??null) {
            // request()->request->add(['dateStart'=>date('Y-m-01', strtotime("-1 months"))]);  
            // request()->request->add(['dateEnd'=>date('Y-m-t', strtotime("-1 months"))]);  
            request()->request->add(['dateStart'=>date('Y-m-01')]);  
            request()->request->add(['dateEnd'=>date('Y-m-t')]);  
        }
        $input=[
            'dateStart'=>$request->dateStart,
            'dateEnd'=>$request->dateEnd,
            'year'=>date('Y', strtotime($request->dateEnd)),
            'month'=>date('m', strtotime($request->dateEnd)),
        ];

        $staffs =Staff::
            whereHas('linkOrder',function($q) use($request){
                $q->whereHas('linkOrderTrack',function($q) use($request) {
                    $q->whereIn('status', [23,24])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
                });
            })
            ->where('status',8)
            ->with(['linktrip','linkTripEc','linkTripEcF'=>function($q) use($request){
                $q->whereHas('linkCustomer',function($q) use($request) {
                    $q->whereBetween('time_to_effective_call', [$request->dateStart, $request->dateEnd]);
                });}
            ,'linkOrder'=>function($q) use($request){
                $q->whereHas('linkOrderTrack',function($q) use($request) {
                    $q->whereIn('status', [23,24])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
                })->join('invoices','orders.id','=','invoices.id_order')->select('id_staff', \DB::raw('SUM(harga_total) as total'))
                ->groupBy('id_staff')->pluck('total','id_staff');
            }])->get();

        //cadangan buat rule linkorder punya $sales
        // $omset= Order::whereHas('linkOrderTrack',function($q) use($request) {
        //     $q->whereIn('status', [23,24])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
        // })->join('invoices','orders.id','=','invoices.id_order')->select('id_staff', \DB::raw('SUM(harga_total) as total'))
        // ->groupBy('id_staff')->pluck('total','id_staff');
        // dd($staffs);
    return view('supervisor.report.kinerja',compact('staffs','input'));
    }
}

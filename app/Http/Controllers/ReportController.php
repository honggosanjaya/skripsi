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
use App\Models\Retur;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Vehicle;
use App\Models\History;

class ReportController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth', 'verified']);
  }

    public function penjualan(Request $request){
      if (!$request->dateStart??null) {
        request()->request->add(['dateStart'=>date('Y-m-01')]);  
        request()->request->add(['dateEnd'=>date('Y-m-t')]); 
      }
      $input=[
        'dateStart'=>$request->dateStart ?? null,
        'dateEnd'=>$request->dateEnd ?? null,
        'year'=>date('Y', strtotime($request->dateEnd ?? null)),
        'month'=>date('m', strtotime($request->dateEnd ?? null)),
        'salesman'=>$request->salesman ?? null,
      ];
      $request->dateStart=$request->dateStart." 00:00:00";
      $request->dateEnd=$request->dateEnd." 23:59:59";

      $data = Order::whereHas('linkOrderTrack',function($q) use($request){
                $q->whereIn('status_enum', ['4', '5', '6'])->whereBetween('waktu_sampai',[$request->dateStart,$request->dateEnd]);
              })
              ->orderBy('id', 'DESC')
              ->with(['linkInvoice', 'linkStaff', 'linkCustomer.linkCustomerType', 'linkInvoice.linkEvent']);
      if($request->salesman??null){
        $data = $data->whereHas('linkStaff',function($q) use($request){
          $q->where('nama', $request->salesman);
        });
      }
      
      $data = $data->paginate(10);

      $invoicesSampai = Invoice::whereHas('linkOrder',function($q){
        $q->whereHas('linkOrderTrack', function($q){
          $q->where('status_enum','4');
        });
      })->get();

      $invoiceJatuhTempo = [];

      foreach($invoicesSampai as $invoice){
        if($invoice->jatuh_tempo != null){
          $waktuSampai = $invoice->linkOrder->linkOrderTrack->waktu_sampai;
          $tanggalSampai = date("Y-m-d",strtotime($waktuSampai));
          $tanggalSampai2 = date_create($tanggalSampai);

          $interval = date_add($tanggalSampai2, date_interval_create_from_date_string($invoice->jatuh_tempo . " days"));
          $tanggalJatuhTempo = date_format($interval,"Y-m-d");

          array_push($invoiceJatuhTempo, [
            'id_invoice' => $invoice->id,
            'tanggalJatuhTempo' => $tanggalJatuhTempo
          ]);
        }
      }

      return view('supervisor.report.penjualan',compact('data','input','invoiceJatuhTempo'));
    }

    public function index(Request $request){
      if (!$request->dateStart??null) {
        request()->request->add(['dateStart'=>date('Y-m-01')]);  
        request()->request->add(['dateEnd'=>date('Y-m-t')]);  
      }
      $input=[
        'dateStart'=>$request->dateStart,
        'dateEnd'=>$request->dateEnd,
        'year'=>date('Y', strtotime($request->dateEnd)),
        'month'=>date('m', strtotime($request->dateEnd)),
        'count'=>$request->count??5
      ];
      $request->dateStart=$request->dateStart." 00:00:00";
      $request->dateEnd=$request->dateEnd." 23:59:59";

      $item =OrderItem::
          whereHas('linkOrder', function($q) use($request){
              $q->whereHas('linkOrderTrack',function($q) use($request) {
                  $q->whereIn('status_enum', ['4','5'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
              });
          })
          ->whereHas('linkItem',function($q) use($request){
              $q->where('status_enum','1');
          })
          ->select('id_item', \DB::raw('SUM(kuantitas) as total'))
      ->groupBy('id_item')->with('linkItem');


        // Perhitungan Uang Retur 
        $returInvoices = Retur::where('status_enum', '1')->where('tipe_retur', 1)
        ->whereHas('linkInvoice', function($q) use($request){
          $q->whereHas('linkOrder', function($q) use($request){
            $q->whereHas('linkOrderTrack', function($q) use($request){
              $q->whereIn('status_enum', ['4','5'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
            });
          });
        })
        ->select('id_item', \DB::raw('SUM(kuantitas*harga_satuan) as total_retur'))
        ->groupBy('id_item')->get()->sum('total_retur');

        $data=[];
        $data['produk_laris'] = $item->orderBy('total', 'DESC')->take(10)->get();
        $item =OrderItem::
          whereHas('linkOrder',function($q) use($request){
              $q->whereHas('linkOrderTrack',function($q) use($request) {
                  $q->whereIn('status_enum', ['4','5'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
              });
          })
          ->whereHas('linkItem',function($q) use($request){
              $q->where('status_enum','1');
          })
          ->select('id_item', \DB::raw('SUM(kuantitas) as total'))
          ->groupBy('id_item')->with('linkItem');


        $data['produk_tidak_terjual'] = $item->pluck('id_item')->toArray();
        $data['produk_tidak_terjual'] = Item::where('status_enum','1')->whereNotIn('id',$data['produk_tidak_terjual'])->get();

        //produk jual dikit
        // $data['produk_slow'] = array_keys($item->orderBy('total', 'ASC')->get()->groupBy('total')->take(5)->toArray());

        // $data['produk_slow'] = $item->orderBy('total', 'ASC')->get()->whereIn('total',  $data['produk_slow']);
        
        $data['omzet'] = Invoice::whereHas('linkOrder',function($q) use($request) {
          $q->whereHas('linkOrderTrack',function($q) use($request) {
              $q->whereIn('status_enum', ['4', '5'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
          });
        })->select(\DB::raw('SUM(harga_total) as total'))->first();

        
        // $data['pembelian'] = Pengadaan::whereBetween('created_at',[$request->dateStart,$request->dateEnd])
        // ->select(\DB::raw('SUM(harga_total) as total'))->first();

        $data['pembelian'] = Pengadaan::
        // whereBetween('created_at',[$request->dateStart,$request->dateEnd])->
        select('id_item', \DB::raw('SUM(harga_total)/SUM(kuantitas) as harga_item'))
        ->groupBy('id_item');

        $data['pembelian'] = OrderItem::
          whereHas('linkOrder',function($q) use($request){
            $q->whereHas('linkOrderTrack',function($q) use($request) {
                $q->whereIn('status_enum', ['4','5'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
            });
          })
          ->whereHas('linkItem',function($q) use($request){
            $q->where('status_enum','1');
          })
          ->joinSub($data['pembelian'], 'harga_beli', function ($join) {
            $join->on('order_items.id_item', '=', 'harga_beli.id_item');
          })
          ->select('order_items.id_item', \DB::raw('SUM(kuantitas*harga_item) as total_price'))
          ->groupBy('id_item')
          // ->select(\DB::raw('SUM(total_price) as total'))
          ->get()
          ->sum('total_price');

        $data['retur'] = Retur::whereBetween('created_at', [$request->dateStart, $request->dateEnd])->where('status_enum','1')
          ->select('id_item', \DB::raw('SUM(kuantitas*harga_satuan) as total_price'))
          ->groupBy('id_item')->get()->sum('total_price');
            

        $data['produk_slow'] = OrderItem::
          whereHas('linkOrder',function($q) use($request){
              $q->whereHas('linkOrderTrack',function($q) use($request) {
                  $q->whereIn('status_enum', ['4','5'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
              });
          })
          ->whereHas('linkItem',function($q) use($request){
              $q->where('status_enum','1');
          })
          ->select('id_item', \DB::raw('SUM(kuantitas) as total'), \DB::raw('count(*) as count'))
        ->groupBy('id_item')->with('linkItem')->orderBy('count', 'ASC')->take($request->count??5)->get();


        $data['pp'] = OrderItem::
          whereHas('linkOrder',function($q) use($request){
              $q->whereHas('linkOrderTrack',function($q) use($request) {
                  $q->whereIn('status_enum', ['4','5'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
              });
          })
          ->whereHas('linkItem',function($q) use($request){
              $q->where('status_enum','1');
          })
          ->select('order_items.id_item', \DB::raw('SUM(kuantitas*harga_satuan) as total_price'))
          ->groupBy('id_item')
          ->get()
          ->sum('total_price');

        $data['rtrd']=Retur::whereBetween('created_at', [$request->dateStart, $request->dateEnd])->where('status_enum','1')
            ->select('id_invoice', \DB::raw('SUM(kuantitas*harga_satuan) as total_price'))
            ->groupBy('id_invoice')->with('linkInvoice')->get()->pluck('total_price','linkInvoice.id_order')->toArray();
        
        $data['ppd'] = OrderItem::
          whereHas('linkOrder',function($q) use($request){
              $q->whereHas('linkOrderTrack',function($q) use($request) {
                  $q->whereIn('status_enum', ['4','5'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
              });
          })
          ->whereHas('linkItem',function($q) use($request){
              $q->where('status_enum','1');
          })
          ->select('id_order', \DB::raw('SUM(kuantitas*harga_satuan) as total_price'))
          ->groupBy('id_order')
          ->get()->pluck('total_price','id_order')->toArray();

        $data['ppd-rtrd'] = [];
        foreach ($data['ppd'] as $a => $val){
          array_push( $data['ppd-rtrd'],[$a => $val-(array_key_exists($a, $data['rtrd'])?$data['rtrd'][$a]:0)]);
        }

        $data['invd'] = Invoice::whereHas('linkOrder',function($q) use($request) {
                          $q->whereHas('linkOrderTrack',function($q) use($request) {
                              $q->whereIn('status_enum', ['4','5'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
                          });
                        })->get()->pluck('harga_total','id_order')->toArray();

        $data['totalReturInvoice'] = $returInvoices;
        //ceking aja
        // dd($data);

        $customersPengajuanLimit = Customer::where('status_limit_pembelian_enum', '0')->get();
        $stokOpnamePengajuan = Order::where('id_customer',0)->where('status_enum','-1')->get();
        $request->session()->increment('count');

        return view('owner.dashboard',[
          'data' => $data,
          'input' => $input,
          'customersPengajuanLimit' => $customersPengajuanLimit,
          'stokOpnamePengajuan' => $stokOpnamePengajuan
        ])->with('datadua', [
          'lihat_notif_spv' => true,
          'jml_pengajuan' => count($customersPengajuanLimit),
          'juml_opname' => count($stokOpnamePengajuan)
        ]);
    }
    
    public function kinerja(Request $request){
      if (!$request->dateStart??null) {
        request()->request->add(['dateStart'=>date('Y-m-01')]);  
        request()->request->add(['dateEnd'=>date('Y-m-t')]);  
      }
      $input=[
        'dateStart'=>$request->dateStart,
        'dateEnd'=>$request->dateEnd,
        'year'=>date('Y', strtotime($request->dateEnd)),
        'month'=>date('m', strtotime($request->dateEnd)),
      ];
      $request->dateStart=$request->dateStart." 00:00:00";
      $request->dateEnd=$request->dateEnd." 23:59:59";

      $staffs =Staff::
        where('status_enum','1')->where('role',3)
        ->with([
        'linkTrip'=>function($q) use($request){
              $q->whereBetween('created_at', [$request->dateStart, $request->dateEnd]);
          }
        ,'linkTripEc'=>function($q) use($request){
              $q->whereBetween('created_at', [$request->dateStart, $request->dateEnd]);
          }
        ,'linkTripEcF'=>function($q) use($request){
            $q->whereBetween('created_at', [$request->dateStart, $request->dateEnd])
            ->whereHas('linkCustomer',function($q) use($request) {
                $q->whereBetween('time_to_effective_call', [$request->dateStart, $request->dateEnd]);
            });}
        ,'linkOrder'=>function($q) use($request){
            $q->whereBetween('invoices.created_at', [$request->dateStart, $request->dateEnd])
            ->join('invoices','orders.id','=','invoices.id_order')->select('id_staff', \DB::raw('SUM(harga_total) as total'))
            ->groupBy('id_staff')->pluck('total','id_staff');
          }])
        ->get();

      $customer_baru= Customer::whereBetween('time_to_effective_call', [$request->dateStart, $request->dateEnd])->where('status_enum','1')->select('id_staff', \DB::raw('count(*) as count'))
      ->groupBy('id_staff')->get()->pluck('count','id_staff')->toArray();

      //cadangan buat rule linkorder punya $sales
      // $omset= Order::whereHas('linkOrderTrack',function($q) use($request) {
      //     $q->whereIn('status', [23,24])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
      // })->join('invoices','orders.id','=','invoices.id_order')->select('id_staff', \DB::raw('SUM(harga_total) as total'))
      // ->groupBy('id_staff')->pluck('total','id_staff');

      return view('supervisor.report.kinerja',compact('staffs','input','customer_baru'));
    }

    public function panduanPelaporan(){
      return view('supervisor.panduan.index');
    }

    public function koordinattrip(Request $request){
      if (!$request->dateStart??null) {
        request()->request->add(['dateStart'=>date('Y-m-01')]);  
        request()->request->add(['dateEnd'=>date('Y-m-t')]);  
      }

      $input=[
        'dateStart'=>$request->dateStart ?? null,
        'dateEnd'=>$request->dateEnd ?? null,
        'year'=>date('Y', strtotime($request->dateEnd ?? null)),
        'month'=>date('m', strtotime($request->dateEnd ?? null)),
        'salesman'=>$request->salesman ?? null,
      ];

      $request->dateStart=$request->dateStart." 00:00:00";
      $request->dateEnd=$request->dateEnd." 23:59:59";

      $data = Trip::whereBetween('waktu_masuk',[$request->dateStart,$request->dateEnd])
              ->orderBy('id', 'DESC')
              ->with(['linkCustomer', 'linkStaff']);

      if($request->salesman??null){
        $data = $data->whereHas('linkStaff',function($q) use($request){
          $q->where('nama', $request->salesman);
        });
      }

      $data = $data->paginate(10);

      return view('supervisor.report.koordinattrip',compact('data','input'));
    }

    public function cekKoordinatTrip(Trip $trip){
      $koordinatCustomer = Customer::where('id', $trip->id_customer)->first()->koordinat;      
      $koordinatTrip = $trip->koordinat;

      $customers = Customer::all();

      return view('supervisor.report.cekkoordinattrip', [
        'trip' => $trip,
        'datas' => [
          'koordinatCustomer' => $koordinatCustomer,
          'koordinatTrip' => $koordinatTrip
        ],
        'customers' => $customers
      ]);
    }
}

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
use App\Models\CashAccount;
use App\Models\Pembayaran;
use App\Models\Kas;
use Jenssegers\Agent\Agent;
use DateTime;

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
      
      $data = $data->get();

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

    // public function index(Request $request){
    //   if (!$request->dateStart??null) {
    //     request()->request->add(['dateStart'=>date('Y-m-01')]);  
    //     request()->request->add(['dateEnd'=>date('Y-m-t')]);  
    //   }
    //   $input=[
    //     'dateStart'=>$request->dateStart,
    //     'dateEnd'=>$request->dateEnd,
    //     'year'=>date('Y', strtotime($request->dateEnd)),
    //     'month'=>date('m', strtotime($request->dateEnd)),
    //     'count'=>$request->count??5
    //   ];
    //   $request->dateStart=$request->dateStart." 00:00:00";
    //   $request->dateEnd=$request->dateEnd." 23:59:59";

    //     // Perhitungan Uang Retur 
    //     $returInvoices = Retur::where('status_enum', '1')->where('tipe_retur', 1)
    //     ->whereHas('linkInvoice', function($q) use($request){
    //       $q->whereHas('linkOrder', function($q) use($request){
    //         $q->whereHas('linkOrderTrack', function($q) use($request){
    //           $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
    //         });
    //       });
    //     })
    //     ->select('id_item', \DB::raw('SUM(kuantitas*harga_satuan) as total_retur'))
    //     ->groupBy('id_item')->get()->sum('total_retur');

    //     $item =OrderItem::
    //       whereHas('linkOrder',function($q) use($request){
    //           $q->whereHas('linkOrderTrack',function($q) use($request) {
    //               $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
    //           });
    //       })
    //       ->whereHas('linkItem',function($q) use($request){
    //           $q->where('status_enum','1');
    //       })
    //       ->select('id_item', \DB::raw('SUM(kuantitas) as total'))
    //       ->groupBy('id_item')->with('linkItem');
    //     $data=[];
    //     $data['produk_laris'] = $item->orderBy('total', 'DESC')->take(10)->get();


    //     $data['produk_tidak_terjual'] = $item->pluck('id_item')->toArray();
    //     $data['produk_tidak_terjual'] = Item::where('status_enum','1')->whereNotIn('id',$data['produk_tidak_terjual'])->get();

    //     //produk jual dikit
    //     // $data['produk_slow'] = array_keys($item->orderBy('total', 'ASC')->get()->groupBy('total')->take(5)->toArray());

    //     // $data['produk_slow'] = $item->orderBy('total', 'ASC')->get()->whereIn('total',  $data['produk_slow']);
        
    //     $data['omzet'] = Invoice::whereHas('linkOrder',function($q) use($request) {
    //       $q->whereHas('linkOrderTrack',function($q) use($request) {
    //           $q->whereIn('status_enum', ['4', '5'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
    //       });
    //     })->select(\DB::raw('SUM(harga_total) as total'))->first();

        
    //     // $data['pembelian'] = Pengadaan::whereBetween('created_at',[$request->dateStart,$request->dateEnd])
    //     // ->select(\DB::raw('SUM(harga_total) as total'))->first();

    //     $data['pembelian'] = Pengadaan::
    //     // whereBetween('created_at',[$request->dateStart,$request->dateEnd])->
    //     select('id_item', \DB::raw('SUM(harga_total)/SUM(kuantitas) as harga_item'))
    //     ->groupBy('id_item');

    //     $data['pembelian'] = OrderItem::
    //       whereHas('linkOrder',function($q) use($request){
    //         $q->whereHas('linkOrderTrack',function($q) use($request) {
    //             $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
    //         });
    //       })
    //       ->whereHas('linkItem',function($q) use($request){
    //         $q->where('status_enum','1');
    //       })
    //       ->joinSub($data['pembelian'], 'harga_beli', function ($join) {
    //         $join->on('order_items.id_item', '=', 'harga_beli.id_item');
    //       })
    //       ->select('order_items.id_item', \DB::raw('SUM(kuantitas*harga_item) as total_price'))
    //       ->groupBy('id_item')
    //       // ->select(\DB::raw('SUM(total_price) as total'))
    //       ->get()
    //       ->sum('total_price');

    //     $data['retur'] = Retur::whereBetween('created_at', [$request->dateStart, $request->dateEnd])->where('status_enum','1')
    //       ->select('id_item', \DB::raw('SUM(kuantitas*harga_satuan) as total_price'))
    //       ->groupBy('id_item')->get()->sum('total_price');
            

    //     $data['produk_slow'] = OrderItem::
    //       whereHas('linkOrder',function($q) use($request){
    //           $q->whereHas('linkOrderTrack',function($q) use($request) {
    //               $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
    //           });
    //       })
    //       ->whereHas('linkItem',function($q) use($request){
    //           $q->where('status_enum','1');
    //       })
    //       ->select('id_item', \DB::raw('SUM(kuantitas) as total'), \DB::raw('count(*) as count'))
    //     ->groupBy('id_item')->with('linkItem')->orderBy('count', 'ASC')->take($request->count??5)->get();


    //     $data['pp'] = OrderItem::
    //       whereHas('linkOrder',function($q) use($request){
    //           $q->whereHas('linkOrderTrack',function($q) use($request) {
    //               $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
    //           });
    //       })
    //       ->whereHas('linkItem',function($q) use($request){
    //           $q->where('status_enum','1');
    //       })
    //       ->select('order_items.id_item', \DB::raw('SUM(kuantitas*harga_satuan) as total_price'))
    //       ->groupBy('id_item')
    //       ->get()
    //       ->sum('total_price');

    //     $data['rtrd']=Retur::whereBetween('created_at', [$request->dateStart, $request->dateEnd])->where('status_enum','1')
    //         ->select('id_invoice', \DB::raw('SUM(kuantitas*harga_satuan) as total_price'))
    //         ->groupBy('id_invoice')->with('linkInvoice')->get()->pluck('total_price','linkInvoice.id_order')->toArray();
        
    //     $data['ppd'] = OrderItem::
    //       whereHas('linkOrder',function($q) use($request){
    //           $q->whereHas('linkOrderTrack',function($q) use($request) {
    //               $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
    //           });
    //       })
    //       ->whereHas('linkItem',function($q) use($request){
    //           $q->where('status_enum','1');
    //       })
    //       ->select('id_order', \DB::raw('SUM(kuantitas*harga_satuan) as total_price'))
    //       ->groupBy('id_order')
    //       ->get()->pluck('total_price','id_order')->toArray();

    //     $data['ppd-rtrd'] = [];
    //     foreach ($data['ppd'] as $a => $val){
    //       array_push( $data['ppd-rtrd'],[$a => $val-(array_key_exists($a, $data['rtrd'])?$data['rtrd'][$a]:0)]);
    //     }

    //     $data['invd'] = Invoice::whereHas('linkOrder',function($q) use($request) {
    //                       $q->whereHas('linkOrderTrack',function($q) use($request) {
    //                           $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
    //                       });
    //                     })->get()->pluck('harga_total','id_order')->toArray();

    //     $data['totalReturInvoice'] = $returInvoices;
    //     //ceking aja
    //     // dd($data);

    //     $customersPengajuanLimit = Customer::where('status_limit_pembelian_enum', '0')->get();
    //     $stokOpnamePengajuan = Order::where('id_customer',0)->where('status_enum','-1')->get();
    //     $request->session()->increment('count');

    //     return view('owner.dashboard',[
    //       'data' => $data,
    //       'input' => $input,
    //       'customersPengajuanLimit' => $customersPengajuanLimit,
    //       'stokOpnamePengajuan' => $stokOpnamePengajuan
    //     ])->with('datadua', [
    //       'lihat_notif_spv' => true,
    //       'jml_pengajuan' => count($customersPengajuanLimit),
    //       'juml_opname' => count($stokOpnamePengajuan)
    //     ]);
    // }
    
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

      $data = $data->get();

      return view('supervisor.report.koordinattrip',compact('data','input'));
    }

    public function cekKoordinatTrip(Trip $trip){
      $koordinatCustomer = Customer::where('id', $trip->id_customer)->first()->koordinat;      
      $koordinatTrip = $trip->koordinat;
      $customers = Customer::all();

      $data = [
        'trip' => $trip,
        'datas' => [
          'koordinatCustomer' => $koordinatCustomer,
          'koordinatTrip' => $koordinatTrip
        ],
        'customers' => $customers
      ];

      $agent = new Agent();
      if($agent->isMobile()){
        return view('mobile.administrasi.tripsales.detail', $data);
      }else{
        return view('supervisor.report.cekkoordinattrip', $data);
      }
    }

    public function tripSalesAdmin(Request $request){
      if (!$request->tripDateStart ?? null) {
        request()->request->add(['tripDateStart'=>date('Y-m-01')]);  
        request()->request->add(['tripDateEnd'=>date('Y-m-t')]);  
      }
  
      $input=[
        'dateStart'=>date('Y-m-01'),
        'dateEnd'=>date('Y-m-t'),
        'tripDateStart'=>$request->tripDateStart ?? null,
        'tripDateEnd'=>$request->tripDateEnd ?? null,
        'tripSalesman'=>$request->tripSalesman ?? null,
        'tripCustomer'=>$request->tripCustomer ?? null,
      ];
  
      $request->tripDateStart=$request->tripDateStart." 00:00:00";
      $request->tripDateEnd=$request->tripDateEnd." 23:59:59";
  
      $tripssales = Trip::whereBetween('waktu_masuk',[$request->tripDateStart,$request->tripDateEnd])
                    ->orderBy('id', 'DESC')
                    ->with(['linkCustomer', 'linkStaff']);
  
      if($request->tripSalesman ?? null){
        $tripssales = $tripssales->whereHas('linkStaff',function($q) use($request){
          $q->where(strtolower('nama'),'like','%'.$request->tripSalesman.'%');
        });
      }

      if($request->tripCustomer ?? null){
        $tripssales = $tripssales->whereHas('linkCustomer',function($q) use($request){
          $q->where(strtolower('nama'),'like','%'.$request->tripCustomer.'%');
        });
      }
  
      $agent = new Agent();
      if($agent->isMobile()){
        return view('mobile.administrasi.tripsales.index', [
          'input' => $input,
          'tripssales' => $tripssales->paginate(10)
        ]);
      }else{
        return view('administrasi.tripsales.index', [
          'input' => $input,
          'tripssales' => $tripssales->get()
        ]);
      }
    }

    public function laporanExcel(Request $request){
      $bukuKas = CashAccount::where('account', '<=', 100)
                ->where(function ($query) {
                  $query->whereNull('default')->orWhereIn('default', ['1', '2']);                  
                })->get();
      
      $staff = Staff::where('role', 3)->where('status_enum','1')->get();

      return view('report.laporanExcel', compact('bukuKas', 'staff'));
    }

    public function indexNew(Request $request){
        if (!$request->dateStart??null) {
          request()->request->add(['dateStart'=>date('Y-m-01')]);  
          request()->request->add(['dateEnd'=>date('Y-m-t')]);  
        }
        $input=[
          'dateStart'=>$request->dateStart,
          'dateEnd'=>$request->dateEnd,
          'year'=>date('Y', strtotime($request->dateEnd)),
          'month'=>date('m', strtotime($request->dateEnd)),
          'count'=>$request->count??5,
          'kas'=>$request->kas ?? null
        ];
        $request->dateStart = $request->dateStart." 00:00:00";
        $request->dateEnd = $request->dateEnd." 23:59:59";

        $item = OrderItem::whereHas('linkOrder',function($q) use($request){
            $q->whereHas('linkOrderTrack',function($q) use($request) {
                $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
            });
          })
          ->whereHas('linkItem',function($q) use($request){
            $q->where('status_enum','1');
          })
          ->select('id_item', \DB::raw('SUM(kuantitas) as total'))
          ->groupBy('id_item')->with('linkItem');

        $data = [];
        $data['produk_laris'] = $item->orderBy('total', 'DESC')->take(10)->get();
        $data['produk_tidak_terjual'] = $item->pluck('id_item')->toArray();
        $data['produk_tidak_terjual'] = Item::where('status_enum','1')->whereNotIn('id',$data['produk_tidak_terjual'])->get();

        $data['total_omzet'] = Pembayaran::whereHas('linkInvoice', function($q) use($request) {
            $q->whereHas('linkOrder', function($q) use($request) {
              $q->whereHas('linkOrderTrack', function($q) use($request) {
                $q->whereIn('status_enum', ['4','5','6']);
              });
            });
          })->whereBetween('tanggal', [$request->dateStart, $request->dateEnd])
          ->select('id_invoice', \DB::raw('SUM(jumlah_pembayaran) as omzet'))
          ->groupBy('id_invoice')
          ->get()
          ->sum('omzet');

        $invoice_blmlunas = Invoice::where('tanggal_lunas', null)
          ->whereHas('linkOrder', function($q) {
            $q->whereHas('linkOrderTrack', function($q) {
              $q->whereIn('status_enum',['4','5','6']);
            });
          })
          ->where('invoices.created_at', '<=', $request->dateEnd);
        $total_invoice = $invoice_blmlunas->select(\DB::raw('SUM(harga_total) as total'))->get()->sum('total');
        $total_dibayar = $invoice_blmlunas->join('pembayarans','invoices.id','=','pembayarans.id_invoice')
          ->select('pembayarans.id_invoice', \DB::raw('SUM(pembayarans.jumlah_pembayaran) as total'))
          ->groupBy('pembayarans.id_invoice')->get()->sum('total');
        
        $data['jumlah_invoice_blmlunas'] = $invoice_blmlunas->get()->count();
        $data['total_piutang'] = $total_invoice - $total_dibayar;

        $data['total_retur'] = Retur::where('status_enum', '1')->where('tipe_retur', '1')
          ->whereHas('linkInvoice', function($q) use($request){
            $q->whereHas('linkOrder', function($q) use($request){
              $q->whereHas('linkOrderTrack', function($q) use($request){
                $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
              });
            });
          })
          ->select('id_item', \DB::raw('SUM(kuantitas*harga_satuan) as total_retur'))
          ->groupBy('id_item')->get()->sum('total_retur');

    
        $total1_hpp = OrderItem::whereHas('linkOrder', function($q) use($request){
              $q->whereHas('linkOrderTrack', function($q) use($request) {
                  $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
              });
            })
            ->whereHas('linkItem', function($q){
              $q->where('status_enum','1');
            })
            ->join('items','items.id','=','order_items.id_item')
            ->select('order_items.id_item', \DB::raw('SUM(items.hargahpp_satuan * order_items.kuantitas) as total'))
            ->groupBy('order_items.id_item')->get()
            ->sum('total');

        $data['pembelian'] = Pengadaan::select('id_item', \DB::raw('SUM(harga_total)/SUM(kuantitas) as harga_item'))
                      ->groupBy('id_item');

        $total2_hpp = OrderItem::whereHas('linkOrder',function($q) use($request){
              $q->whereHas('linkOrderTrack', function($q) use($request) {
                  $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
              });
            })
            ->whereHas('linkItem', function($q) use($request){
              $q->where('status_enum','1')->where('hargahpp_satuan', null);
            })
            ->joinSub($data['pembelian'], 'harga_beli', function ($join) {
              $join->on('order_items.id_item', '=', 'harga_beli.id_item');
            })
            ->select('order_items.id_item', \DB::raw('SUM(kuantitas*harga_item) as total_price'))
            ->groupBy('id_item')
            ->get()
            ->sum('total_price');

        $data['total_hpp'] = $total1_hpp + $total2_hpp;

        $data['pembelian'] = OrderItem::
          whereHas('linkOrder',function($q) use($request){
            $q->whereHas('linkOrderTrack',function($q) use($request) {
                $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
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
          ->get()
          ->sum('total_price');

        $data['produk_slow'] = OrderItem::
          whereHas('linkOrder',function($q) use($request){
              $q->whereHas('linkOrderTrack',function($q) use($request) {
                  $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
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
                  $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
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
                  $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
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
                              $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
                          });
                        })->get()->pluck('harga_total','id_order')->toArray();

        $data['pengajuanLimitPembelian'] = Customer::where('status_limit_pembelian_enum', '0')->get();
        $data['pengajuanOpname'] = Order::where('id_customer',0)->where('status_enum','-1')->get();
        $request->session()->increment('count');

        $data['kas'] = CashAccount::where('account', '<=', 100)
                        ->where(function ($query) {
                          $query->whereNull('default')->orWhereIn('default', ['1', '2']);                  
                        })->get();

        if($request->kas ?? null){
          $prev_date = date('Y-m-d', strtotime($request->dateStart .' -1 day'));

          $debit_awal = Kas::where('kas', $request->kas)
              ->where(function ($query) {
                $query->where('status_pengajuan','0')->orWhere('status_pengajuan','-1')->orWhereNull('status_pengajuan');                  
              })
              ->where(function ($query) {
                $query->where('status','1')->orWhereNull('status');                  
              })->where('tanggal', '<=', $prev_date)
              ->where('debit_kredit','1')
              ->select(\DB::raw('SUM(uang) as debit')) 
              ->get()
              ->sum('debit');

          $kredit_awal = Kas::where('kas', $request->kas)
              ->where(function ($query) {
                $query->where('status_pengajuan','0')->orWhere('status_pengajuan','-1')->orWhereNull('status_pengajuan');                  
              })
              ->where(function ($query) {
                $query->where('status','1')->orWhereNull('status');                  
              })->where('tanggal', '<=', $prev_date)
              ->where('debit_kredit','-1')
              ->select(\DB::raw('SUM(uang) as kredit')) 
              ->get()
              ->sum('kredit');
          $data['hitungkas']['saldo_awal'] = $debit_awal - $kredit_awal;


          $debit_akhir = Kas::where('kas', $request->kas)
              ->where(function ($query) {
                $query->where('status_pengajuan','0')->orWhere('status_pengajuan','-1')->orWhereNull('status_pengajuan');                  
              })
              ->where(function ($query) {
                $query->where('status','1')->orWhereNull('status');                  
              })->where('tanggal', '<=', $request->dateEnd)
              ->where('debit_kredit','1')
              ->select(\DB::raw('SUM(uang) as debit')) 
              ->get()
              ->sum('debit');

          $kredit_akhir = Kas::where('kas', $request->kas)
              ->where(function ($query) {
                $query->where('status_pengajuan','0')->orWhere('status_pengajuan','-1')->orWhereNull('status_pengajuan');                  
              })
              ->where(function ($query) {
                $query->where('status','1')->orWhereNull('status');                  
              })->where('tanggal', '<=', $request->dateEnd)
              ->where('debit_kredit','-1')
              ->select(\DB::raw('SUM(uang) as kredit')) 
              ->get()
              ->sum('kredit');
          $data['hitungkas']['saldo_akhir'] = $debit_akhir - $kredit_akhir;


          $pemasukan = Kas::where('kas', $request->kas)
              ->where(function ($query) {
                $query->where('status_pengajuan','0')->orWhere('status_pengajuan','-1')->orWhereNull('status_pengajuan');                  
              })
              ->where(function ($query) {
                $query->where('status','1')->orWhereNull('status');                  
              })->whereBetween('tanggal', [$request->dateStart, $request->dateEnd])
              ->where('debit_kredit','1')
              ->select(\DB::raw('SUM(uang) as pemasukan')) 
              ->get()
              ->sum('pemasukan');
          $data['hitungkas']['pemasukan'] = $pemasukan;
          

          $pengeluaran = Kas::where('kas', $request->kas)
          ->where(function ($query) {
            $query->where('status_pengajuan','0')->orWhere('status_pengajuan','-1')->orWhereNull('status_pengajuan');                  
          })
          ->where(function ($query) {
            $query->where('status','1')->orWhereNull('status');                  
          })->whereBetween('tanggal', [$request->dateStart, $request->dateEnd])
          ->where('debit_kredit','-1')
          ->select(\DB::raw('SUM(uang) as pengeluaran')) 
          ->get()
          ->sum('pengeluaran');
          $data['hitungkas']['pengeluaran'] = $pengeluaran;
        }

        $data['total_pengadaan'] = Pengadaan::whereBetween('created_at', [$request->dateStart, $request->dateEnd])
            ->select(\DB::raw('SUM(harga_total) as total_pengadaan')) 
            ->get()
            ->sum('total_pengadaan');
        
        $data['total_penjualan'] = Invoice::whereHas('linkOrder',function($q) use($request) {
              $q->whereHas('linkOrderTrack', function($q) use($request) {
                  $q->whereIn('status_enum', ['4','5','6'])->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd]);
              });
            })->select(\DB::raw('SUM(harga_total) as total_penjualan'))
            ->get()
            ->sum('total_penjualan');

        // statistik kecepatan
        $kecepatan_pembayaran = 0;
        $kecepatan_proses = 0;
        $inv_lunas = Invoice::where('tanggal_lunas', '!=', null)
                  ->whereBetween('tanggal_lunas', [$request->dateStart, $request->dateEnd])
                  ->with('linkOrder.linkOrderTrack')->get();
        
        foreach($inv_lunas as $inv){
          $waktu_sampai = new DateTime($inv->linkOrder->linkOrderTrack->waktu_sampai); 
          $waktu_lunas = new DateTime($inv->tanggal_lunas." 23:59:59"); 
          $kecepatan_pembayaran += $waktu_sampai->diff($waktu_lunas)->days; 
        }
        
        $order_berangkat = OrderTrack::whereIn('status_enum', ['3','4','5','6'])
                        ->whereBetween('waktu_berangkat', [$request->dateStart, $request->dateEnd])
                        ->get();

        foreach($order_berangkat as $ordtrck){
          $waktu_order = new DateTime($ordtrck->waktu_order); 
          $waktu_berangkat = new DateTime($ordtrck->waktu_berangkat); 
          $kecepatan_proses += $waktu_order->diff($waktu_berangkat)->days; 
        }

        $data['avg_pembayaran'] = count($inv_lunas) ? $kecepatan_pembayaran/count($inv_lunas) : 0;
        $data['avg_pemrosesan'] = count($order_berangkat) ? $kecepatan_proses/count($order_berangkat) : 0;

        // Rata2 Lama Perjalanan
        $lama_perjalanan_hari = 0;
        $lama_perjalanan_jam = 0;
        $lama_perjalanan_menit = 0;

        $order_sampai = OrderTrack::whereIn('status_enum', ['4','5','6'])
                        ->whereBetween('waktu_sampai', [$request->dateStart, $request->dateEnd])
                        ->get();

        foreach($order_sampai as $ordtrck){
          $waktu_berangkat = new DateTime($ordtrck->waktu_berangkat); 
          $waktu_sampai = new DateTime($ordtrck->waktu_sampai); 
          $interval = $waktu_berangkat->diff($waktu_sampai); 
          $lama_perjalanan_hari += $interval->days;
          $lama_perjalanan_jam += $interval->h;
          $lama_perjalanan_menit += $interval->i;
        }

        $data['avg_perjalanan_hari'] = count($order_sampai) ? $lama_perjalanan_hari/count($order_sampai) : 0;
        $data['avg_perjalanan_jam'] = count($order_sampai) ? $lama_perjalanan_jam/count($order_sampai) : 0;
        $data['avg_perjalanan_menit'] = count($order_sampai) ? $lama_perjalanan_menit/count($order_sampai) : 0;

        //  Total EC dan Kunjungan
        $data['total_EC'] = 0;
        $data['total_kunjungan'] = 0;
        $staffs =Staff::where('status_enum','1')->where('role',3)
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

        foreach($staffs as $sales){
          $data['total_EC'] += $sales->linkTripEc->count() ?? 0;
          $data['total_kunjungan'] += $sales->linkTrip->count() ?? 0;
        }

        return view('owner.dashboard',[
          'data' => $data,
          'input' => $input,
        ])->with('datadua', [
          'lihat_notif_spv' => true,
          'jumlah' => [
            'pengajuanLimitPembelian' => count($data['pengajuanLimitPembelian']),
            'pengajuanStokOpname' => count($data['pengajuanOpname']),
          ]
        ]);
    }
}

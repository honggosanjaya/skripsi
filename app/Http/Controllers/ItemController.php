<?php

namespace App\Http\Controllers;

use App\Models\CashAccount;
use App\Models\CategoryItem;
use App\Models\Kas;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pengadaan;
use App\Models\History;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\Kanvas;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Database\QueryException;
use Intervention\Image\ImageManagerStatic as Image;

class ItemController extends Controller
{
  protected $status = null;
  protected $error = null;
  protected $data = null;

  public function getListAllProductAPI($id){
    $history = History::where('id_customer',$id)->with('linkItem')->get();
    $items = $history->pluck('id_item');
    $items = Item::orderBy("status_enum", "ASC")->whereNotIn('id',$items->toArray())->paginate(4);

    $orderItemUnconfirmed=OrderItem::
    whereHas('linkOrder',function($q) {
      $q->where('status_enum', '-1');
    })
    ->whereHas('linkOrder',function($q) {
      $q->whereHas('linkOrderTrack',function($q) {
        $q->where('status_enum','!=', '-1');
      });
    })
    ->select('id_item', DB::raw('SUM(kuantitas) as jumlah_blmkonfirmasi'))      
    ->groupBy('id_item')->pluck('jumlah_blmkonfirmasi','id_item')->all();
  
    return response()->json([
      "status" => 'success',
      "data" => $items,
      "orderRealTime" => $orderItemUnconfirmed
    ], 200);
  }

  public function getListHistoryProductAPI($id){
    $history = History::where('id_customer',$id)->with(['linkItem'])->get();
    $customer = Customer::where('id',$id)->with('linkCustomerType')->first();

    $latestOrderItem = [];

    $histories = History::select('id_item')->where('id_customer',$id)->get();
    foreach($histories as $h){
      $query = OrderItem::where('id_item',$h['id_item'])
      ->whereHas('linkOrder', function($q) use($id){
          $q->where('id_customer', $id);
        })
      ->join('order_tracks','order_items.id_order','=','order_tracks.id_order')
      ->select('order_items.id', 'order_items.id_order' ,'id_item', 'harga_satuan', 'order_tracks.waktu_diteruskan' ,'order_items.created_at')
      ->where('order_tracks.waktu_diteruskan', '!=', null)
      ->latest()->first();

      $latestOrderItem[$h->id_item] = array(
        $query,
      );
    }

    // dd($latestOrderItem);

    $orderItemUnconfirmed=OrderItem::
    whereHas('linkOrder',function($q) {
      $q->where('status_enum', '-1');
    })
    ->whereHas('linkOrder',function($q) {
      $q->whereHas('linkOrderTrack',function($q) {
        $q->where('status_enum','!=', '-1');
      });
    })
    ->select('id_item', DB::raw('SUM(kuantitas) as jumlah_blmkonfirmasi'))      
    ->groupBy('id_item')->pluck('jumlah_blmkonfirmasi','id_item')->all();


    return response()->json([
      "status" => "success",
      "data" => [
        "history" => $history,
        "customer" => $customer,
        "latestOrderItems" => $latestOrderItem
      ],
      "orderRealTime" => $orderItemUnconfirmed
    ], 200);
  }

  public function updateStockCustomer(Request $request){
    History::where('id_customer',$request->id_customer)->where('id_item',$request->id_item)->update(['stok_terakhir_customer' => $request->quantity]);
    return response()->json([
      "status" => "success",
      "message" => "berhasil mengupdate stok terakhir customer"
    ], 200);
  }

  //pengadaan
  public function productList(Request $request){
      $products = Item::orderBy("status_enum", "ASC")->get();
      $counter = $request->session()->increment('counterPengadaan');
      $pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

      if(!$pageWasRefreshed) {
        if($counter>0){
          \Cart::session(auth()->user()->id.'pengadaan')->clear();
          session(['counterPengadaan' => 0]);
        }
      }

      return view('administrasi.stok.pengadaan.index', [
        "products" => $products,
        // "pageWasRefreshed" => $pageWasRefreshed,
        // 'counter' => $counter,
        "title" => "Stok Marketing - Pengadaan",
      ]);
  }

  //opname
  public function productListOpname(Request $request){
      $products = Item::orderBy("status_enum", "ASC")->get();
      $counter = $request->session()->increment('counterOpname');
      $pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

      if(!$pageWasRefreshed) {
        if($counter>0){
          \Cart::session(auth()->user()->id.'opname')->clear();
          session(['counterOpname' => 0]);
        }
      }

      return view('administrasi.stok.opname.index', [
        "products" => $products,
        // "pageWasRefreshed" => $pageWasRefreshed,
        // 'counter' => $counter,
        "title" => "Stok Marketing - opname",
      ]);
  }

  public function riwayatOpname(){
      $orders = Order::orderBy('created_at','DESC')->where('id_customer',0)->with(['linkStaff'])->get();
      return view('administrasi.stok.opname.riwayat', [
        "orders" => $orders
      ]);
  }

  public function detailRiwayatOpname(Order $order){
      $order_items = OrderItem::where('id_order',$order->id)->with(['linkItem'])->paginate(10);
      
      return view('administrasi.stok.opname.riwayatdetail', [
        "order_items" => $order_items
      ]);
  }

  public function simpanDataPengadaan(Request $request){
    $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();

    $rules = ([
      'no_nota' => ['required', 'max:20'],
      'harga_total' => ['required'],
      'keterangan' => ['required', 'string', 'max:255'],
    ]);

    if($request->kas != null){
      $rules['kas'] = ['required'];
    }

    $request->validate($rules);

    $uangKas = 0;

    $data = [];
    $pengadaan_count="PGD-". explode("-",Pengadaan::orderBy("id", "DESC")->first()->no_pengadaan ?? 'PGD-0')[1] + 1 ."-".date_format(now(),"YmdHis");
    foreach($cartItems as $item){
      $uangKas = $uangKas + $item->attributes->total_harga;
      array_push($data,[
        'id_item' => $item->id,
        'id_staff' => auth()->user()->id_users,
        'no_pengadaan' => $pengadaan_count,
        'no_nota' => $request->no_nota,
        'kuantitas' => $item->quantity,
        'harga_total' => $item->attributes->total_harga,
        'keterangan' => $request->keterangan,
        'created_at' => now(),
      ]);
      $stok = Item::find($item->id);
      $stok->stok += $item->quantity;
      $stok->save();
    }

    Pengadaan::insert($data);
    \Cart::session(auth()->user()->id.$request->route)->clear();


    $cashaccount = CashAccount::where('default', '1')->first();
    if($cashaccount != null){
      Kas::insert([
        'id_staff' => auth()->user()->id_users,
        'tanggal' => date("Y-m-d"),
        'no_bukti' => $pengadaan_count,
        'debit_kredit' => '-1',
        'keterangan_1' => 'pengadaan',
        'keterangan_2' => $request->keterangan,
        'uang' => $uangKas,
        'id_cash_account' => $cashaccount->id,
        'kas' => $request->kas,
        'created_at' => now()
      ]);
    }

    return redirect()->route('products.list')->with('pesanSukses', 'Produk berhasil ditambahkan ke database');
  }

  public function simpanDataOpname(Request $request){
    $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();

    $order_id= Order::insertGetId([
      'id_customer' => 0,
      'id_staff' => auth()->user()->id_users,
      'status_enum' => '-1',
      'created_at' => now(),
    ]);

    $data = [];
    foreach($cartItems as $item){
      array_push($data,[
        'id_item' => $item->id,
        'id_order' => $order_id,
        'kuantitas' => $item->attributes->jumlah,
        'harga_satuan' => 0,
        'keterangan' =>  $item->attributes->keterangan,
        'created_at' =>  now(),
      ]);
      // $stok = Item::find($item->id);
      // $stok->stok +=  $item->attributes->jumlah;
      // $stok->save();
    }

    OrderItem::insert($data);
    
    \Cart::session(auth()->user()->id.$request->route)->clear();

    return redirect('/administrasi/stok/')->with('pesanSukses', 'Produk berhasil ditambahkan ke database');
  }

  public function index(){      
    return view('administrasi.stok.produk.index', [
      'items' => Item::orderBy("status_enum", "ASC")->orderBy('id','DESC')->get(),
      "title" => "List Produk"
    ]);
  }

  public function create(){
    $parentItems = Item::where('link_item',null)->get();

    // $items = Item::all();
    // $parentItems = [];

    // foreach($items as $item){
    //   $get1 = '';
    //   $get2 = '';
    //   $value = 0;
      
    //   if($item->link_item == null){
    //     $get1 = $item->nama;
    //     $value = $item->id;
    //     array_push($parentItems, [$get1, $value]);
    //   }
    //   else if($item->link_item != null){
    //     for($i=0; $i<Item::count(); $i++){
    //       if($item->link_item == $items[$i]->id){
    //         $get2 = $parentItems[$i][0] . " - " .$item->nama;
    //         $value = $item->id;
    //         array_push($parentItems, [$get2,$value]);
    //       }
    //     }
    //   }
    // }
      
    // usort($parentItems, function($a, $b) {
    //   return $a[0] <=> $b[0];
    // });

      // dd($parentItems);

      $statuses = [
        1 => 'active',
        -1 => 'inactive',
      ];

    return view('administrasi.stok.produk.create', [
      'items' => Item::orderBy("status_enum", "ASC")->get(),
      'categories' => CategoryItem::all(),
      'statuses' => $statuses,
      'parentItems' => $parentItems,
      "title" => "Stok Marketing - List Produk - Add"
    ]);
  }

  public function store(Request $request){
    $rules = ([
      'nama' => ['required', 'string', 'max:255'],
      'kode_barang' => ['required', 'string', 'max:20', 'unique:items'],
      'satuan' => ['required', 'string', 'max:30'],
      'harga1_satuan' => ['required', 'numeric'],
      'harga2_satuan' => ['nullable', 'numeric'],
      'harga3_satuan' => ['nullable', 'numeric'],
      'gambar' => 'image|file|max:1024',
      // 'gambar' => 'image|file',
      'volume' => 'required'
    ]);

    if($request->stok){
      $rules['stok'] = ['integer', 'min:0'];
    }
    if($request->min_stok){
      $rules['min_stok'] = ['integer', 'min:0'];
    }
    if($request->max_stok){
      $rules['max_stok'] = ['integer', 'min:0'];
    }

    $validatedData = $request->validate($rules);
    $validatedData['status_enum'] = $request->status_enum;
    $validatedData['id_category'] = $request->category;
    $validatedData['link_item'] = $request->link_item;
    $validatedData['max_pengadaan'] = ($request->max_stok??0) - ($request->min_stok??0);
    
    if ($request->gambar) {
      $nama_item = str_replace(" ", "-", $request->nama);
      $file_name = 'ITM-' . $nama_item . '-' .date_format(now(),"YmdHis"). '.' . $request->gambar->extension();
      $validatedData['gambar'] = $file_name;
      Image::make($request->file('gambar'))->resize(350, null, function ($constraint) {
        $constraint->aspectRatio();
      })->save(public_path('storage/item/') . $file_name);
    }    

    Item::create($validatedData);

    return redirect('/administrasi/stok/produk') -> with('pesanSukses', 'Produk berhasil ditambahkan' );
  }

  public function edit($id){
    $parentItems = Item::where('link_item',null)->get();
    // $cannotSelected = Item::where('id',$id)->orWhere('link_item',$id)->count();
    
    // $items = Item::all();
    // $itemsCount = Item::count();
    // $parentItems = [];

    // foreach($items as $item){
    //   $get1 = '';
    //   $get2 = '';
    //   $value = 0;

    //   // if($item->id != $id && $item->link_item != $id){
    //     if($item->link_item == null){
    //         $get1 = $item->nama;
    //         $value = $item->id;
    //         array_push($parentItems, [$get1, $value]);
    //     }
    //     else if($item->link_item != null){
    //       for($j=$itemsCount-1; $j>=0; $j--){
    //         if($item->link_item == $items[$j]->id){
    //           $get2 = $parentItems[$j][0] . " - " .$item->nama;
    //           $value = $item->id;
    //           array_push($parentItems, [$get2, $value]);
    //         }
    //       }
    //     }
    //   // }
    // }
      
    // usort($parentItems, function($a, $b) {
    //   return $a[0] <=> $b[0];
    // });

    // dd($parentItems);

    $statuses = [
      1 => 'active',
      -1 => 'inactive',
    ];
  
    return view('administrasi.stok.produk.edit',[
      'item' => Item::where('id', $id)->first(),
      'parentItems' => $parentItems,
      'categories' => CategoryItem::all(),
      'statuses' => $statuses,
    ]);
  }

  public function update(Request $request, $id)
  {
    $rules = ([
      'nama' => ['required', 'string', 'max:255'],
      'gambar' => 'image|file|max:1024',
      'min_stok' => ['required', 'integer', 'min:0'],
      'max_stok' => ['required', 'integer', 'min:0'],
      'satuan' => ['required', 'string', 'max:30'],
      'harga1_satuan' => ['required', 'numeric'],
      'harga2_satuan' => ['nullable', 'numeric'],
      'harga3_satuan' => ['nullable', 'numeric'],
      'volume' => ['required'],
    ]);

    if($request->kode_barang !== Item::where('id', $id)->first()->kode_barang){
      $rules['kode_barang'] = ['required', 'string', 'max:20', 'unique:items'];
    }

    $validatedData = $request->validate($rules);
    $validatedData['id_category'] = $request->category;
    $validatedData['link_item'] = $request->link_item;
    $validatedData['status_enum'] = $request->status_enum;

    if ($request->gambar) {
      if($request->oldGambar){
        \Storage::delete('/item/'.$request->oldGambar);
      }

      $file= $request->file('gambar');
      $nama_item = str_replace(" ", "-", $validatedData['nama']);
      $file_name = 'ITM-' . $nama_item . '-' .date_format(now(),"YmdHis"). '.' . $file->getClientOriginalExtension();
      Image::make($request->file('gambar'))->resize(350, null, function ($constraint) {
        $constraint->aspectRatio();
      })->save(public_path('storage/item/') . $file_name);
      $validatedData['gambar'] = $file_name;
    }    

    Item::where('id', $id)->update($validatedData);
  
    return redirect('/administrasi/stok/produk') -> with('pesanSukses', 'Berhasil mengubah data');
  }

    public function customerIndex(){
      $customer = Customer::where('id', auth()->user()->id_users)->first();
        return view('customer.produk',[
            'items' => Item::where('status_enum','1')->orderBy("status_enum", "ASC")->get(),
            'customer' => $customer
        ]);
    }

    public function itemSearch(){
        $items = Item::where(strtolower('nama'),'like','%'.request('cari').'%')->get();
        $customer = Customer::where('id', auth()->user()->id_users)->first();
        return view('customer.produk',[
            'items' => $items,
            'customer' => $customer
        ]);
    }

    public function indexAdministrasi(){
        $items = Item::orderBy("status_enum", "ASC")->paginate(10);
        return view('administrasi.stok.index',[
            'items' => $items
        ]);
    }

    public function cariStok(){
        $items = Item::where(strtolower('nama'),'like','%'.request('cari').'%')
        ->orWhere(strtolower('kode_barang'),'like','%'.request('cari').'%')
        ->paginate(10);
               
        return view('administrasi/stok.index',[
            'items' => $items
        ]);
    }

    public function riwayatAdministrasi(){
        $pengadaans = Pengadaan::orderBy('created_at','DESC')
        ->select('no_pengadaan','no_nota','keterangan','created_at', DB::raw('SUM(harga_total) as harga'))
        ->groupBy('no_pengadaan','no_nota','keterangan','created_at')->get();
        
        return view('administrasi.stok.riwayat.index',[
            'pengadaans' => $pengadaans
        ]);
    }

    // public function cariRiwayat(){
    //     $pengadaans = Pengadaan::select('no_pengadaan','no_nota','keterangan','created_at', DB::raw('SUM(harga_total) as harga'))
    //     ->where(strtolower('no_nota'),'like','%'.request('cari').'%')
    //     ->groupBy('no_pengadaan','no_nota','keterangan','created_at')->paginate(10);
        
    //     return view('administrasi/stok/riwayat.index',[
    //         'pengadaans' => $pengadaans
    //     ]);
    // }

    public function cariRiwayatDetail(Pengadaan $pengadaan){
        $pengadaans = Pengadaan::where('no_pengadaan','=',$pengadaan->no_pengadaan)
        ->with("linkItem")
        ->get();

        $total = Pengadaan::selectRaw('SUM(harga_total) as harga')
        ->where('no_pengadaan','=',$pengadaan->no_pengadaan)
        ->first();

        return view('administrasi.stok.riwayat.detail',[
            'pengadaans' => $pengadaans,
            'total_harga' => $total,
            'detail' => $pengadaan
        ]);
    }

    public function cetakPDF(Pengadaan $pengadaan){
        $pengadaans = Pengadaan::where('no_pengadaan','=',$pengadaan->no_pengadaan)
        ->with("linkItem")
        ->get();

        $total = Pengadaan::selectRaw('SUM(harga_total) as harga')
        ->where('no_pengadaan','=',$pengadaan->no_pengadaan)
        ->first();

        $administrasi = Staff::select('nama')->where('id','=',auth()->user()->id_users)->first();
        
        $pdf = PDF::loadview('administrasi.stok.riwayat.detail-pdf',[
          'pengadaans' => $pengadaans,
          'total_harga' => $total,
          'detail' => $pengadaan,
          'administrasi' => $administrasi            
        ]);

        return $pdf->stream('laporan-NPB-pdf-'.$pengadaan->no_pengadaan.'.pdf');
    }

    public function filterProdukApi(Request $request){
      $items=Item::orderBy('status_enum','ASC');
      if ($request->nama??null) {
        $items=$items->where(strtolower('nama'),'like','%'.$request->nama.'%');
      }
      if ($request->filter=='price') {
        $items=$items->orderBy('harga1_satuan',$request->order);
      }
      if ($request->filter=='nama') {
        $items=$items->orderBy('nama',$request->order);
      }
      if (($request->filterstockmerah??null)==true) {
        $items=$items->where('stok', '<=', 'min_stok');
      }
      
      $items=$items->get();
      $customer = Customer::where('id', auth()->user()->id_users)->first();

      if(!empty($items)){
        return response()->json([
          'html'=> view('customer.c_listproduk', [
            'items' => $items,
            'customer' => $customer
          ])->render(),
          'status' => 'success'
        ]);
      }
      else{
        return response()->json([
          'items' => $items,
          'status' => 'error'
        ],404);
      }
    }

    // public function produkSearch(){
    //   $items =  Item::where(strtolower('nama'),'like','%'.request('cari').'%')
    //     ->paginate(10);

    //   return view('administrasi.stok.produk.index', [
    //     'items' => $items,
    //     "title" => "List Produk"
    //   ]);
    // }

    public function searchProductAPI($id, $name){
      $history = History::where('id_customer',$id)->with('linkItem')->get();
      $items = $history->pluck('id_item');
      $items = Item::orderBy("status_enum", "ASC")->whereNotIn('id',$items->toArray())->where(strtolower('nama'), 'like', '%'.$name.'%')->paginate(4);
  
      $orderItemUnconfirmed=OrderItem::
      whereHas('linkOrder',function($q) {
        $q->where('status_enum', '-1');
      })
      ->whereHas('linkOrder',function($q) {
        $q->whereHas('linkOrderTrack',function($q) {
          $q->where('status_enum','!=', '-1');
        });
      })
      ->select('id_item', DB::raw('SUM(kuantitas) as jumlah_blmkonfirmasi'))      
      ->groupBy('id_item')->pluck('jumlah_blmkonfirmasi','id_item')->all();
  
      return response()->json([
        "status" => "success",
        "data" => $items,
        "orderRealTime" => $orderItemUnconfirmed
      ], 200);
    }

    public function filterProductAPI($id, $filterby){
      $customer = Customer::find($id);
      $history = History::where('id_customer',$id)->with('linkItem')->get();
      $items = $history->pluck('id_item');
      $items = Item::orderBy("status_enum", "ASC")->whereNotIn('id', $items->toArray());

      $orderItemUnconfirmed=OrderItem::
      whereHas('linkOrder',function($q) {
        $q->where('status_enum', '-1');
      })
      ->whereHas('linkOrder',function($q) {
        $q->whereHas('linkOrderTrack',function($q) {
          $q->where('status_enum','!=', '-1');
        });
      })
      ->select('id_item', DB::raw('SUM(kuantitas) as jumlah_blmkonfirmasi'))      
      ->groupBy('id_item')->pluck('jumlah_blmkonfirmasi','id_item')->all();

      if($filterby == 'hargaterendah'){
        $items=$items->orderBy('harga1_satuan','ASC')->paginate(4);
      }else if($filterby == 'hargatertinggi'){
        $items=$items->orderBy('harga1_satuan','DESC')->paginate(4);
      } else if($filterby == 'namaasc'){
        $items=$items->orderBy('nama','ASC')->paginate(4);
      } else if($filterby == 'namadsc'){
        $items=$items->orderBy('nama','DESC')->paginate(4);
      }

      return response()->json([
        "status" => "success",
        "data" => $items,
        "orderRealTime" => $orderItemUnconfirmed
      ], 200);
    }

    public function administrasiEditStatusItem(Item $item){
      $status = $item->status_enum;

      if($status === '1'){
        Item::where('id', $item->id)->update(['status_enum' => '-1']);
      }else if($status === '-1'){
        Item::where('id', $item->id)->update(['status_enum' => '1']);
      }

      return redirect('/administrasi/stok/produk') -> with('pesanSukses', 'Berhasil ubah status' );
    }

    public function productListStokRetur(Request $request){
      $products = Item::where('stok_retur', '>', 0)->orderBy("status_enum", "ASC")->get();
      $counter = $request->session()->increment('counterStokRetur');
      $pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

      if(!$pageWasRefreshed) {
        if($counter>0){
          \Cart::session(auth()->user()->id.'stokretur')->clear();
          session(['counterStokRetur' => 0]);
        }
      }

      return view('administrasi.stok.stokretur.index', [
        "products" => $products,
      ]);
    }

    public function simpanDataStokRetur(Request $request){
      $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();

      foreach($cartItems as $item){
        $stok = Item::find($item->id);
        if($item->attributes->metode == 'potongan'){
          $rules = ([
            'uang' => ['required'],
          ]);
          if($request->kas != null){
            $rules['kas'] = ['required'];
          }
          $request->validate($rules);
          
          $stok->stok_retur -= $item->quantity;
          $stok->save();

          $cashaccount = CashAccount::where('default', '1')->first();
          if($cashaccount != null){
            Kas::insert([
              'id_staff' => auth()->user()->id_users,
              'tanggal' => date("Y-m-d"),
              'debit_kredit' => '1',
              'keterangan_1' => 'Stok Retur',
              'uang' => $request->uang,
              'id_cash_account' => $cashaccount->id,
              'kas' => $request->kas,
              'created_at' => now()
            ]);
          }
        }else{
          $stok->stok += $item->quantity;
          $stok->stok_retur -= $item->quantity;
          $stok->save();
        }
      }
  
      \Cart::session(auth()->user()->id.$request->route)->clear();
  
      return redirect('/administrasi/stok/stokretur')->with('pesanSukses', 'Stok retur tercatat ke database');
    }

    public function indexKanvas(){
      $listkanvas = Kanvas::whereNull('waktu_dikembalikan')
                    ->select(DB::raw('GROUP_CONCAT(id) as ids'),'nama','id_staff_pengonfirmasi_pembawaan','id_staff_yang_membawa','waktu_dibawa', DB::raw('COUNT(id_item) as banyak_jenis_item')) 
                    ->groupBy('nama','id_staff_pengonfirmasi_pembawaan','waktu_dibawa','id_staff_yang_membawa')
                    ->orderBy('id', 'DESC')
                    ->get();
      
      return view('administrasi.kanvas.index', [
        'listkanvas' => $listkanvas
      ]);
    }

    public function createKanvas(){
      $staffs = Staff::where('status_enum', '1')->where('role', 3)->get();
      $items = Item::all();

      return view('administrasi.kanvas.createKanvas', [
        "staffs" => $staffs,
        "items" => $items
      ]);
    }

    public function storeKanvas(Request $request){
      $request->validate([
        'nama' => 'required',
        'id_staff_yang_membawa' => 'required',
      ]);

      for($i=0; $i<count($request->id_item); $i++){
        Kanvas::insert([
          "nama" => $request->nama,
          "id_item" => $request->id_item[$i],
          "stok_awal" => $request->jumlah_item[$i],
          "sisa_stok" => $request->jumlah_item[$i],
          "id_staff_pengonfirmasi_pembawaan" => auth()->user()->id_users,
          "id_staff_yang_membawa" => $request->id_staff_yang_membawa,
          "waktu_dibawa" => now(),
          "created_at" => now()
        ]);

        $stokGudang = Item::where('id', $request->id_item[$i])->first()->stok;

        Item::where('id', $request->id_item[$i])->update([
          "stok" => $stokGudang - $request->jumlah_item[$i],
          "updated_at" => now()
        ]);
      }
      if($request->route=='history'){
        return redirect('/administrasi/kanvas/history')->with('pesanSukses', 'Berhasil menambahkan kanvas');
      }else{
        return redirect('/administrasi/kanvas/create')->with('pesanSukses', 'Berhasil menambahkan kanvas');
      }
    }

    public function checkSalesHasKanvasAPI($idSales){
      $allKanvas = Kanvas::all();
      $isStaffHasKanvas = false;
      foreach($allKanvas as $kanvas){
        if($kanvas->id_staff_yang_membawa == $idSales && $kanvas->waktu_dikembalikan == null){
          $isStaffHasKanvas = true;
        }
      }

      if($isStaffHasKanvas == true){
        $sales = Staff::find($idSales)->nama;
        return response()->json([
          'status' => 'error',
          'message' => 'Kanvas untuk ' . $sales .' sudah ada'
        ]); 
      }else{
        return response()->json([
          'status' => 'success',
          'message' => 'Bisa menambah data'
        ]); 
      }
    }

    public function getDetailKanvas($id){
      $getId = explode("-", $id);
      $detailsKanvas = [];

      foreach($getId as $getid){
        $kanvas = Kanvas::where('id', $getid)->with(['linkItem','linkStaffPengonfirmasiPembawaan','linkStaffPengonfirmasiPengembalian'])->first();
        array_push($detailsKanvas,[
          $kanvas
        ]);
      }

      return response()->json([
        'status' => 'success',
        'data' => $detailsKanvas
      ]); 
    }

    public function historyKanvas(){
      $listkanvas = Kanvas::whereNotNull('waktu_dikembalikan')
      ->whereBetween('waktu_dibawa',[now()->subDays(59),now()])
      ->select(DB::raw('GROUP_CONCAT(id) as ids'),'nama','id_staff_pengonfirmasi_pembawaan','id_staff_yang_membawa','id_staff_pengonfirmasi_pengembalian','waktu_dibawa','waktu_dikembalikan', DB::raw('COUNT(id_item) as banyak_jenis_item')) 
      ->groupBy('nama','id_staff_pengonfirmasi_pembawaan','waktu_dibawa','id_staff_yang_membawa','waktu_dikembalikan','id_staff_pengonfirmasi_pengembalian')
      ->orderBy('id', 'DESC')->get();

      $staffs = Staff::where('status_enum', '1')->where('role', 3)->get();
      $items = Item::all();

      return view('administrasi.kanvas.historyKanvas', [
        "listkanvas" => $listkanvas,
        "staffs" => $staffs,
        "items" => $items
      ]);
    }

    public function pengembalianKanvas($ids){
      $getId = explode("-", $ids);

      foreach($getId as $getid){
        Kanvas::where('id', $getid)->update([
          "id_staff_pengonfirmasi_pengembalian" => auth()->user()->id_users,
          "waktu_dikembalikan" => now(),
          "updated_at" => now()
        ]);

        $kanvas = Kanvas::where('id', $getid)->first();
        $sisaStokKanvas = $kanvas->sisa_stok;
        $idItem = $kanvas->id_item;

        $stokGudang = Item::where('id', $idItem)->first()->stok;

        Item::where('id', $idItem)->update([
          "stok" => $stokGudang + $sisaStokKanvas,
          "updated_at" => now()
        ]);
      }

      return redirect('/administrasi/kanvas')->with('pesanSukses', 'Berhasil mengonfirmasi pengembalian kanvas');
    }

    public function getItemKanvasAPI($idStaf){
      $listkanvas = Kanvas::where('id_staff_yang_membawa',$idStaf)
      ->select(DB::raw('GROUP_CONCAT(id) as ids'),'nama','id_staff_pengonfirmasi_pembawaan','id_staff_pengonfirmasi_pengembalian','waktu_dibawa','waktu_dikembalikan', DB::raw('COUNT(id_item) as banyak_jenis_item')) 
      ->groupBy('nama','id_staff_pengonfirmasi_pembawaan','id_staff_pengonfirmasi_pengembalian','waktu_dibawa','waktu_dikembalikan')
      ->orderBy('id', 'DESC')->get();

      return response()->json([
        'status' => 'success',
        'data' => $listkanvas
      ]); 
    }

    // public function getKanvasAPI($idStaf){

    // }
}

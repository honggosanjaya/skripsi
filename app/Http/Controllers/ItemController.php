<?php

namespace App\Http\Controllers;

use App\Models\CustomerType;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pengadaan;
use App\Models\Status;
use App\Models\History;
use App\Models\Staff;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Database\QueryException;

class ItemController extends Controller
{
  protected $status = null;
  protected $error = null;
  protected $data = null;

  public function getListAllProductAPI($id){
    $history = History::where('id_customer',$id)->with('linkItem')->get();
    $items = $history->pluck('id_item');
    $items = Item::orderBy("status", "ASC")->whereNotIn('id',$items->toArray())->paginate(4);

    $orderItemUnconfirmed=OrderItem::
    whereHas('linkOrder',function($q) {
      $q->where('status', 15);
    })
    ->whereHas('linkOrder',function($q) {
      $q->whereHas('linkOrderTrack',function($q) {
        $q->where('status','!=', 25);
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
    $history = History::where('id_customer',$id)->with('linkItem')->get();
    $customer = Customer::where('id',$id)->with('linkCustomerType')->first();

    $orderItemUnconfirmed=OrderItem::
    whereHas('linkOrder',function($q) {
      $q->where('status', 15);
    })
    ->whereHas('linkOrder',function($q) {
      $q->whereHas('linkOrderTrack',function($q) {
        $q->where('status','!=', 25);
      });
    })
    ->select('id_item', DB::raw('SUM(kuantitas) as jumlah_blmkonfirmasi'))      
    ->groupBy('id_item')->pluck('jumlah_blmkonfirmasi','id_item')->all();


    return response()->json([
      "status" => "success",
      "data" => [
        "history" => $history,
        "customer" => $customer
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
  public function productList(){
      $products = Item::orderBy("status", "ASC")->get();
      return view('administrasi.stok.pengadaan.index', [
        "products" => $products,
        "title" => "Stok Marketing - Pengadaan",
      ]);
  }
  //opname
  public function productListOpname(){
      $products = Item::orderBy("status", "ASC")->get();
      return view('administrasi.stok.opname.index', [
        "products" => $products,
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

    $request->validate($rules);

    $data = [];
    $pengadaan_count="PGD-". explode("-",Pengadaan::orderBy("id", "DESC")->first()->no_pengadaan ?? 'PGD-0')[1] + 1 ."-".date_format(now(),"YmdHis");
    foreach($cartItems as $item){
      array_push($data,[
        'id_item' => $item->id,
        'id_staff' => auth()->user()->id_users,
        'no_pengadaan' => $pengadaan_count,
        'no_nota' => $request->no_nota,
        'kuantitas' => $item->quantity,
        'harga_total' => $request->harga_total,
        'keterangan' => $request->keterangan,
        'created_at' => now(),
      ]);
      $stok = Item::find($item->id);
      $stok->stok += $item->quantity;
      $stok->save();
    }

    Pengadaan::insert($data);
    \Cart::session(auth()->user()->id.$request->route)->clear();

    return redirect()->route('products.list')->with('pesanSukses', 'Produk berhasil ditambahkan ke database');
  }

  public function simpanDataOpname(Request $request){
    $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();

    $order_id= Order::insertGetId([
      'id_customer' => 0,
      'id_staff' => auth()->user()->id_users,
      'status' => 14,
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
      $stok = Item::find($item->id);
      $stok->stok +=  $item->attributes->jumlah;
      $stok->save();
    }

    OrderItem::insert($data);
    
    \Cart::session(auth()->user()->id.$request->route)->clear();

    return redirect('/administrasi/stok/')->with('pesanSukses', 'Produk berhasil ditambahkan ke database');
  }

    public function index(){      
        return view('administrasi.stok.produk.index', [
          'items' => Item::orderBy("status", "ASC")->paginate(10),
          "title" => "List Produk"
        ]);
    }

    public function create(){
      return view('administrasi.stok.produk.create', [
        'items' => Item::orderBy("status", "ASC")->get(),
        'statuses' => Status::where('tabel', 'items')->get(),
        "title" => "Stok Marketing - List Produk - Add"
      ]);
    }

    public function store(Request $request){
      $rules = ([
        'nama' => ['required', 'string', 'max:255'],
        'kode_barang' => ['required', 'string', 'max:20', 'unique:items'],
        'satuan' => ['required', 'string', 'max:30'],
        'harga_satuan' => ['required', 'numeric'],
        'gambar' => 'image|file|max:1024',
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

      // if($request->max_pengadaan){
      //   $rules['max_pengadaan'] = ['integer', 'min:0'];
      // }

      $validatedData = $request->validate($rules);

      $validatedData['status'] = $request->status;
      $validatedData['max_pengadaan'] = $request->max_stok??0 - $request->min_stok??0;

      if ($request->gambar) {
        $nama_item = str_replace(" ", "-", $request->nama);
        $file_name = 'ITM-' . $nama_item . '-' .date_format(now(),"YmdHis"). '.' . $request->foto->extension();
        $request->gambar->move(public_path('storage/item'), $file_name);
        $validatedData['gambar'] = $file_name;
      }    

      Item::create($validatedData);

      return redirect('/administrasi/stok/produk') -> with('pesanSukses', 'Produk berhasil ditambahkan' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      return view('administrasi.stok.produk.edit',[
        'item' => Item::where('id', $id)->first(),
        'statuses' => Status::where('tabel', 'items')->get(),
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $rules = ([
        'nama' => ['required', 'string', 'max:255'],
        'gambar' => 'image|file|max:1024',
        'min_stok' => ['required', 'integer', 'min:0'],
        'max_stok' => ['required', 'integer', 'min:0'],
        'satuan' => ['required', 'string', 'max:30'],
        'harga_satuan' => ['required', 'numeric'],
        'volume' => ['required'],
      ]);

      if($request->kode_barang !== Item::where('id', $id)->first()->kode_barang){
        $rules['kode_barang'] = ['required', 'string', 'max:20', 'unique:items'];
      }

      $validatedData = $request->validate($rules);
      $validatedData['status'] = $request->status;

      if ($request->gambar) {
        if($request->oldGambar){
          \Storage::delete('/item/'.$request->oldGambar);
        }

        $file= $request->file('gambar');
        $nama_item = str_replace(" ", "-", $validatedData['nama']);
        $file_name = 'ITM-' . $nama_item . '-' .date_format(now(),"YmdHis"). '.' . $file->getClientOriginalExtension();
        $request->gambar->move(public_path('storage/item'), $file_name);
        $validatedData['gambar'] = $file_name;
      }    

      Item::where('id', $id)->update($validatedData);
    
      return redirect('/administrasi/stok/produk') -> with('pesanSukses', 'Berhasil mengubah data');
    }

    public function customerIndex(){
      $customer = Customer::where('id', auth()->user()->id_users)->first();
        return view('customer/produk',[
            'items' => Item::where('status',10)->orderBy("status", "ASC")->get(),
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
        $items = Item::orderBy("status", "ASC")->paginate(10);
        return view('administrasi/stok.index',[
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
        
        return view('administrasi/stok/riwayat.index',[
            'pengadaans' => $pengadaans
        ]);
    }

    public function cariRiwayat()
    {
        $pengadaans = Pengadaan::select('no_pengadaan','no_nota','keterangan','created_at', DB::raw('SUM(harga_total) as harga'))
        ->where(strtolower('no_nota'),'like','%'.request('cari').'%')
        ->groupBy('no_pengadaan','no_nota','keterangan','created_at')->paginate(10);
        
        return view('administrasi/stok/riwayat.index',[
            'pengadaans' => $pengadaans
        ]);
    }

    public function cariRiwayatDetail(Pengadaan $pengadaan)
    {
        $pengadaans = Pengadaan::where('no_pengadaan','=',$pengadaan->no_pengadaan)
        ->with("linkItem")
        ->get();

        $total = Pengadaan::selectRaw('SUM(harga_total) as harga')
        ->where('no_pengadaan','=',$pengadaan->no_pengadaan)
        ->first();

        return view('administrasi/stok/riwayat.detail',[
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
        
        $pdf = PDF::loadview('administrasi/stok/riwayat.detail-pdf',[
          'pengadaans' => $pengadaans,
            'total_harga' => $total,
            'detail' => $pengadaan,
            'administrasi' => $administrasi            
        ]);

        return $pdf->stream('laporan-NPB-pdf-'.$pengadaan->no_pengadaan.'.pdf');
    }

    public function filterProdukApi(Request $request){
      $items=Item::orderBy('status','ASC');
      if ($request->nama??null) {
        $items=$items->where(strtolower('nama'),'like','%'.$request->nama.'%');
      }
      if ($request->filter=='price') {
        $items=$items->orderBy('harga_satuan',$request->order);
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

    public function produkSearch(){
      $items =  Item::where(strtolower('nama'),'like','%'.request('cari').'%')
        ->paginate(10);

      return view('administrasi.stok.produk.index', [
        'items' => $items,
        "title" => "List Produk"
      ]);
    }

    public function searchProductAPI($id, $name){
      $history = History::where('id_customer',$id)->with('linkItem')->get();
      $items = $history->pluck('id_item');
      $items = Item::orderBy("status", "ASC")->whereNotIn('id',$items->toArray())->where(strtolower('nama'), 'like', '%'.$name.'%')->paginate(4);
  
      $orderItemUnconfirmed=OrderItem::
      whereHas('linkOrder',function($q) {
        $q->where('status', 15);
      })
      ->whereHas('linkOrder',function($q) {
        $q->whereHas('linkOrderTrack',function($q) {
          $q->where('status','!=', 25);
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
      $history = History::where('id_customer',$id)->with('linkItem')->get();
      $items = $history->pluck('id_item');
      $items = Item::orderBy("status", "ASC")->whereNotIn('id',$items->toArray());

      $orderItemUnconfirmed=OrderItem::
      whereHas('linkOrder',function($q) {
        $q->where('status', 15);
      })
      ->whereHas('linkOrder',function($q) {
        $q->whereHas('linkOrderTrack',function($q) {
          $q->where('status','!=', 25);
        });
      })
      ->select('id_item', DB::raw('SUM(kuantitas) as jumlah_blmkonfirmasi'))      
      ->groupBy('id_item')->pluck('jumlah_blmkonfirmasi','id_item')->all();

      if($filterby == 'hargaterendah'){
        $items=$items->orderBy('harga_satuan','ASC')->paginate(4);
      }else if($filterby == 'hargatertinggi'){
        $items=$items->orderBy('harga_satuan','DESC')->paginate(4);
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
      $status = $item->status;
      $nama_status = Status::where('id', $status)->first()->nama; 

      if($nama_status === 'active'){
        Item::where('id', $item->id)->update(['status' => 11]);
      }else if($nama_status === 'inactive'){
        Item::where('id', $item->id)->update(['status' => 10]);
      }

      return redirect('/administrasi/stok/produk') -> with('pesanSukses', 'Berhasil ubah status' );
    }
}

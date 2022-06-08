<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pengadaan;
use App\Models\Status;
use App\Models\History;
use App\Models\Staff;
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

    $orderItemUnconfirmed=OrderItem::
    whereHas('linkOrder',function($q) {
      $q->where('status', 15);
    })->select('id_item', DB::raw('SUM(kuantitas) as jumlah_blmkonfirmasi'))      
    ->groupBy('id_item')->pluck('id_item', 'jumlah_blmkonfirmasi');

    return response()->json([
      "status" => "success",
      "data" => $history,
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

  public function searchProductAPI($name){
    try {
      $items = DB::table('items')->where(strtolower('nama'), 'like', '%'.$name.'%')->orderBy("status", "ASC")->paginate(4);
      $this->data = $items;
      $this->status = "success";
    } catch (QueryException $e) {
        $this->status = "failed";
        $this->error = $e;
    }

    return response()->json([
      "status" => $this->status,
      "data" => $this->data,
      "error" => $this->error
    ], 200);
  }

  //pengadaan
  public function productList(){
      $products = Item::all();
      return view('administrasi.stok.pengadaan.index', [
        "products" => $products,
        "title" => "Stok Marketing - Pengadaan",
      ]);
  }
//opname
  public function productListOpname()
  {
      $products = Item::all();
      return view('administrasi.stok.opname.index', [
        "products" => $products,
        "title" => "Stok Marketing - opname",
      ]);
  }

  public function riwayatOpname()
  {
      $orders = Order::where('id_customer',0)->with(['linkStaff'])->paginate();
      return view('administrasi.stok.opname.riwayat', [
        "orders" => $orders
      ]);
  }

  public function detailRiwayatOpname(Order $order)
  {
      $order_items = OrderItem::where('id_order',$order->id)->with(['linkItem'])->paginate();
      
      return view('administrasi.stok.opname.riwayatdetail', [
        "order_items" => $order_items
      ]);
  }

  public function simpanDataPengadaan(Request $request)
  {
    $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();

    $rules = ([
      'no_nota' => ['required', 'max:20'],
      'harga_total' => ['required'],
      'keterangan' => ['required', 'string', 'max:255'],
    ]);

    $request->validate($rules);

    $data = [];
    foreach($cartItems as $item){
      array_push($data,[
        'id_item' => $item->id,
        'id_staff' => auth()->user()->id_users,
        'no_pengadaan' => (Pengadaan::orderBy("no_pengadaan", "DESC")->first()->no_pengadaan ?? 0) + 1,
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

  public function simpanDataOpname(Request $request)
  {
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
      ]);
      $stok = Item::find($item->id);
      $stok->stok +=  $item->attributes->jumlah;
      $stok->save();
    }

    OrderItem::insert($data);

    
    \Cart::session(auth()->user()->id.$request->route)->clear();

    return redirect('/administrasi/stok/')->with('pesanSukses', 'Produk berhasil ditambahkan ke database');
  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {      
        return view('administrasi.stok.produk.index', [
          'items' => Item::paginate(5),
          "title" => "List Produk"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      // // (Staff::with('linkStaffRole')->find($staff)->linkStaffRole->nama=="supervisor")
      // dd(Item::with('linkStatus')->linkStatus->nama);

      return view('administrasi.stok.produk.create', [
        'items' => Item::all(),
        'statuses' => Status::where('tabel', 'items')->get(),
        "title" => "Stok Marketing - List Produk - Add"
      ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        $file_name = time() . '.' . $request->gambar->extension();
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
        $file_name = time() . '.' . $request->gambar->extension();
        $request->gambar->move(public_path('storage/item'), $file_name);
        $validatedData['gambar'] = $file_name;
      }    

      Item::where('id', $id)->update($validatedData);
    
      return redirect('/administrasi/stok/produk') -> with('pesanSukses', 'Berhasil mengubah data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function customerIndex()
    {
        return view('customer/produk',[
            'items' => Item::all()
        ]);
    }

    public function itemSearch()
    {
        $items = DB::table('items')->where(strtolower('nama'),'like','%'.request('cari').'%')->get();
       
        return view('customer/produk',[
            'items' => $items
        ]);
    }

    public function indexAdministrasi()
    {
        $items = Item::paginate(5);
        return view('administrasi/stok.index',[
            'items' => $items
        ]);
        
    }

    public function cariStok()
    {
        $items = Item::where(strtolower('nama'),'like','%'.request('cari').'%')
        ->orWhere(strtolower('kode_barang'),'like','%'.request('cari').'%')
        ->paginate(5);
               
        return view('administrasi/stok.index',[
            'items' => $items
        ]);
    }

    public function riwayatAdministrasi()
    {
        $pengadaans = Pengadaan::select('no_pengadaan','no_nota','keterangan','created_at', DB::raw('SUM(harga_total) as harga'))
        ->groupBy('no_pengadaan','no_nota','keterangan','created_at')->paginate(5);
        
        return view('administrasi/stok/riwayat.index',[
            'pengadaans' => $pengadaans
        ]);
        
    }

    public function cariRiwayat()
    {
        $pengadaans = Pengadaan::select('no_pengadaan','no_nota','keterangan','created_at', DB::raw('SUM(harga_total) as harga'))
        ->where(strtolower('no_nota'),'like','%'.request('cari').'%')
        ->groupBy('no_pengadaan','no_nota','keterangan','created_at')->paginate(5);
        
        return view('administrasi/stok/riwayat.index',[
            'pengadaans' => $pengadaans
        ]);
    }

    public function cariRiwayatDetail(Pengadaan $pengadaan)
    {
        $pengadaans = Pengadaan::join('items','pengadaans.id_item','=','items.id')
        ->where('no_pengadaan','=',$pengadaan->no_pengadaan)
        ->get();

        $total = Pengadaan::selectRaw('SUM(harga_total) as harga')
        ->where('no_pengadaan','=',$pengadaan->no_pengadaan)
        ->get();

        return view('administrasi/stok/riwayat.detail',[
            'pengadaans' => $pengadaans,
            'total_harga' => $total,
            'detail' => $pengadaan
        ]);
    }

    public function cetakPDF(Pengadaan $pengadaan)
    {
      
        $pengadaans = Pengadaan::join('items','pengadaans.id_item','=','items.id')
        ->where('no_pengadaan','=',$pengadaan->no_pengadaan)
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

        return $pdf->download('laporan-NPB-pdf-'.$pengadaan->no_pengadaan.'.pdf');

        
    }

    public function filterProdukApi(Request $request)
    {
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

        if(!empty($items)){
          return response()->json([
            'html'=> view('customer.c_listproduk', compact('items'))->render(),
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
        ->paginate(5);

      return view('administrasi.stok.produk.index', [
        'items' => $items,
        "title" => "List Produk"
      ]);
    }
}

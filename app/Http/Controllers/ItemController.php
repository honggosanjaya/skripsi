<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Pengadaan;
use App\Models\Status;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ItemController extends Controller
{
  public function productList()
  {
      $products = Item::all();
      return view('administrasi.stok.pengadaan.index', [
        "products" => $products,
        "title" => "Stok Marketing - Pengadaan",
      ]);
  }

  public function simpanDataPengadaan(Request $request)
  {
    $cartItems = \Cart::getContent();

    $data = [];
    foreach($cartItems as $item){
      array_push($data,[
        'id_item' => $item->id,
        'id_staff' => auth()->user()->id,
        'no_pengadaan' => (Pengadaan::orderBy("no_pengadaan", "DESC")->first()->no_pengadaan ?? 0) + 1,
        'no_nota' => $request->no_nota,
        'kuantitas' => $item->quantity,
        'harga_total' => $request->harga_total,
        'keterangan' => $request->keterangan,
      ]);
    }

    $rules = ([
      'no_nota' => ['required', 'max:20'],
      'harga_total' => ['required'],
      'keterangan' => ['required', 'string', 'max:255'],
    ]);

    $request->validate($rules);

    Pengadaan::insert($data);
  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {      
        return view('administrasi.stok.produk.index', [
          'items' => Item::all(),
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

      if($request->max_pengadaan){
        $rules['max_pengadaan'] = ['integer', 'min:0'];
      }

      $validatedData = $request->validate($rules);

      $validatedData['status'] = $request->status;

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
        'stok' => ['required', 'integer', 'min:0'],
        'min_stok' => ['required', 'integer', 'min:0'],
        'max_stok' => ['required', 'integer', 'min:0'],
        'max_pengadaan' => ['required', 'integer', 'min:0'],
        'satuan' => ['required', 'string', 'max:30'],
        'harga_satuan' => ['required', 'numeric'],
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

    public function customerSearch()
    {
        $items = DB::table('items')->where(strtolower('nama'),'like','%'.request('cari').'%')->get();
       
        return view('customer/produk',[
            'items' => $items
        ]);
    }

    public function indexAdministrasi()
    {
        $items = Item::paginate(3);
        return view('administrasi/stok.index',[
            'items' => $items
        ]);
        
    }

    public function cariStok()
    {
        $items = Item::where(strtolower('nama'),'like','%'.request('cari').'%')->paginate(5);
               
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
        ->get();

        $pdf = FacadePdf::loadview('administrasi/stok/riwayat.detail-pdf',[
          'pengadaans' => $pengadaans,
            'total_harga' => $total,
            'detail' => $pengadaan
        ]);

        return $pdf->download('laporan-NPB-pdf.pdf');
    }
}

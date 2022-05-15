<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use App\Models\Toko;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class StokController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/produk/produk', [
          'items' => Item::all(),
          /* Contoh Logika
          'testing' => Order::with(['linkOrderTrack'=>function($q){
            $q->whereNotNull('waktu_diteruskan')->whereNull('waktu_dikonfirmasi');
        }]);*/
        ]);
    }

    public function index2()
    {
      return view('admin.produk.ubahStok', [
        'items' => Item::all()
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
      $items = Item::where('id', $id)->first();
      $stok_saatini = $items->stok;

      $validation = ([
        "aksi" => 'nullable',
        "stok" => 'required|integer|min:1',
      ]);

      if($request->aksi === 'dikurangi'){
        $validation['stok'] = 'required|integer|max:'.$stok_saatini;
      } 

      $validatedData = $request->validate($validation);
      $validatedData['aksi'] = $validatedData['aksi'] ?? 'ditambah';

      if($validatedData['aksi'] === 'dikurangi'){
        Item::where('id', $id)->update([
          'stok' => $stok_saatini - $validatedData['stok']
        ]);
      }else{
        Item::where('id', $id)->update([
          'stok' => $stok_saatini + $validatedData['stok']
        ]);
      }
      
      $dataOrder = Order::create([
        'toko_id' => Toko::where('nama', 'Toko Admin')->first()->id,
        'sales_id' => Auth::user()->id,
      ]);

      if($validatedData['aksi'] === 'dikurangi'){
        OrderItem::create([
          'order_id' => $dataOrder->id,
          'item_id' => $id,
          'jumlah_item' => $validatedData['stok'],
          'keterangan' => 'dikurangi oleh admin yang bernama '.Auth::user()->nama
        ]);
      }else{
        OrderItem::create([
          'order_id' => $dataOrder->id,
          'item_id' => $id,
          'jumlah_item' => $validatedData['stok'],
          'keterangan' => 'ditambah oleh admin yang bernama '.Auth::user()->nama
        ]);
      }
            
    
      if($validatedData['aksi'] === 'dikurangi'){
        return redirect('/dashboard/produk/stok/edit') -> with('successMessage', 'stok ' .$items->nama_barang. ' dikurangi ' .$validatedData['stok']);
      } else{
        return redirect('/dashboard/produk/stok/edit') -> with('successMessage', 'stok ' .$items->nama_barang. ' ditambah ' .$validatedData['stok']);
      }
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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/produk/produk', [
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
      return view('admin/produk/addProduk',[
        
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
        $validatedData = $request->validate([
          'nama_barang' => 'required|max:255|unique:items',
          'stok' => 'required|integer|min:1',
          'satuan' => 'required|max:255',
          'harga_satuan' => 'required|numeric',
          'status_produk' => 'required|in:aktif,nonaktif'
        ]);

        Item::create($validatedData);
        return redirect('/dashboard/produk') -> with('successMessage', 'Berhasil menambahkan sebuah item' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin/produk/detailProduk', [
          'barang' => Item::where('id', $id)->first()
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin/produk/editProduk', [
          'barang' => Item::where('id', $id)->first()
        ]);
    }

    public function ubahstatus($id)
    {
      $status = Item::where('id', $id)->first()->status_produk;
      
      if($status === 'aktif'){
        Item::where('id', $id)->update(['status_produk' => 'nonaktif']);
      }else if($status === 'nonaktif'){
        Item::where('id', $id)->update(['status_produk' => 'aktif']);
      }      

      return response()->json([
        'status'=> Item::where('id', $id)->first()->status_produk,
        'successMessage' => 'Berhasil mengubah status '.Item::where('id', $id)->first()->nama_barang,
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
      $validation = ([
        'stok' => 'required|integer',
        'satuan' => 'required|max:255',
        'harga_satuan' => 'required|numeric',
        'status_produk' => 'required|in:aktif,nonaktif'
      ]);

      if($request->nama_barang !== Item::where('id', $id)->first()->nama_barang){
        $validation['nama_barang'] = 'required|max:255|unique:items';
      }

      $validatedData = $request->validate($validation);

      Item::where('id', $id)->update($validatedData);
    
      return redirect('/dashboard/produk') -> with('successMessage', 'Berhasil mengubah data '.Item::where('id', $id)->first()->nama_barang);
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

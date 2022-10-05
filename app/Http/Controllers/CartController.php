<?php

namespace App\Http\Controllers;

use App\Models\CashAccount;
use Illuminate\Http\Request;
use App\Models\Customer;

class CartController extends Controller
{
  public function cartList(Request $request)
  {
    session(['counterPengadaan' => 0]);
    session(['counterOpname' => 0]);
    session(['counterStokRetur' => 0]);
    
    if ($request->route=="pengadaan" || $request->route=="stokretur") {
      $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();
      $defaultpengadaan = CashAccount::where('default', '1')->first();
      $listskas = CashAccount::where('account', '<=', '100')
                  ->where(function ($query) {
                    $query->whereNull('default')->orWhereIn('default', ['1', '2']);                  
                  })->get();
      
      if($request->route=="stokretur"){
        $shouldShowKas = false;
        foreach($cartItems as $item){
          if($item->attributes->metode == 'potongan'){
            $shouldShowKas = true;
          }
        }
        return view('administrasi.stok.stokretur.cart', compact('cartItems','defaultpengadaan', 'listskas', 'shouldShowKas'));
      }elseif($request->route=="pengadaan"){
        return view('administrasi.stok.pengadaan.cart', compact('cartItems','defaultpengadaan', 'listskas'));
      }
    }elseif($request->route=="customerOrder") {
      $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();
      $customer = Customer::where('id', auth()->user()->id_users)->first();
      return view('customer.cart', compact('cartItems','customer'));
    }elseif($request->route=="opname") {
      $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();
      return view('administrasi.stok.opname.cart', compact('cartItems'));
    }
  }

  public function addToCart(Request $request)
  {
    $cartItem = \Cart::session(auth()->user()->id.$request->route)->get($request->id);

    if ($request->route=="pengadaan") {
      if($cartItem !== null){
        \Cart::session(auth()->user()->id.$request->route)->update(
          $request->id,
          [
              'quantity' => [
                  'relative' => false,
                  'value' => $request->quantity
              ],
              'attributes' => array(
                'kode_barang' => $request->kode_barang,
                'satuan' => $request->satuan,
                'max_pengadaan' => $request->max_pengadaan,
                'total_harga' => $request->total_harga
              )
          ]
        );
  
        if($cartItem->quantity == 0){
          \Cart::session(auth()->user()->id.$request->route)->remove($request->id);
        }
  
      } else if($cartItem == null){
        \Cart::session(auth()->user()->id.$request->route)->add([
          'id' => $request->id,
          'name' => $request->nama,
          'price' => $request->harga_satuan,
          'quantity' => $request->quantity,
          'attributes' => array(
            'kode_barang' => $request->kode_barang,
            'satuan' => $request->satuan,
            'max_pengadaan' => $request->max_pengadaan,
            'total_harga' => $request->total_harga
          )
        ]);
      }

      return response()->json([
        'status' => 'success',
        'message' => 'Produk berhasil ditambahkan ke keranjang'
      ]);
  
      // return redirect()->route('products.list')->with('pesanSukses', 'Produk berhasil ditambahkan ke keranjang');
    }elseif ($request->route=="customerOrder") {
      if($cartItem !== null){
        \Cart::session(auth()->user()->id.$request->route)->update(
          $request->id,
          [
              'quantity' => [
                  'relative' => false,
                  'value' => $request->quantity
              ],
          ]
        );
  
        if($cartItem->quantity == 0){
          \Cart::session(auth()->user()->id.$request->route)->remove($request->id);
        }
  
      } else if($cartItem == null){
        \Cart::session(auth()->user()->id.$request->route)->add([
          'id' => $request->id,
          'quantity' => $request->quantity,
          'name' => $request->nama,
          'price' => $request->harga_satuan,
        ]);
      }
      return response()->json([
        'status' => 'success',
        'quantityCart'=>\Cart::session(auth()->user()->id.$request->route)->getTotalQuantity()
      ]); 
      
    }elseif ($request->route=="opname") {
      $rules = ([
        'jumlah' => ['required'],
        'keterangan' => ['required', 'string', 'max:255'],
      ]);
  
      $request->validate($rules);

      if($cartItem !== null){
        \Cart::session(auth()->user()->id.$request->route)->update(
          $request->id,
          [
              'attributes' => ['jumlah' => $request->jumlah,'keterangan' => $request->keterangan,'kode_barang' => $request->kode_barang]
          ]
        );
  
        if($request->jumlah == 0){
          \Cart::session(auth()->user()->id.$request->route)->remove($request->id);
        }
  
      } else if($cartItem == null){
        \Cart::session(auth()->user()->id.$request->route)->add([
          'id' => $request->id,
          'quantity' => $request->quantity,
          'name' => $request->nama,
          'price' => $request->harga_satuan,
          'attributes' => ['jumlah' => $request->jumlah,'keterangan' => $request->keterangan,'kode_barang' => $request->kode_barang]
        ]);      
      }

      return response()->json([
        'status' => 'success',
        'message' => 'Produk berhasil ditambahkan ke keranjang'
      ]);
      // return redirect('/administrasi/stok/opname/')->with('pesanSukses', 'Produk berhasil ditambahkan ke keranjang');
    }elseif ($request->route=="stokretur") {
      if($cartItem !== null){
        \Cart::session(auth()->user()->id.$request->route)->update(
          $request->id,
          [
              'quantity' => [
                  'relative' => false,
                  'value' => $request->quantity
              ],
              'attributes' => array(
                'kode_barang' => $request->kode_barang,
                'satuan' => $request->satuan,
                'stok' => $request->stok,
                'stok_retur' => $request->stok_retur,
                'metode' => $request->metode
              )
          ]
        );
        if($cartItem->quantity == 0){
          \Cart::session(auth()->user()->id.$request->route)->remove($request->id);
        }
  
      } else if($cartItem == null){
        \Cart::session(auth()->user()->id.$request->route)->add([
          'id' => $request->id,
          'name' => $request->nama,
          'quantity' => $request->quantity,
          'price' => 0,
          'attributes' => array(
            'kode_barang' => $request->kode_barang,
            'satuan' => $request->satuan,
            'stok' => $request->stok,
            'stok_retur' => $request->stok_retur,
            'metode' => $request->metode
          )
        ]);
      }

      return response()->json([
        'status' => 'success',
        'message' => 'Produk berhasil ditambahkan ke keranjang'
      ]);
    }
  }

  public function updateCart(Request $request)
  {
    if ($request->route=="pengadaan"){
      \Cart::session(auth()->user()->id.$request->route)->update(
          $request->id,
          [
              'quantity' => [
                  'relative' => false,
                  'value' => $request->quantity
              ],
          ]
      );
      return redirect()->route('cart.list');
    }elseif ($request->route=="opname") {
      \Cart::session(auth()->user()->id.$request->route)->update(
        $request->id,
        [
          'attributes' => ['jumlah' => $request->jumlah,'keterangan' => $request->keterangan,'kode_barang' => $request->kode_barang]
        ]
    );
    $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();
    return view('administrasi.stok.opname.cart', compact('cartItems'));
    }
  }

  public function removeCart(Request $request)
  {
      \Cart::session(auth()->user()->id.$request->route)->remove($request->id);

      if ($request->route=="pengadaan") {
        $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();
        return response()->json([
          'status' => 'success',
          'message' => 'Produk berhasil dihapus dari keranjang'
        ]);

        // return view('administrasi.stok.pengadaan.cart', compact('cartItems'));
      }elseif($request->route=="customerOrder") {
        $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();
        $customer = Customer::where('id', auth()->user()->id_users)->first();
        return view('customer.cart', compact('cartItems','customer'));
      }elseif($request->route=="opname") {
        $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();
        return view('administrasi.stok.opname.cart', compact('cartItems'));
      }
  }

  public function clearAllCart(Request $request)
  {
      \Cart::session(auth()->user()->id.$request->route)->clear();

      if ($request->route=="pengadaan") {
        $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();
        return redirect('/administrasi/stok/pengadaan/cart?route=pengadaan');
        // return view('administrasi.stok.pengadaan.cart', compact('cartItems'));
      }elseif($request->route=="customerOrder") {
        $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();
        $customer = Customer::where('id', auth()->user()->id_users)->first();
        return view('customer.cart', compact('cartItems','customer'));
      }elseif($request->route=="opname") {
        $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();
        return view('administrasi.stok.opname.cart', compact('cartItems'));
      }elseif($request->route=="stokretur") {
        $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();
        return redirect('/administrasi/stok/stokretur/cart?route=stokretur');
        // return view('administrasi.stok.stokretur.cart', compact('cartItems'));
      }
  }
}

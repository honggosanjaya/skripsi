<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
  public function cartList(Request $request)
  {
    if ($request->route=="pengadaan") {
      $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();
      return view('administrasi.stok.pengadaan.cart', compact('cartItems'));
    }elseif($request->route=="customerOrder") {
      $cartItems = \Cart::session(auth()->user()->id.$request->route)->getContent();
      return view('customer.cart', compact('cartItems'));
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
  
      return redirect()->route('products.list')->with('pesanSukses', 'Produk berhasil ditambahkan ke keranjang');
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
        'status' => 'success'
      ]); 
    }
  }

  public function updateCart(Request $request)
  {
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
  }

  public function removeCart(Request $request)
  {
      \Cart::session(auth()->user()->id.$request->route)->remove($request->id);
      return redirect()->route('cart.list');
  }

  public function clearAllCart(Request $request)
  {
      \Cart::session(auth()->user()->id.$request->route)->clear();
      return redirect()->route('cart.list');
  }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
  public function cartList()
  {
      $cartItems = \Cart::getContent();
      return view('administrasi.stok.pengadaan.cart', compact('cartItems'));
  }

  public function addToCart(Request $request)
  {
    $cartItem = \Cart::get($request->id);

    if($cartItem !== null){
      \Cart::update(
        $request->id,
        [
            'quantity' => [
                'relative' => false,
                'value' => $request->quantity
            ],
        ]
      );

      if($cartItem->quantity == 0){
        \Cart::remove($request->id);
      }

    } else if($cartItem == null){
      \Cart::add([
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
  }

  public function updateCart(Request $request)
  {
      \Cart::update(
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
      \Cart::remove($request->id);
      return redirect()->route('cart.list');
  }

  public function clearAllCart()
  {
      \Cart::clear();
      return redirect()->route('cart.list');
  }
}

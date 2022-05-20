<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
  public function cartList()
  {
      $cartItems = \Cart::getContent();
      // dd($cartItems);
      return view('administrasi.stok.pengadaan.cart', compact('cartItems'));
  }


  public function addToCart(Request $request)
  {
    // dd($request->nama);
      \Cart::add([
          'id' => $request->id,
          'name' => $request->nama,
          'price' => $request->harga_satuan,
          'quantity' => $request->quantity,
          // sisanya
          'attributes' => array(
              'gambar' => $request->gambar,
          )
      ]);
      session()->flash('sukses', 'Product is Added to Cart Successfully !');
      return redirect()->route('products.list');
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

      session()->flash('success', 'Item Cart is Updated Successfully !');

      return redirect()->route('cart.list');
  }

  public function removeCart(Request $request)
  {
      \Cart::remove($request->id);
      session()->flash('success', 'Item Cart Remove Successfully !');

      return redirect()->route('cart.list');
  }

  public function clearAllCart()
  {
      \Cart::clear();

      session()->flash('success', 'All Item Cart Clear Successfully !');

      return redirect()->route('cart.list');
  }
}

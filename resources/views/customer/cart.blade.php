@extends('customer.layouts.customerLayouts')

@section('header')
  <header class='header_mobile d-flex justify-content-between align-items-center'>
    <div class="d-flex">
      <a href="/customer/produk">
        <span class="iconify fs-3 text-white me-2" data-icon="eva:arrow-back-fill"></span>
      </a>
      <h1 class="page_title">Produk</h1>
    </div>
  </header>
@endsection

@section('content')
  <div class="container">
    <table class="table mt-4">
      <thead>
        <th scope="col" class="text-center">Nama</th>
        <th scope="col" class="text-center">Kuantitas</th>
        <th scope="col" class="text-center">Harga (Rp)</th>
      </thead>
      <tbody>
        @php
          $total = 0;
          $t_items = 0;
        @endphp
        @foreach ($cartItems as $item)
          <tr>
            @php
              $t_items = $item->quantity * ($item->price - ($item->price * $customer->linkCustomerType->diskon) / 100);
              $total += $t_items;
            @endphp
            <td>{{ $item->name }}</td>
            <td class="text-center">{{ $item->quantity . ' X ' . $item->price - ($item->price * $customer->linkCustomerType->diskon) / 100 }}</td>
            <td class="text-center"> {{ $t_items }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <td colspan="2" class="table-active">Sub-Total</td>
        <td>Rp {{ number_format($total, 0, '', '.') }}</td>
      </tfoot>
    </table>

    <div class="d-flex justify-content-between">
      <a type="button" class="btn btn-danger me-3 btn_deleteCart" href="/customer/produk/cart/clear?route=customerOrder">
        <span class="iconify fs-3 me-1" data-icon="bxs:trash"></span>Remove All Cart
      </a>
      <a href="/customer/produk/cart/tambahorder?route=customerOrder" type="button"
        class="btn btn-success checkout_btn"><span class="iconify fs-3 me-2"
          data-icon="ic:baseline-shopping-cart-checkout"></span>Checkout</a>
    </div>
  </div>
@endsection

@extends('customer.layouts.customerLayouts')

@section('content')
    <h1>Ini dashboard customer</h1>

    <p class="fw-bold">Produk Favorit Bulan Mei</p>

    <table border="1" class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">Kode Barang</th>
          <th scope="col">Nama Barang</th>
          <th scope="col">Penjualan</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>K001</td>
          <td>Sapu</td>
          <td>5000000</td>
        </tr>
        <tr>
          <td>K002</td>
          <td>Lidi</td>
          <td>6000000</td>
        </tr>
      </tbody>
    </table>
@endsection

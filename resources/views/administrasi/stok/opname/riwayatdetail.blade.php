@extends('layouts.main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/opname/riwayat">Riwayat Stok Opname</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
@endsection
@section('main_content')
  @push('CSS')
    <script src="{{ mix('css/administrasi.css') }}"></script>
  @endpush

  <div id="opname" class="pt-4 px-5">
    <h1 class="fs-4 fw-bold mb-3">Riwayat Opname</h1>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col" class="text-center">Kode Barang</th>
          <th scope="col">Nama Barang</th>
          <th scope="col">Kuantitas</th>
          <th scope="col">Keterangan</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($order_items as $order_item)
          <tr>
            <td>{{ $order_item->linkItem->kode_barang }}</td>
            <td>{{ $order_item->linkItem->nama }}</td>
            <td>{{ $order_item->kuantitas }}</td>
            <td>{{ $order_item->keterangan }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection

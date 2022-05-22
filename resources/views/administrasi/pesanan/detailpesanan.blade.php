<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@extends('layouts/main')
@push('CSS')
<link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
  <li class="breadcrumb-item" aria-current="page"><a href="/administrasi/pesanan">Pesanan</a></li>
  <li class="breadcrumb-item active" aria-current="page">Detail Pesanan</li>
</ol>
@endsection
@section('main_content')
@if(session()->has('addPesananSuccess'))
<div id="hideMeAfter3Seconds">
  <div class="alert alert-success alert-dismissible fade show mt-4" role="alert" >
    {{ session('addPesananSuccess') }}
    <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
</div>
</div>
            
@endif


<div class="container">
  <div class="row mt-3">
      <div class="d-flex flex-row justify-content-end">
        <a href="/administrasi/pesanan/detail/{{ $order->id }}/cetak-memo" class="btn btn-primary mx-1"><i class="bi bi-download px-1"></i>Unduh Memo Persiapan Barang</a>
        <a href="/administrasi/pesanan/detail/{{ $order->id }}/cetak-invoice" class="btn btn-secondary mx-1"><i class="bi bi-download px-1"></i>Unduh Invoice</a>
        <a href="/administrasi/pesanan/detail/{{ $order->id }}/cetak-sj" class="btn btn-success mx-1"><i class="bi bi-download px-1"></i>Unduh Surat Jalan</a>
      </div>    
  </div>

  <div class="row mt-5">
      <div class="col-3">
        <h5>Customer Pemesan : </h5>
      </div>
      <div class="col-3">
        <p>{{ $order->linkCustomer->nama }}</p>
      </div>
      <div class="col-3">
        <h5>Staff Bersangkutan : </h5>
      </div>
      <div class="col-3">
        <p>{{ $order->linkStaff->nama }}</p>
      </div>
  </div>

  <div class="row">
    <div class="col-3">
        <h5>Nomor Invoice : </h5>
      </div>
      <div class="col-3">
        <p>{{ $order->linkInvoice->nomor_invoice }}</p>
      </div>
    <div class="col-3">
        <h5>Tanggal Pesan : </h5>
      </div>
      <div class="col-3">
        <p>{{ date('d-m-Y', strtotime($order->linkInvoice->created_at)) }}</p>
      </div>
  </div>

  <div class="row">
    <div class="col-3">
        <h5>Status Pesanan : </h5>
      </div>
      <div class="col-3">
        @if ($order->linkStatus->id === 14 )
            <p class="text-success fw-bold">{{ $order->linkStatus->nama }}</p>
        @else
            <p class="text-danger fw-bold">{{ $order->linkStatus->nama }}</p>
        @endif
        
      </div>    
  </div>

  <div>
    <table class="table table-bordered mt-4">
        <thead>
          <tr>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Harga Satuan</th>
            <th scope="col">Kuantitas</th>
            <th scope="col">Harga Total</th>                
          </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->linkItem->kode_barang }}</td>
                    <td>{{ $item->linkItem->nama }}</td>
                    <td>{{ number_format($item->harga_satuan,0,"",".") }}</td>
                    <td>{{ $item->kuantitas }}</td>
                    <td>{{ number_format($item->harga_satuan * $item->kuantitas,0,"",".") }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-center fw-bold">Total : </td>
                <td>{{ number_format($order->linkInvoice->harga_total,0,"",".") }}</td>
            </tr>
        </tbody>
      </table>
  </div>


</div>



@endsection
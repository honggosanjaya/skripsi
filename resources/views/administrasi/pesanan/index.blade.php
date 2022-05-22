<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@extends('layouts/main')
@push('CSS')
<link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Pesanan</li>
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
  <div class="row">
    <div class="col-5">
      <div class="mt-3 search-box">
        <form method="GET" action="/administrasi/pesanan/cari">
          <div class="input-group">
            <input type="text" class="form-control" name="cari" placeholder="Cari Pesanan..."
            value="{{ request('cari') }}">
            <button type="submit" class="btn btn-primary">Cari</button>   
          </div>
          
        </form>    
        
      </div>
    </div>
    
  </div>
</div>


<table class="table table-bordered mt-4">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Nama Customer</th>
      <th scope="col">Nama Staff yang bersangkutan</th>
      <th scope="col">Nomor Invoice</th>
      <th scope="col">Harga Total</th>
      <th scope="col">Status Pesanan</th>
      <th scope="col">Tanggal Dibuat</th>
      <th scope="col">Action</th>      
    </tr>
  </thead>
  <tbody>
    @foreach ($orders as $order)
        <tr>
            <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
            <td>{{ $order->linkCustomer->nama }}</td>
            <td>{{ $order->linkStaff->nama }}</td>
            <td>{{ $order->linkInvoice->nomor_invoice }}</td>
            <td>{{ $order->linkInvoice->harga_total }}</td>
            <td>{{ $order->linkStatus->nama }}</td>
            <td>{{ date('d-m-Y', strtotime($order->linkInvoice->created_at)) }}</td>
            <td>
                <a href="/administrasi/pesanan/detail/{{ $order->id }}" class="btn btn-primary">Detail</a>
            </td>
        </tr>
    @endforeach
  </tbody>
</table>

<div class="d-flex flex-row mt-4">
 {{ $orders->links() }}
</div>

@endsection
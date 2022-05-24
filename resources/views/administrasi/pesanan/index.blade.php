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
    <div class="col-4 mt-3">
      <button type="button" class="btn btn-primary ml-5" data-bs-toggle="modal" data-bs-target="#filterDate">
        <i class="bi bi-funnel-fill fs-6"></i> Filter
      </button> 
    </div>
    
  </div>

  <div class="modal fade" id="filterDate" tabindex="-1" aria-labelledby="filterDateLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
              <input type="date" class="form-control @error('tanggal_awal') is-invalid @enderror" id="tanggal_awal"
              name="tanggal_awal" value="{{ old('tanggal_awal') }}">
              @error('tanggal_awal')
              <div class="invalid-feedback">
              {{ $message }}
              </div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
              <input type="date" class="form-control @error('tanggal_akhir') is-invalid @enderror" id="tanggal_akhir"
              name="tanggal_akhir" value="{{ old('tanggal_akhir') }}">
              @error('tanggal_akhir')
              <div class="invalid-feedback">
              {{ $message }}
              </div>
              @enderror
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-filter-produk" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary submit-filter-produk"><i class="bi bi-search"></i> Cari</button>
          </div>
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
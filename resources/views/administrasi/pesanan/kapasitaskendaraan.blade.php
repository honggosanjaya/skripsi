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



<div class="container">
  <div class="row mt-3">
      <div class="d-flex flex-row justify-content-between">
        <a href="/administrasi/pesanan/detail/{{ $order->id }}" class="btn btn-primary mx-1"><i class="bi bi-arrow-left-short fs-5"></i>Kembali</a>
        
        
      </div>    
  </div>
  <div class="row mt-3">
    <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#volume">Volume</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#harga">Harga</a>
        </li>        
    </ul>

    <div class="tab-content">
      <div class="tab-pane fade show active" id="volume">

        <div class="progress">
          <div class="progress-bar" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        
      </div>
    </div>

  </div>

  
  </div>






@endsection
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
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="volume-tab" href="#volume" data-bs-toggle="tab" data-bs-target="#volume"
          role="tab" aria-controls="volume" aria-selected="true">Volume</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="harga-tab" href="#harga" data-bs-toggle="tab" data-bs-target="#harga"
          role="tab" aria-controls="harga" aria-selected="false">Harga</a>
        </li>        
    </ul>

    <div class="tab-content clearfix">
      <div class="tab-pane fade show active" id="volume" role="tabpanel" aria-labelledby="volume-tab">
        <h3 class="mt-3">Persentase Volume setiap Kendaraan</h3>
        @foreach($persentaseVolumes as $persentaseVolume)
          <h4 class="mt-4">Kendaraan : <span class="fw-normal">{{ $persentaseVolume[0] }}</span></h4>
          <h5>Plat Nomor : <span class="fw-normal">{{ $persentaseVolume[1] }}</span></h5>
          <h6>Persentase : <span class="fw-normal">{{ number_format($persentaseVolume[2],2,",",".") }} %</span></h6>
          <div class="progress">
            @if ( $persentaseVolume[2] >= 50 && $persentaseVolume[2] < 75)
            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $persentaseVolume[2] }}%" aria-valuenow="{{ $persentaseVolume[2] }}" aria-valuemin="0" aria-valuemax="100"></div>
            @elseif($persentaseVolume[2] >= 75)
            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $persentaseVolume[2] }}%" aria-valuenow="{{ $persentaseVolume[2] }}" aria-valuemin="0" aria-valuemax="100"></div>
            @else
            <div class="progress-bar" role="progressbar" style="width: {{ $persentaseVolume[2] }}%" aria-valuenow="{{ $persentaseVolume[2] }}" aria-valuemin="0" aria-valuemax="100"></div>
            @endif            
          </div>
        @endforeach  
        
      </div> 
      
      <div class="tab-pane" id="harga" role="tabpanel" aria-labelledby="harga-tab">
        <h3 class="mt-3">Persentase Harga setiap Kendaraan</h3>
        @foreach($persentaseHargas as $persentaseHarga)
          <h4 class="mt-4">Kendaraan : <span class="fw-normal">{{ $persentaseHarga[0] }}</span></h4>
          <h5>Plat Nomor : <span class="fw-normal">{{ $persentaseHarga[1] }}</span></h5>
          <h6>Persentase : <span class="fw-normal">{{ number_format($persentaseHarga[2],2,",",".") }} %</span></h6>
          <div class="progress">
            @if ( $persentaseHarga[2] >= 50 && $persentaseHarga[2] < 75)
            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $persentaseHarga[2] }}%" aria-valuenow="{{ $persentaseHarga[2] }}" aria-valuemin="0" aria-valuemax="100"></div>
            @elseif($persentaseHarga[2] >= 75)
            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $persentaseHarga[2] }}%" aria-valuenow="{{ $persentaseHarga[2] }}" aria-valuemin="0" aria-valuemax="100"></div>
            @else
            <div class="progress-bar" role="progressbar" style="width: {{ $persentaseHarga[2] }}%" aria-valuenow="{{ $persentaseHarga[2] }}" aria-valuemin="0" aria-valuemax="100"></div>
            @endif            
          </div>
        @endforeach  
        
      </div> 

    </div>
  </div>

</div>

  
</div>






@endsection
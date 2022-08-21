<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="/administrasi/pesanan">Pesanan</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="/administrasi/pesanan/detail/{{ $order->id }}">Detail
        Pesanan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Kapasitas Kendaraan</li>
  </ol>
@endsection

@section('main_content')
  <div class="px-5 pt-4">
    <h3 class="mt-3 fs-5 fw-bold">Kapasitas Kendaraan</h3>
    @foreach ($datas as $data)
      @if ($selectedVehicle == $data['id_vehicle'])
        <h4 class="mt-4 fs-6 fw-bold">
          Kendaraan : <span class="fw-normal">{{ $data['nama_vehicle'] }}</span>
          <span class="badge bg-danger ms-4">Dipilih</span>
        </h4>
      @else
        <h4 class="mt-4 fs-6 fw-bold">Kendaraan : <span class="fw-normal">{{ $data['nama_vehicle'] }}</span></h4>
      @endif
      <h4 class="fs-6 fw-bold">Plat Nomor : <span class="fw-normal text-uppercase">{{ $data['kode_vehicle'] }}</span></h4>
      <h6>Persentase Volume:
        <span class="fw-normal">{{ number_format($data['total_persentase_volume'], 2, ',', '.') }} %</span>
      </h6>

      @php
        $eachPersentaseVolumes = explode('+', $data['persentase_volume']);
        $eachPersentaseHargas = explode('+', $data['persentase_harga']);
        $eachColors = explode('+', $data['color']);
        $eachOrders = explode('+', $data['id_order']);
      @endphp

      <div class="progress">
        @for ($i = 0; $i < sizeof($eachPersentaseVolumes); $i++)
          <div class="progress-bar bg-{{ $eachColors[$i] }}" role="progressbar"
            style="width: {{ $eachPersentaseVolumes[$i] }}%" aria-valuenow="{{ $eachPersentaseVolumes[$i] }}"
            aria-valuemin="0" aria-valuemax="100">{{ $eachOrders[$i] }}</div>
        @endfor
      </div>

      <h6 class="mt-2">Persentase Harga :
        <span class="fw-normal">{{ number_format($data['total_persentase_harga'], 2, ',', '.') }}%</span>
      </h6>

      <div class="progress">
        @for ($i = 0; $i < sizeof($eachPersentaseHargas); $i++)
          <div class="progress-bar bg-{{ $eachColors[$i] }}" role="progressbar"
            style="width: {{ $eachPersentaseHargas[$i] }}%" aria-valuenow="{{ $eachPersentaseHargas[$i] }}"
            aria-valuemin="0" aria-valuemax="100">{{ $eachOrders[$i] }}</div>
        @endfor
      </div>

      <hr>
    @endforeach
  </div>
@endsection

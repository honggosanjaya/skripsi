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
    <h3 class="mt-3 fs-5 fw-bold">Persentase Volume setiap Kendaraan</h3>
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
      <h6>Persentase : <span class="fw-normal">{{ number_format($data['total_persentase_volume'], 2, ',', '.') }} %</span>
      </h6>

      @php
        $eachPersentases = explode('+', $data['persentase_volume']);
        $eachColors = explode('+', $data['color']);
        $eachOrders = explode('+', $data['id_order']);
      @endphp

      <div class="progress">
        @for ($i = 0; $i < sizeof($eachPersentases); $i++)
          <div class="progress-bar bg-{{ $eachColors[$i] }}" role="progressbar" style="width: {{ $eachPersentases[$i] }}%"
            aria-valuenow="{{ $eachPersentases[$i] }}" aria-valuemin="0" aria-valuemax="100">{{ $eachOrders[$i] }}</div>
        @endfor
      </div>
    @endforeach

    <hr class="my-5">

    <h3 class="mt-3 fs-5 fw-bold">Persentase Harga setiap Kendaraan</h3>
    @foreach ($datas as $data)
      @if ($selectedVehicle == $data['id_vehicle'])
        <h4 class="mt-4 fs-6 fw-bold">
          Kendaraan : <span class="fw-normal">{{ $data['nama_vehicle'] }}</span>
          <span class="badge bg-danger ms-4">Dipilih</span>
        </h4>
      @else
        <h4 class="mt-4 fs-6 fw-bold">Kendaraan : <span class="fw-normal">{{ $data['nama_vehicle'] }}</span></h4>
      @endif
      <h4 class="fs-6 fw-bold">Plat Nomor : <span class="fw-normal text-uppercase">{{ $data['kode_vehicle'] }}</span>
      </h4>
      <h6>Persentase : <span class="fw-normal">{{ number_format($data['total_persentase_harga'], 2, ',', '.') }}
          %</span>
      </h6>

      @php
        $eachPersentases = explode('+', $data['persentase_harga']);
        $eachColors = explode('+', $data['color']);
        $eachOrders = explode('+', $data['id_order']);
      @endphp

      <div class="progress">
        @for ($i = 0; $i < sizeof($eachPersentases); $i++)
          <div class="progress-bar bg-{{ $eachColors[$i] }}" role="progressbar"
            style="width: {{ $eachPersentases[$i] }}%" aria-valuenow="{{ $eachPersentases[$i] }}" aria-valuemin="0"
            aria-valuemax="100">{{ $eachOrders[$i] }}</div>
        @endfor
      </div>
    @endforeach

    {{-- <div class="row mt-3">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="volume-tab" href="#volume" data-bs-toggle="tab" data-bs-target="#volume"
            role="tab" aria-controls="volume" aria-selected="true">Volume</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="harga-tab" href="#harga" data-bs-toggle="tab" data-bs-target="#harga" role="tab"
            aria-controls="harga" aria-selected="false">Harga</a>
        </li>
      </ul>

      <div class="tab-content clearfix">
        <div class="tab-pane fade show active" id="volume" role="tabpanel" aria-labelledby="volume-tab">
          <h3 class="mt-3 fs-5 fw-bold">Persentase Volume setiap Kendaraan</h3>
          @foreach ($datas as $data)
            <h4 class="mt-4 fs-6 fw-bold">Kendaraan : <span class="fw-normal">{{ $data['nama_vehicle'] }}</span></h4>
            <h4 class="fs-6 fw-bold">Plat Nomor : <span
                class="fw-normal text-uppercase">{{ $data['kode_vehicle'] }}</span></h4>
            <h6>Persentase : <span class="fw-normal">{{ number_format($data['total_persentase_volume'], 2, ',', '.') }}
                %</span>
            </h6>
            <div class="progress">
              @if ($data['total_persentase_volume'] >= 50 && $data['total_persentase_volume'] < 75)
                <div class="progress-bar bg-warning" role="progressbar"
                  style="width: {{ $data['total_persentase_volume'] }}%" aria-valuenow="{{ $data['total_persentase_volume'] }}"
                  aria-valuemin="0" aria-valuemax="100"></div>
              @elseif($data['total_persentase_volume'] >= 75)
                <div class="progress-bar bg-danger" role="progressbar"
                  style="width: {{ $data['total_persentase_volume'] }}%" aria-valuenow="{{ $data['total_persentase_volume'] }}"
                  aria-valuemin="0" aria-valuemax="100"></div>
              @else
                <div class="progress-bar" role="progressbar" style="width: {{ $data['total_persentase_volume'] }}%"
                  aria-valuenow="{{ $data['total_persentase_volume'] }}" aria-valuemin="0" aria-valuemax="100"></div>
              @endif
            </div>
          @endforeach
        </div>

        <div class="tab-pane" id="harga" role="tabpanel" aria-labelledby="harga-tab">
          <h3 class="mt-3 fs-5 fw-bold">Persentase Harga setiap Kendaraan</h3>
          @foreach ($datas as $data)
            <h4 class="mt-4 fs-6 fw-bold">Kendaraan : <span class="fw-normal">{{ $data['nama_vehicle'] }}</span></h4>
            <h4 class="fs-6 fw-bold">Plat Nomor : <span
                class="fw-normal text-uppercase">{{ $data['kode_vehicle'] }}</span></h4>
            <h6>Persentase : <span class="fw-normal">{{ number_format($data['total_persentase_harga'], 2, ',', '.') }}
                %</span>
            </h6>
            <div class="progress">
              @if ($data['total_persentase_harga'] >= 50 && $data['total_persentase_harga'] < 75)
                <div class="progress-bar bg-warning" role="progressbar"
                  style="width: {{ $data['total_persentase_harga'] }}%" aria-valuenow="{{ $data['total_persentase_harga'] }}"
                  aria-valuemin="0" aria-valuemax="100"></div>
              @elseif($data['total_persentase_harga'] >= 75)
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $data['total_persentase_harga'] }}%"
                  aria-valuenow="{{ $data['total_persentase_harga'] }}" aria-valuemin="0" aria-valuemax="100"></div>
              @else
                <div class="progress-bar" role="progressbar" style="width: {{ $data['total_persentase_harga'] }}%"
                  aria-valuenow="{{ $data['total_persentase_harga'] }}" aria-valuemin="0" aria-valuemax="100"></div>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    </div> --}}
  </div>
@endsection

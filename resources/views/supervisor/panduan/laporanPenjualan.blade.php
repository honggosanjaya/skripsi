@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/supervisor.css') }}" rel="stylesheet">
@endpush

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    {{-- <li class="breadcrumb-item"><a href="/supervisor/panduan">Panduan</a></li> --}}
    <li class="breadcrumb-item active" aria-current="page">Panduan Penjualan</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <h1 class="fs-5">Panduan Perhitungan Laporan Penjualan</h1>

    <p>Laporan Penjualan didapatkan dari data pesanan yang sudah dikirimkan, sudah dibayarkan, dan sudah selesai</p>
  </div>
@endsection

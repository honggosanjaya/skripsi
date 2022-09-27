@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/supervisor.css') }}" rel="stylesheet">
@endpush

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    {{-- <li class="breadcrumb-item"><a href="/supervisor/panduan">Panduan</a></li> --}}
    <li class="breadcrumb-item active" aria-current="page">Panduan Kinerja</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <h1 class="fs-5">Panduan Perhitungan Laporan Kinerja Salesman</h1>

    <p>Laporan kinerja salesman mencakup jumlah kunjungan, jumlah effective call, jumlah customer baru, dan total
      penjualan yang didapatkan</p>

    <p>Total penjualan ini kalau kami tidak salah berdasarkan invoice, sehingga mendapatkan pengurangan dari diskon tipe
      customer dan diskon event</p>
  </div>
@endsection

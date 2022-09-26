@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/datacustomer">Data Customer</a></li>
    <li class="breadcrumb-item active" aria-current="page">Generate QR Code</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-5">
    <h1 class="fs-4 mb-4 text-center"><b>Kode QR untuk {{ $customer->nama }}</b></h1>

    <div class="text-center">
      {{-- {!! QrCode::size(300)->generate('https://salesman-dev.suralaya.web.id/salesman/trip/' . $customer->id) !!} --}}
      {!! QrCode::size(300)->generate(env('APP_URL') . '/salesman/trip/' . $customer->id) !!}
    </div>

    <a href="/administrasi/datacustomer/{{ $customer->id }}/cetak-qr" class="btn btn-primary d-block mx-auto w-25 mt-5">
      <i class="bi bi-download px-1"></i>Unduh QR Code
    </a>
  </div>
@endsection

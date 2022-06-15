@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('main_content')
  <div class="card-main_wrapper mt-4">
    <div class="card-main bg-primary">
      <i class="bi bi-box2"></i>
      <h1 class="fs-5 fw-bold">Jumlah Item</h1>
      <h1 class="counter">{{ $data['jumlah_item'] }}</h1>
      <small class="d-block">Item aktif sebanyak {{ $data['jumlah_item_aktif'] }}</small>
    </div>

    <div class="card-main bg-success">
      <i class="bi bi-box2"></i>
      <h1 class="fs-5 fw-bold">Jumlah Kendaraan</h1>
      <h1 class="counter">{{ $data['jumlah_kendaraan'] }}</h1>
    </div>

    <div class="card-main bg-warning">
      <i class="bi bi-box2"></i>
      <h1 class="fs-5 fw-bold">Jumlah Customer</h1>
      <h1 class="counter">{{ $data['jumlah_customer'] }}</h1>
      <small class="d-block">Customer aktif sebanyak {{ $data['jumlah_customer_aktif'] }}</small>
    </div>
  </div>
@endsection

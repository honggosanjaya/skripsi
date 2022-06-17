@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="/administrasi/pesanan">Pesanan</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="/administrasi/pesanan/detail">Detail Pesanan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Pengiriman</li>
  </ol>
@endsection
@section('main_content')
  <div class="container mt-4">
    <h1 class="fs-3">Detail Pengiriman</h1>
    <div class="informasi-list">
      <span><b>Nomor Invoice</b>{{ $order->linkInvoice->nomor_invoice ?? '-' }}</span>
      <span><b>Nama Customer</b>{{ $order->linkCustomer->nama }}</span>
      <span><b>Nama Pengirim</b>{{ $order->linkOrderTrack->linkStaffPengirim->nama ?? '-' }}</span>
      <span><b>Alamat Pengiriman</b>{{ $order->linkCustomer->full_alamat }}</span>
      <span><b>Telepon</b>{{ $order->linkCustomer->telepon ?? '-' }}</span>
      <span><b>Status pesanan</b>{{ $order->linkOrderTrack->linkStatus->nama }}</span>
      <span><b>Foto Pengiriman</b>
        @if ($order->linkOrderTrack->foto_pengiriman != null)
          <img src="{{ asset('storage/pengiriman/' . $order->linkOrderTrack->foto_pengiriman) }}"
            class="img-preview img-fluid d-block">
        @else
          <small class="text-danger">Tidak ada foto</small>
        @endif
      </span>
    </div>

    @if ($order->linkOrderTrack->status == 21)
      <form class="form-submit" method="POST" action="/administrasi/pesanan/detail/{{ $order->id }}/dikirimkan">
        @csrf
        <div class="my-3">
          <label for="id_staff_pengirim" class="form-label">Nama Pengirim</label>
          <select class="form-select" name="id_staff_pengirim">
            @foreach ($stafs as $staf)
              <option value="{{ $staf->id }}">{{ $staf->nama }}</option>
            @endforeach
          </select>
        </div>

        <div class="my-3">
          <label for="id_vehicle" class="form-label">Nama Kendaraan</label>
          <select class="form-select" name="id_vehicle">
            @foreach ($vehicles as $vehicle)
              <option value="{{ $vehicle->id }}">{{ $vehicle->nama }}</option>
            @endforeach
          </select>
        </div>

        <button type="submit" class="btn btn-primary">Pesanan Dikirim</button>
      </form>
    @endif


    @if ($order->linkOrderTrack->linkStatus->nama == 'order telah sampai')
      <form class="form-submit" method="POST" action="/administrasi/pesanan/detail/{{ $order->id }}/dikirimkan">
        @csrf
        <button type="submit" class="btn btn-success mt-4">Pesanan Selesai</button>
      </form>
    @endif
  </div>
@endsection

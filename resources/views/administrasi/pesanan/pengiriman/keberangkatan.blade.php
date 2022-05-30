@extends('layouts/main')
@section('main_content')
  <div class="container mt-4">
    <h1 class="fs-3">Detail Pengiriman</h1>
    <p>Nomor Invoice : {{ $order->linkInvoice->nomor_invoice }}</p>
    <p>Nama Customer : {{ $order->linkCustomer->nama }}</p>
    <p>Alamat Pengiriman : {{ $order->linkCustomer->full_alamat }}</p>
    <p>Telepon : {{ $order->linkCustomer->telepon ?? '-' }}</p>
    <p>Status pesanan : {{ $order->linkOrderTrack->linkStatus->nama }}</p>
    <p>Foto Pengiriman : </p>
    @if ($order->linkOrderTrack->foto_pengiriman != null)
      <img src="{{ asset('storage/pengiriman/' . $order->linkOrderTrack->foto_pengiriman) }}"
        class="img-preview img-fluid d-block">
    @else
      <p>Tidak ada foto</p>
    @endif

    @if ($order->linkOrderTrack->linkStatus->nama == 'dikonfirmasi admin')
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

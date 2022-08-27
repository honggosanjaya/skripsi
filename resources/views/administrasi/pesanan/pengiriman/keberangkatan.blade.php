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
    <li class="breadcrumb-item active" aria-current="page">Detail Pengiriman</li>
  </ol>
@endsection

@section('main_content')
  <div class="px-5 pt-4">
    <h1 class="fs-4 mb-4">Detail Pengiriman</h1>
    <div class="informasi-list mb_big">
      <span><b>Nomor Invoice</b>{{ $order->linkInvoice->nomor_invoice ?? '-' }}</span>
      <span><b>Nama Customer</b>{{ $order->linkCustomer->nama }}</span>
      <span><b>Nama Pengirim</b>{{ $order->linkOrderTrack->linkStaffPengirim->nama ?? '-' }}</span>
      <span><b>Alamat Pengiriman</b>{{ $order->linkCustomer->full_alamat }}</span>
      <span><b>Telepon</b>{{ $order->linkCustomer->telepon ?? '-' }}</span>

      @if ($order->linkOrderTrack->status_enum == '0')
        <span><b>Status pesanan</b>Diajukan Customer</span>
      @elseif ($order->linkOrderTrack->status_enum == '1')
        <span><b>Status pesanan</b>Diajukan Salesman</span>
      @elseif ($order->linkOrderTrack->status_enum == '2')
        <span><b>Status pesanan</b>Dikonfirmasi Admin</span>
      @elseif ($order->linkOrderTrack->status_enum == '3')
        <span><b>Status pesanan</b>Dalam Perjalanan</span>
      @elseif ($order->linkOrderTrack->status_enum == '4')
        <span><b>Status pesanan</b>Order Telah Sampai</span>
      @elseif ($order->linkOrderTrack->status_enum == '5')
        <span><b>Status pesanan</b>Order Selesai</span>
      @endif

      <span><b>Foto Pengiriman</b>
        @if ($order->linkOrderTrack->foto_pengiriman != null)
          <img src="{{ asset('storage/pengiriman/' . $order->linkOrderTrack->foto_pengiriman) }}"
            class="img-preview img-fluid d-block">
        @else
          <small class="text-danger">Tidak ada foto</small>
        @endif
      </span>
    </div>

    @if ($order->linkOrderTrack->status_enum == '2')
      <hr>
      <h1 class="fs-4 my-4">Atur Pengiriman</h1>

      <form class="form-submit" method="POST" id="keberangkatan"
        action="/administrasi/pesanan/detail/{{ $order->id }}/dikirimkan">
        @csrf
        <div class="row">
          <div class="col">
            <div class="mb-3">
              <label for="id_staff_pengirim" class="form-label">Nama Pengirim</label>
              <select class="form-select" name="id_staff_pengirim">
                @foreach ($stafs as $staf)
                  <option value="{{ $staf->id }}">{{ $staf->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col">
            <div class="mb-3">
              <label for="id_vehicle" class="form-label">Nama Kendaraan</label>
              <select class="form-select select_vehicle" name="id_vehicle" readonly>

                @foreach ($activeVehicles as $vehicle)
                  @if ($selectedVehicle == $vehicle->id)
                    <option value="{{ $vehicle->id }}" selected>{{ $vehicle->nama }}</option>
                  @else
                    <option value="{{ $vehicle->id }}">{{ $vehicle->nama }}</option>
                  @endif
                @endforeach
                @foreach ($inactiveVehicles as $vehicle)
                  @if ($selectedVehicle == $vehicle->id)
                    <option value="{{ $vehicle->id }}" selected>{{ $vehicle->nama }}</option>
                  @else
                    <option value="{{ $vehicle->id }}" disabled>{{ $vehicle->nama }}</option>
                  @endif
                @endforeach

              </select>
            </div>
          </div>
        </div>
        <div class="row justify-content-end mt-4">
          <div class="col-3 d-flex justify-content-end">
            <button type="button" class="btn btn-warning me-2 btn_ubah_kendaraan">Ubah Kendaraan</button>
            <button type="submit" class="btn btn-primary btn_konfirmasikeberangkatan">Pesanan Dikirim</button>
          </div>
        </div>
      </form>
    @endif


    @if ($order->linkOrderTrack->status_enum == '4')
      <form class="form-submit" id="pesananselesai" method="POST"
        action="/administrasi/pesanan/detail/{{ $order->id }}/dikirimkan">
        @csrf
        <button type="submit" class="btn btn-success mt-4 pesanan_selesai">Pesanan Selesai</button>
      </form>
    @endif
  </div>

  <script>
    const btn_ubah_kendaraan = document.querySelector('.btn_ubah_kendaraan');
    const select_vehicle = document.querySelector('.select_vehicle');
    btn_ubah_kendaraan.addEventListener("click", () => {
      select_vehicle.removeAttribute("readonly");
      btn_ubah_kendaraan.classList.add('d-none');
    })
  </script>
@endsection

@extends('layouts.mainmobile')
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
  <div class="container pt-4">
    <h1 class="fs-5 mb-4">Detail Pengiriman</h1>
    <div class="informasi-list mb_big">
      <span class="d-flex align-items-center"><b>Nomor Invoice</b>
        <span>{{ $order->linkInvoice->nomor_invoice ?? null }}</span>
      </span>
      <span class="d-flex align-items-center"><b>Nama Customer</b>
        <span>{{ $order->linkCustomer->nama ?? null }}</span>
      </span>
      <span class="d-flex align-items-center"><b>Nama Pengirim</b>
        <span>{{ $order->linkOrderTrack->linkStaffPengirim->nama ?? null }}</span>
      </span>
      <span class="d-flex align-items-center"><b>Alamat Pengiriman</b>
        <span>{{ $order->linkCustomer->full_alamat ?? null }}</span>
      </span>
      <span class="d-flex align-items-center"><b>Telepon</b>
        <span>{{ $order->linkCustomer->telepon ?? null }}</span>
      </span>

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
        <span><b>Status pesanan</b>Pembayaran</span>
      @elseif ($order->linkOrderTrack->status_enum == '6')
        <span><b>Status pesanan</b>Order Selesai</span>
      @endif

      <span><b>Foto Pengiriman</b>
        @if ($order->linkOrderTrack->foto_pengiriman != null)
          <img src="{{ asset('storage/pengiriman/' . $order->linkOrderTrack->foto_pengiriman) }}"
            class="img-preview img-fluid d-block">
        @else
          <span class="text-danger">Tidak ada foto</sp>
        @endif
      </span>
    </div>

    @if ($order->linkOrderTrack->status_enum == '2')
      <hr>
      <h1 class="fs-5 my-4">Atur Pengiriman</h1>

      <form class="form-submit" method="POST" id="keberangkatan"
        action="/administrasi/pesanan/detail/{{ $order->id }}/dikirimkan">
        @csrf
        <div class="row">
          <div class="col-12">
            <div class="mb-3">
              <label for="id_staff_pengirim" class="form-label">Nama Pengirim</label>
              <select class="form-select" name="id_staff_pengirim">
                @foreach ($stafs as $staf)
                  <option value="{{ $staf->id }}">{{ $staf->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-12">
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
          <div class="col d-flex justify-content-end">
            <button type="button" class="btn btn-warning me-2 btn_ubah_kendaraan">Ubah Kendaraan</button>
            <button type="submit" class="btn btn-primary btn_konfirmasikeberangkatan">Pesanan Dikirim</button>
          </div>
        </div>
      </form>
    @endif


    {{-- @if ($order->linkOrderTrack->status_enum == '4')
      <form class="form-submit" id="pesananselesai" method="POST"
        action="/administrasi/pesanan/detail/{{ $order->id }}/dikirimkan">
        @csrf
        <button type="submit" class="btn btn-success mt-4 pesanan_selesai">Pesanan Selesai</button>
      </form>
    @endif --}}
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

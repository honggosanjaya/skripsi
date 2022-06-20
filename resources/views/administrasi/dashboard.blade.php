@extends('layouts.main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush

@section('main_content')
  <div class="retur_notif m-fadeOut p-3">
    @foreach ($notifikasi['retur'] as $notif)
      <div class="card_notif">
        <a href="/administrasi/retur/{{ $notif->no_retur }}" class="text-black text-decoration-none">
          <div class="d-flex justify-content-between">
            <p class="mb-0">Invoice: {{ $notif->linkInvoice->nomor_invoice ?? null }}</p>
            <p class="mb-0">{{ date('d F Y', strtotime($notif->created_at)) }}</p>
          </div>
          <p class="mb-0">Retur dari {{ $notif->linkCustomer->nama }}</p>
        </a>
      </div>
    @endforeach
  </div>

  <div class="trip_notif m-fadeOut p-3">
    @foreach ($notifikasi['trip'] as $notif)
      <div class="card_notif">
        <p class="mb-0 fw-bold">Peringatan Kunjungan</p>
        <p class="mb-0">Hari ini waktunya mengunjungi {{ $notif->nama }} </p>
        <p class="mb-0"><span class="fw-bold">Alamat:</span> {{ $notif->full_alamat }}</p>
      </div>
    @endforeach
  </div>

  <div class="order_notif m-fadeOut p-3">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <a class="nav-link active" id="diajukan-tab" href="#diajukan" data-bs-toggle="tab" data-bs-target="#diajukan"
          role="tab" aria-controls="diajukan" aria-selected="true">Diajukan</a>
      </li>
      <li class="nav-item" role="presentation">
        <a class="nav-link" id="selesai-tab" href="#selesai" data-bs-toggle="tab" data-bs-target="#selesai" role="tab"
          aria-controls="selesai" aria-selected="true">selesai</a>
      </li>
    </ul>

    <div class="tab-content clearfix">
      <div class="tab-pane fade show active" id="diajukan" role="tabpanel" aria-labelledby="diajukan-tab">
        @foreach ($notifikasi['order_diajukan'] as $notif)
          <div class="card_notif">
            <a href="/administrasi/pesanan/detail/{{ $notif->id }}" class="text-black text-decoration-none">
              <div class="d-flex justify-content-between">
                <p class="mb-0">Invoice: {{ $notif->linkInvoice->nomor_invoice ?? null }}</p>
                <p class="mb-0">{{ date('d F Y', strtotime($notif->linkOrderTrack->waktu_diteruskan)) }}</p>
              </div>
              @php
                $total_pesanan = 0;
                foreach ($notif->linkOrderItem as $orderitem) {
                    $total_pesanan = $total_pesanan + $orderitem->harga_satuan * $orderitem->kuantitas;
                }
              @endphp
              <p class="mb-0">Pesanan dari {{ $notif->linkCustomer->nama }} sebesar
                Rp.
                {{ number_format($total_pesanan, 0, '', '.') }}
              </p>
            </a>
          </div>
        @endforeach
      </div>
      <div class="tab-pane" id="selesai" role="tabpanel" aria-labelledby="selesai-tab">
        @foreach ($notifikasi['order_selesai'] as $notif)
          <div class="card_notif">
            <a href="/administrasi/pesanan/detail/{{ $notif->id }}" class="text-black text-decoration-none">
              <div class="d-flex justify-content-between">
                <p class="mb-0">Invoice: {{ $notif->linkInvoice->nomor_invoice ?? null }}</p>
                <p class="mb-0">{{ date('d F Y', strtotime($notif->linkOrderTrack->waktu_diteruskan)) }}</p>
              </div>
              @php
                $total_pesanan = 0;
                foreach ($notif->linkOrderItem as $orderitem) {
                    $total_pesanan = $total_pesanan + $orderitem->harga_satuan * $orderitem->kuantitas;
                }
              @endphp
              <p class="mb-0">Pesanan dari {{ $notif->linkCustomer->nama }} sebesar
                Rp.
                {{ number_format($total_pesanan, 0, '', '.') }}
              </p>
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="card-main_wrapper mt-4">
    <div class="card-main bg-primary">
      <i class="bi bi-box2"></i>
      <h1 class="fs-5 fw-bold">Jumlah Item</h1>
      <h1 class="counter">{{ $data['jumlah_item'] }}</h1>
      <small class="d-block">Item aktif sebanyak {{ $data['jumlah_item_aktif'] }}</small>
    </div>

    <div class="card-main bg-info">
      <i class="bi bi-box2"></i>
      <h1 class="fs-5 fw-bold">Jumlah Kendaraan</h1>
      <h1 class="counter">{{ $data['jumlah_kendaraan'] }}</h1>
    </div>

    <div class="card-main bg-success">
      <i class="bi bi-box2"></i>
      <h1 class="fs-5 fw-bold">Jumlah Customer</h1>
      <h1 class="counter">{{ $data['jumlah_customer'] }}</h1>
      <small class="d-block">Customer aktif sebanyak {{ $data['jumlah_customer_aktif'] }}</small>
    </div>
  </div>

  <script>
    const dropdownRetur = document.querySelector(".alert_retur");
    const notifRetur = document.querySelector(".retur_notif");
    const dropdownOrder = document.querySelector(".alert_order");
    const notifOrder = document.querySelector(".order_notif");
    const dropdownTrip = document.querySelector(".alert_trip");
    const notifTrip = document.querySelector(".trip_notif");

    dropdownRetur.addEventListener("click", function() {
      dropdownRetur.classList.toggle('active');
      notifRetur.classList.toggle("m-fadeIn");
      notifRetur.classList.toggle("m-fadeOut");
      notifOrder.classList.add("m-fadeOut");
      notifOrder.classList.remove("m-fadeIn");
      notifTrip.classList.add("m-fadeOut");
      notifTrip.classList.remove("m-fadeIn");
      dropdownTrip.classList.remove('active');
      dropdownOrder.classList.remove('active');
    });

    dropdownOrder.addEventListener("click", function() {
      dropdownOrder.classList.toggle('active');
      notifOrder.classList.toggle("m-fadeIn");
      notifOrder.classList.toggle("m-fadeOut");
      notifRetur.classList.add("m-fadeOut");
      notifRetur.classList.remove("m-fadeIn");
      notifTrip.classList.add("m-fadeOut");
      notifTrip.classList.remove("m-fadeIn");
      dropdownTrip.classList.remove('active');
      dropdownRetur.classList.remove('active');
    });

    dropdownTrip.addEventListener("click", function() {
      dropdownTrip.classList.toggle('active');
      notifTrip.classList.toggle("m-fadeIn");
      notifTrip.classList.toggle("m-fadeOut");
      notifRetur.classList.add("m-fadeOut");
      notifRetur.classList.remove("m-fadeIn");
      notifOrder.classList.add("m-fadeOut");
      notifOrder.classList.remove("m-fadeIn");
      dropdownOrder.classList.remove('active');
      dropdownRetur.classList.remove('active');
    });
  </script>
@endsection

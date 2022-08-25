@extends('layouts.main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush

@section('main_content')
  <div class="pajak_notif notif m-fadeOut p-3">
    @foreach ($notifikasi['pajak_kendaraan'] as $notif)
      <div class="card_notif">
        <p class="mb-0 fw-bold">Peringatan Masa Pajak Kendaraan</p>
        <p class="mb-0">{{ $notif['nama_vehicle'] }} jatuh tempo pada
          {{ date('d M Y', strtotime($notif['tanggal_pajak'])) }}</p>
      </div>
    @endforeach
  </div>

  <div class="limit_notif notif m-fadeOut p-3">
    @foreach ($notifikasi['pengajuan_limit'] as $notif)
      <div class="card_notif">
        <a href="/administrasi/datacustomer/{{ $notif->id }}?route={{ $notif->status_limit_pembelian == 7 ? 'lihatpengajuan' : 'bacapengajuan' }}"
          class="text-black text-decoration-none">
          <p class="mb-0"><b>Customer:</b> {{ $notif->nama ?? null }}</p>
          <p class="mb-0"><b>Tanggal Pengajuan:</b> {{ date('d F Y', strtotime($notif->created_at)) }}</p>
          <p class="mb-0">Pengajuan sebesar Rp.
            {{ number_format($notif->pengajuan_limit_pembelian, 0, '', '.') }}
            <span class="text-danger fw-bold">
              {{ $notif->status_limit_pembelian == 5 ? 'Disetujui' : ($notif->status_limit_pembelian == 6 ? 'Tidak Disetujui' : 'Diajukan') }}
            </span>
          </p>
        </a>
      </div>
    @endforeach
  </div>

  <div class="retur_notif notif m-fadeOut p-3">
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

  <div class="trip_notif notif m-fadeOut p-3">
    @foreach ($notifikasi['trip'] as $notif)
      <div class="card_notif">
        <p class="mb-0 fw-bold">Peringatan Kunjungan</p>
        <p class="mb-0">Hari ini waktunya mengunjungi {{ $notif->nama }} </p>
        <p class="mb-0"><span class="fw-bold">Alamat:</span> {{ $notif->full_alamat }}</p>
      </div>
    @endforeach
  </div>

  <div class="order_notif notif m-fadeOut p-3">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <a class="nav-link active" id="customer-tab" href="#customer" data-bs-toggle="tab" data-bs-target="#customer"
          role="tab" aria-controls="customer" aria-selected="true">Customer</a>
      </li>

      <li class="nav-item" role="presentation">
        <a class="nav-link" id="salesman-tab" href="#salesman" data-bs-toggle="tab" data-bs-target="#salesman"
          role="tab" aria-controls="salesman" aria-selected="true">Salesman</a>
      </li>

      <li class="nav-item" role="presentation">
        <a class="nav-link" id="selesai-tab" href="#selesai" data-bs-toggle="tab" data-bs-target="#selesai" role="tab"
          aria-controls="selesai" aria-selected="true">Selesai</a>
      </li>
    </ul>

    <div class="tab-content clearfix">
      <div class="tab-pane fade show active" id="customer" role="tabpanel" aria-labelledby="customer-tab">
        @foreach ($notifikasi['order_diajukan_customer'] as $notif)
          <div class="card_notif">
            <a href="/administrasi/pesanan/detail/{{ $notif->id }}" class="text-black text-decoration-none">
              <div class="d-flex justify-content-between">
                <p class="mb-0"><b>Tanggal Order:
                  </b>{{ date('d F Y', strtotime($notif->linkOrderTrack->waktu_order)) }}</p>
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

      <div class="tab-pane" id="salesman" role="tabpanel" aria-labelledby="salesman-tab">
        @foreach ($notifikasi['order_diajukan_salesman'] as $notif)
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

  <div class="reimbursement_notif notif m-fadeOut p-3">
    @foreach ($notifikasi['reimbursement'] as $notif)
      <div class="card_notif">
        <a href="/administrasi/reimbursement/pengajuan/{{ $notif->id }}" class="text-black text-decoration-none">
          <p class="mb-0">Pengajuan dari {{ $notif->linkStaffPengaju->nama }}</p>
          <p class="mb-0">Diajukan pada {{ date('d F Y', strtotime($notif->created_at)) }}</p>
          <p class="mb-0">{{ $notif->status == 27 ? 'Menunggu Konfirmasi' : 'Menunggu Pembayaran' }}</p>
        </a>
      </div>
    @endforeach
  </div>

  <div class="card-main_wrapper mt-4" id="dashboardAdmin">
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

    <input type="hidden" name="loginPassword" value="{{ session('password') }}">
    <input type="hidden" name="countt" value="{{ session('count') }}">
  </div>

  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

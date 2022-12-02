@extends('layouts.main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush

@section('main_content')
  <div class="px-5 pt-4" id="dashboardAdmin">
    @if (session()->has('successMessage'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('successMessage') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    <div class="all-notification m-fadeOut p-3">
      <div id="horizontal_scroll" class="mb-3">
        <button class="btn btn-outline-primary filter-notif active" data-notif="trip">Trip
          ({{ $datadua['jml_trip'] }})</button>
        <button class="btn btn-outline-primary filter-notif" data-notif="order">Pesanan
          ({{ $datadua['jml_order'] }})</button>
        <button class="btn btn-outline-primary filter-notif" data-notif="retur">Retur
          ({{ $datadua['jml_retur'] }})</button>
        <button class="btn btn-outline-primary filter-notif" data-notif="limit">Limit Pembelian
          ({{ $datadua['jml_pengajuan_limit'] }})</button>
        <button class="btn btn-outline-primary filter-notif" data-notif="reimbursement">Reimbursement
          ({{ $datadua['jml_reimbursement'] }})</button>
        <button class="btn btn-outline-primary filter-notif" data-notif="pajak">Pajak Kendaraan
          ({{ $datadua['jml_pajak'] }})</button>
        <button class="btn btn-outline-primary filter-notif" data-notif="jatuhtempo">Jatuh Tempo
          ({{ $datadua['jml_jatuhTempo'] }})</button>
      </div>

      <div class="trip_notif notif">
        @foreach ($notifikasi['trip'] as $notif)
          <div class="card_notif">
            <p class="mb-0 fw-bold">Peringatan Kunjungan</p>
            <p class="mb-0">Hari ini waktunya mengunjungi {{ $notif->nama ?? null }} </p>
            <p class="mb-0"><span class="fw-bold">Alamat:</span> {{ $notif->full_alamat ?? null }}</p>
          </div>
        @endforeach
      </div>

      <div class="order_notif notif d-none">
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
            <a class="nav-link" id="selesai-tab" href="#selesai" data-bs-toggle="tab" data-bs-target="#selesai"
              role="tab" aria-controls="selesai" aria-selected="true">Selesai</a>
          </li>
        </ul>

        <div class="tab-content clearfix">
          <div class="tab-pane fade show active" id="customer" role="tabpanel" aria-labelledby="customer-tab">
            @foreach ($notifikasi['order_diajukan_customer'] as $notif)
              <div class="card_notif">
                <a href="/administrasi/pesanan/detail/{{ $notif->id ?? null }}" class="text-black text-decoration-none">
                  <div class="d-flex justify-content-between">
                    <p class="mb-0"><b>Tanggal Order:
                      </b>{{ date('d F Y', strtotime($notif->linkOrderTrack->waktu_order ?? '-')) }}</p>
                  </div>
                  @php
                    $total_pesanan = 0;
                    foreach ($notif->linkOrderItem as $orderitem) {
                        $total_pesanan = $total_pesanan + $orderitem->harga_satuan * $orderitem->kuantitas;
                    }
                  @endphp
                  <p class="mb-0">Pesanan dari {{ $notif->linkCustomer->nama ?? null }} sebesar
                    Rp.
                    {{ number_format($total_pesanan ?? 0, 0, '', '.') }}
                  </p>
                </a>
              </div>
            @endforeach
          </div>

          <div class="tab-pane" id="salesman" role="tabpanel" aria-labelledby="salesman-tab">
            @foreach ($notifikasi['order_diajukan_salesman'] as $notif)
              <div class="card_notif">
                <a href="/administrasi/pesanan/detail/{{ $notif->id ?? null }}" class="text-black text-decoration-none">
                  <p class="mb-0">Invoice: {{ $notif->linkInvoice->nomor_invoice ?? null }}</p>
                  <p class="mb-0">{{ date('d F Y', strtotime($notif->linkOrderTrack->waktu_diteruskan ?? '-')) }}</p>
                  @php
                    $total_pesanan = 0;
                    foreach ($notif->linkOrderItem as $orderitem) {
                        $total_pesanan = $total_pesanan + $orderitem->harga_satuan * $orderitem->kuantitas;
                    }
                  @endphp
                  <p class="mb-0">Pesanan dari {{ $notif->linkCustomer->nama ?? null }} sebesar
                    Rp.
                    {{ number_format($total_pesanan ?? 0, 0, '', '.') }}
                  </p>
                </a>
              </div>
            @endforeach
          </div>

          <div class="tab-pane" id="selesai" role="tabpanel" aria-labelledby="selesai-tab">
            @foreach ($notifikasi['order_selesai'] as $notif)
              <div class="card_notif">
                <a href="/administrasi/pesanan/detail/{{ $notif->id ?? null }}" class="text-black text-decoration-none">
                  <div class="d-flex justify-content-between">
                    <p class="mb-0">Invoice: {{ $notif->linkInvoice->nomor_invoice ?? null }}</p>
                    <p class="mb-0">{{ date('d F Y', strtotime($notif->linkOrderTrack->waktu_diteruskan ?? '-')) }}</p>
                  </div>
                  @php
                    $total_pesanan = 0;
                    foreach ($notif->linkOrderItem as $orderitem) {
                        $total_pesanan = $total_pesanan + $orderitem->harga_satuan * $orderitem->kuantitas;
                    }
                  @endphp
                  <p class="mb-0">Pesanan dari {{ $notif->linkCustomer->nama ?? null }} sebesar
                    Rp.
                    {{ number_format($total_pesanan ?? 0, 0, '', '.') }}
                  </p>
                </a>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <div class="reimbursement_notif notif d-none">
        @foreach ($notifikasi['reimbursement'] as $notif)
          <div class="card_notif">
            <a href="/administrasi/reimbursement/pengajuan/{{ $notif->id ?? null }}"
              class="text-black text-decoration-none">
              <p class="mb-0">Pengajuan dari {{ $notif->linkStaffPengaju->nama ?? null }}</p>
              <p class="mb-0">Diajukan pada {{ date('d F Y', strtotime($notif->created_at ?? '-')) }}</p>
              @if ($notif->status_enum != null)
                <p class="mb-0">{{ $notif->status_enum == '0' ? 'Menunggu Konfirmasi' : 'Menunggu Pembayaran' }}</p>
              @endif
            </a>
          </div>
        @endforeach
      </div>

      <div class="pajak_notif notif d-none">
        @foreach ($notifikasi['pajak_kendaraan'] as $notif)
          <div class="card_notif">
            <p class="mb-0 fw-bold">Peringatan Masa Pajak Kendaraan</p>
            <p class="mb-0">{{ $notif['nama_vehicle'] ?? null }} jatuh tempo pada
              {{ date('d M Y', strtotime($notif['tanggal_pajak'] ?? '-')) }}</p>
          </div>
        @endforeach
      </div>

      <div class="jatuhtempo_notif notif d-none">
        @foreach ($notifikasi['jatuh_tempo'] as $notif)
          <div class="card_notif">
            <a href="/administrasi/pesanan/detail/{{ $notif['id_order'] }}" class="text-black text-decoration-none">
              <p class="mb-0 fw-bold">Peringatan Jatuh Tempo</p>
              <p class="mb-0">{{ $notif['nomor_invoice'] ?? null }} jatuh tempo pada
                {{ date('d M Y', strtotime($notif['tanggalJatuhTempo'] ?? '-')) }}</p>
            </a>
          </div>
        @endforeach
      </div>

      <div class="limit_notif notif d-none">
        @foreach ($notifikasi['pengajuan_limit'] as $notif)
          <div class="card_notif">
            <a href="/administrasi/datacustomer/{{ $notif->id ?? null }}?route={{ $notif->status_limit_pembelian_enum == 0 ? 'lihatpengajuan' : 'bacapengajuan' }}"
              class="text-black text-decoration-none">
              <p class="mb-0"><b>Customer:</b> {{ $notif->nama ?? null }}</p>
              <p class="mb-0">Pengajuan sebesar Rp.
                {{ number_format($notif->pengajuan_limit_pembelian ?? 0, 0, '', '.') }}
                @if ($notif->status_limit_pembelian_enum ?? null)
                  <span class="text-danger fw-bold">
                    {{ $notif->status_limit_pembelian_enum == 1 ? 'Disetujui' : ($notif->status_limit_pembelian_enum == -1 ? 'Tidak Disetujui' : 'Diajukan') }}
                  </span>
                @endif
              </p>
            </a>
          </div>
        @endforeach
      </div>

      <div class="retur_notif notif d-none">
        @foreach ($notifikasi['retur'] as $notif)
          <div class="card_notif">
            <a href="/administrasi/retur/{{ $notif->no_retur ?? null }}" class="text-black text-decoration-none">
              <div class="d-flex justify-content-between">
                <p class="mb-0">Invoice: {{ $notif->linkInvoice->nomor_invoice ?? null }}</p>
                <p class="mb-0">{{ date('d F Y', strtotime($notif->created_at ?? '-')) }}</p>
              </div>
              <p class="mb-0">Retur dari {{ $notif->linkCustomer->nama ?? null }}</p>
            </a>
          </div>
        @endforeach
      </div>
    </div>

    <div class="card-main_wrapper mt-4">
      <div class="card-main bg-primary">
        <i class="bi bi-box2"></i>
        <h1 class="fs-5 fw-bold">Jumlah Item</h1>
        <h1 class="counter">{{ $data['jumlah_item'] ?? null }}</h1>
        <small class="d-block">Item aktif sebanyak {{ $data['jumlah_item_aktif'] ?? null }}</small>
      </div>

      <div class="card-main bg-info">
        <i class="bi bi-box2"></i>
        <h1 class="fs-5 fw-bold">Jumlah Kendaraan</h1>
        <h1 class="counter">{{ $data['jumlah_kendaraan'] ?? null }}</h1>
      </div>

      <div class="card-main bg-success">
        <i class="bi bi-box2"></i>
        <h1 class="fs-5 fw-bold">Jumlah Customer</h1>
        <h1 class="counter">{{ $data['jumlah_customer'] ?? null }}</h1>
        <small class="d-block">Customer aktif sebanyak {{ $data['jumlah_customer_aktif'] ?? null }}</small>
      </div>

      <input type="hidden" name="loginPassword" value="{{ session('password') }}">
      <input type="hidden" name="countt" value="{{ session('count') }}">
    </div>
  </div>

  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
    <script>
      let time = new Date().getTime();
      const setActivityTime = (e) => {
        time = new Date().getTime();
      }

      document.body.addEventListener("keypress", setActivityTime);
      const refresh = () => {
        if (new Date().getTime() - time >= 600000) {
          window.location.reload(true);
        } else {
          setTimeout(refresh, 100000);
        }
      }
      setTimeout(refresh, 100000);
    </script>
  @endpush
@endsection

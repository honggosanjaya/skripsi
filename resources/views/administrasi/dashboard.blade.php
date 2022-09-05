@extends('layouts.main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush

@section('main_content')
  <div class="pajak_notif notif m-fadeOut p-3">
    @foreach ($notifikasi['pajak_kendaraan'] as $notif)
      <div class="card_notif">
        <p class="mb-0 fw-bold">Peringatan Masa Pajak Kendaraan</p>
        <p class="mb-0">{{ $notif['nama_vehicle'] ?? null }} jatuh tempo pada
          {{ date('d M Y', strtotime($notif['tanggal_pajak'] ?? '-')) }}</p>
      </div>
    @endforeach
  </div>

  <div class="limit_notif notif m-fadeOut p-3">
    @foreach ($notifikasi['pengajuan_limit'] as $notif)
      <div class="card_notif">
        <a href="/administrasi/datacustomer/{{ $notif->id ?? null }}?route={{ $notif->status_limit_pembelian_enum == 0 ? 'lihatpengajuan' : 'bacapengajuan' }}"
          class="text-black text-decoration-none">
          <p class="mb-0"><b>Customer:</b> {{ $notif->nama ?? null }}</p>
          {{-- <p class="mb-0"><b>Tanggal Pengajuan:</b> {{ date('d F Y', strtotime($notif->updated_at)) }}</p> --}}
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

  <div class="retur_notif notif m-fadeOut p-3">
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

  <div class="trip_notif notif m-fadeOut p-3">
    @foreach ($notifikasi['trip'] as $notif)
      <div class="card_notif">
        <p class="mb-0 fw-bold">Peringatan Kunjungan</p>
        <p class="mb-0">Hari ini waktunya mengunjungi {{ $notif->nama ?? null }} </p>
        <p class="mb-0"><span class="fw-bold">Alamat:</span> {{ $notif->full_alamat ?? null }}</p>
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

  <div class="reimbursement_notif notif m-fadeOut p-3">
    @foreach ($notifikasi['reimbursement'] as $notif)
      <div class="card_notif">
        <a href="/administrasi/reimbursement/pengajuan/{{ $notif->id ?? null }}" class="text-black text-decoration-none">
          <p class="mb-0">Pengajuan dari {{ $notif->linkStaffPengaju->nama ?? null }}</p>
          <p class="mb-0">Diajukan pada {{ date('d F Y', strtotime($notif->created_at ?? '-')) }}</p>
          @if ($notif->status_enum ?? null)
            <p class="mb-0">{{ $notif->status_enum == '0' ? 'Menunggu Konfirmasi' : 'Menunggu Pembayaran' }}</p>
          @endif
        </a>
      </div>
    @endforeach
  </div>

  <div class="px-5 pt-4" id="dashboardAdmin">
    @if (session()->has('pesanSukses'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('pesanSukses') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

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

    <hr class="my-5">

    <h3 class="my-4">Perencanaan Kunjungan</h3>
    <form class="form-rencanakunjungan" method="POST" action="/administrasi/rencanakunjungan/create">
      @csrf
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="id_staff" class="form-label">Nama Salesman</label>
            <select class="form-select @error('id_staff') is-invalid @enderror" id="id_staff" name="id_staff"
              value="{{ old('id_staff') }}">
              @foreach ($staffs as $staff)
                <option value="{{ $staff->id }}">{{ $staff->nama ?? null }}</option>
              @endforeach
            </select>
            @error('id_staff')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
              id="tanggal" value="{{ old('tanggal') }}">
            @error('tanggal')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="row">
          <div class="col-6">
            <label for="id_customer" class="form-label">Customer yang Dikunjungi</label>
          </div>
        </div>
        <div class="form-input">
          <div class="row">
            <div class="col-6">
              <select class="select-customer form-select @error('id_customer') is-invalid @enderror" id="id_customer"
                name="id_customer[]" value="{{ old('id_customer') }}">
                <option disabled selected value>Pilih Customer</option>
                @foreach ($customers as $customer)
                  <option value="{{ $customer->id }}">
                    {{ $customer->nama ?? null }}</option>
                @endforeach
              </select>
              @error('id_customer')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
              <div class="d-flex justify-content-end my-3">
                <button class="btn btn-danger remove-form me-3" type="button">-</button>
                <button class="btn btn-success add-form" type="button">+</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row justify-content-end mt-4">
        <div class="col-3 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </form>
  </div>

  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

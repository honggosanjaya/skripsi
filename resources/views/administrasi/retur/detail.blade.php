<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@push('JS')
  <script src="{{ mix('js/administrasi.js') }}"></script>
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="/administrasi/retur">Retur</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Retur</li>
  </ol>
@endsection
@section('main_content')

  <div id="retur-admin">
    <div class="px-5 pt-4">
      <h1 class="fs-4 fw-bold mb-4">Retur - {{ $retur->no_retur }}</h1>

      <div class="informasi-list mb_big">
        <span><b>Tanggal Pengajuan</b> {{ date('d M Y', strtotime($retur->created_at)) }}</span>
        <span><b>Nama Customer</b> {{ $retur->linkCustomer->nama }}</span>
        <span><b>Alamat Customer</b> {{ $retur->linkCustomer->full_alamat }}</span>
        <span><b>Wilayah</b> {{ $wilayah[0] }}</span>
        <span><b>No. Telepon</b> {{ $retur->linkCustomer->telepon }}</span>
        <span><b>Pengirim</b> {{ $retur->linkStaffPengaju->nama }}</span>
        <span><b>Admin</b> {{ $retur->linkStaffPengonfirmasi->nama ??null }}</span>
        <span><b>No. Invoice</b> {{ $retur->linkInvoice->nomor_invoice ?? null }}</span>
      </div>

      <table class="table table-hover table-sm mt-5">
        <thead>
          <tr>
            <th scope="col" class="text-center">Kode Barang</th>
            <th scope="col" class="text-center">Nama Barang</th>
            <th scope="col" class="text-center">Jumlah</th>
            <th scope="col" class="text-center">Satuan Barang</th>
            <th scope="col" class="text-center">Harga Barang</th>
            <th scope="col" class="text-center">Alasan Retur</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($items as $item)
            <tr class="text-center">
              <td>{{ $item->linkItem->kode_barang }}</td>
              <td class="text-capitalize">{{ $item->linkItem->nama }}</td>
              <td>{{ $item->kuantitas }}</td>
              <td>{{ $item->linkItem->satuan }}</td>
              <td>{{ number_format($item->kuantitas * $item->harga_satuan, 0, '', '.') }}</td>
              <td>{{ $item->alasan }}</td>
            </tr>
          @endforeach
          <tr>
            <td colspan="4" class="text-center fw-bold">Total Harga</td>
            <td class="text-center">{{ number_format($total_harga, 0, '', '.') }}</td>
          </tr>
        </tbody>
      </table>

      <hr class="my-4">

      <form id="form_submit" class="form-submit" method="POST" action="/administrasi/retur/konfirmasi">
        @csrf
        <h1 class="fs-5 fw-bold">Metode retur : </h1>
        <div class="col-2">
          <input value="{{ $retur->no_retur }}" name="no_retur" type="text" hidden readonly>
          <select class="form-select" name="tipe_retur">
            @foreach ($tipeReturs as $tipeRetur)
              <option value="{{ $tipeRetur->id }}" {{ $retur->status == 12 ? 'disabled' : '' }}
                {{ $tipeRetur->id === $retur->tipe_retur ? 'selected' : '' }}>
                {{ $tipeRetur->nama }}
              </option>
            @endforeach
          </select>
        </div>
        <button type="button" class="btn btn-primary open-modal-retur" data-bs-toggle="modal"
          data-bs-target="#staticBackdrop" hidden>
          Launch static backdrop modal
        </button>
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
          aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Pilih Nomor Invoice yang dipotong</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <select class="form-select" name="id_invoice">
                  @if ($invoices->count() > 0)
                    @foreach ($invoices as $invoice)
                      <option value="{{ $invoice->linkInvoice->id }}"
                        {{ $invoice->linkInvoice->id === ($retur->linkInvoice->id ?? null) ? 'selected' : '' }}>
                        {{ $invoice->linkInvoice->nomor_invoice . ' - Rp.' . $invoice->linkInvoice->harga_total }}
                      </option>
                    @endforeach
                  @endif
                </select>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn button-submit-modal btn-success">Pilih</button>
              </div>
            </div>
          </div>
        </div>
      </form>

      <div class="row">
        <div class="d-flex flex-row justify-content-end">
          <a href="/administrasi/retur/cetak-retur/{{ $retur->no_retur }}" class="btn btn_purple mx-1"><i
              class="bi bi-download px-1"></i>Unduh Retur Penjualan</a>
          @if ($retur->status == 13)
            <button data-id="{{ $retur->linkCustomer->id }}" class="btn btn-success button-submit mx-1"><span
                class="iconify fs-3 me-1" data-icon="healthicons:i-documents-accepted-outline"></span>Konfirmasi</a>
          @endif
        </div>

      </div>
    </div>
  </div>
  </div>
@endsection

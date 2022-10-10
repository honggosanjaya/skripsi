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
    <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
  </ol>
@endsection

@section('main_content')
  <div class="px-5 pt-4">
    <h1 class="fs-4 my-4">Riwayat Pembayaran Customer</h1>
    <div class="row">
      <div class="col-6">
        <div class="informasi-list d-flex flex-column">
          <span><b>Nama Customer</b> {{ $order->linkCustomer->nama ?? null }}</span>
          <span><b>Nomor Invoice</b> {{ $order->linkInvoice->nomor_invoice ?? null }}</span>
          <span><b>Harga Total Invoice</b>
            Rp. {{ number_format($order->linkInvoice->harga_total ?? 0, 0, '', '.') ?? null }}</span>
          <span><b>Sisa Tagihan</b>
            Rp. {{ number_format($order->linkInvoice->harga_total - $total_bayar ?? 0, 0, '', '.') ?? null }}</span>
        </div>
      </div>
    </div>
    <table class="table table-hover table-sm mt-4" id="table">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col" class="text-center">Nama Penagih</th>
          <th scope="col" class="text-center">Jumlah Pembayaran</th>
          <th scope="col" class="text-center">Tanggal</th>
          <th scope="col" class="text-center">Metode Pembayaran</th>
        </tr>
      </thead>
      <tbody>
        @if (count($histories) == 0)
          <tr>
            <td colspan="5" class="text-center text-danger mb-0">Tidak Ada Data</td>
          </tr>
        @else
          @foreach ($histories as $history)
            <tr>
              <th scope="row" class="text-center">{{ $loop->iteration }}</th>
              <td>{{ $history->linkStaffPenagih->nama ?? null }}</td>
              <td class="text-center">{{ number_format($history->jumlah_pembayaran ?? 0, 0, '', '.') ?? null }}</td>
              <td class="text-center">{{ date('d M Y', strtotime($history->tanggal ?? '-')) }}</td>
              @if ($history->metode_pembayaran !== null)
                <td>
                  {{ $history->metode_pembayaran == '1' ? 'Tunai' : ($history->metode_pembayaran == '2' ? 'Giro' : 'Transfer') }}
                </td>
              @endif
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>

    <hr class="my-5">

    <h1 class="fs-4 my-4">Pembayaran Customer</h1>
    <form class="form-submit" method="POST" action="/administrasi/pesanan/detail/{{ $order->id }}/dibayar">
      @csrf
      <input type="hidden" value="{{ $order->linkInvoice->id ?? null }}" name="id_invoice">
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label class="form-label">Invoice</label>
            <input type="text" class="form-control" value="{{ $order->linkInvoice->nomor_invoice ?? null }}" readonly>
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label class="form-label">Nama Customer</label>
            <input type="text" class="form-control" value="{{ $order->linkCustomer->nama ?? null }}" readonly>
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label class="form-label">Nama Pengirim</label>
            <input type="text" class="form-control"
              value="{{ $order->linkOrderTrack->linkStaffPengirim->nama ?? null }}" readonly>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label class="form-label">Nama Penagih</label>
            <select class="form-select" name="id_staff_penagih">
              @foreach ($stafs as $staf)
                <option value="{{ $staf->id }}">{{ $staf->nama }}</option>
              @endforeach
            </select>
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
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label class="form-label">Jumlah Pembayaran</label>
            <div class="input-group">
              <span class="input-group-text" id="basic-addon1">Rp.</span>
              <input type="number" class="form-control @error('jumlah_pembayaran') is-invalid @enderror"
                id="jumlah_pembayaran" name="jumlah_pembayaran" value="{{ old('jumlah_pembayaran') }}">
              @error('jumlah_pembayaran')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
            <small class="text-primary">Jumlah pembayaran maksimal Rp.
              {{ number_format($order->linkInvoice->harga_total - $total_bayar ?? 0, 0, '', '.') ?? null }}</small>
          </div>

          <input type="hidden" value="{{ $order->linkInvoice->harga_total - $total_bayar }}" name="sisatagihan">
        </div>
        <div class="col">
          <div class="mb-3">
            <label class="form-label">Metode Pembayaran</label>
            <select class="form-select" name="metode_pembayaran">
              @foreach ($metodes_pembayaran as $key => $val)
                @if ($order->linkInvoice->metode_pembayaran != null)
                  @if ($order->linkInvoice->metode_pembayaran == $key)
                    <option value="{{ $key }}" selected>{{ $val }}</option>
                  @else
                    <option value="{{ $key }}">{{ $val }}</option>
                  @endif
                @else
                  @if (old('metode_pembayaran') == $key)
                    <option value="{{ $key }}" selected>{{ $val }}</option>
                  @else
                    <option value="{{ $key }}">{{ $val }}</option>
                  @endif
                @endif
              @endforeach
            </select>
          </div>
        </div>
      </div>
      @if ($defaultpenjualan ?? null)
        <div class="row">
          <div class="col-6">
            <div class="mb-3">
              <label for="kas" class="form-label">Pilih Kas yang Bertambah <span
                  class='text-danger'>*</span></label>
              <select class="form-select" name="kas">
                @foreach ($listskas as $kas)
                  @if (old('kas') == $kas->id)
                    <option value="{{ $kas->id }}" selected>{{ $kas->nama }}</option>
                  @else
                    <option value="{{ $kas->id }}">{{ $kas->nama }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
        </div>
      @endif

      <div class="row justify-content-end mt-4">
        <div class="col-3 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">Pesanan Dibayar</button>
        </div>
      </div>
    </form>
  </div>
@endsection

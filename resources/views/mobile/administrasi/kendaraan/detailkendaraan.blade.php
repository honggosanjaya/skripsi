@extends('layouts.mainmobile')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/kendaraan">Kendaraan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
@endsection

@section('main_content')
  <div class="container pt-4">
    <div class="row mb-5">
      <div class="col">
        <div class="informasi-list mb_big">
          <span><b>Nama Kendaraan</b>{{ $vehicle->nama ?? null }}</span>
          @if (count($invoices) > 0)
            <span class="d-flex">
              <b>Invoices</b>
              @foreach ($invoices as $invoice)
                {{ $invoice->nomor_invoice }} <br>
              @endforeach
            </span>
          @else
            <span><b>Invoices</b>Belum Ada</span>
          @endif
        </div>
      </div>
    </div>

    @foreach ($invoices as $invoice)
      <h2 class="fs-6">Tabel {{ $loop->iteration }} - {{ $invoice->nomor_invoice ?? null }}</h2>
      <div class="table-responsive mb-4">
        <table class="table table-bordered table-sm">
          <thead>
            <tr>
              <th scope="col" class="text-center">Kode Barang</th>
              <th scope="col" class="text-center">Nama Item</th>
              <th scope="col" class="text-center">Kuantitas</th>
            </tr>
          </thead>
          <tbody>
            @if ($invoice->linkOrder->linkOrderItem ?? null)
              @foreach ($invoice->linkOrder->linkOrderItem as $item)
                <tr>
                  <td>{{ $item->linkItem->kode_barang ?? null }}</td>
                  <td>{{ $item->linkItem->nama ?? null }}</td>
                  <td class="text-center">{{ $item->kuantitas ?? null }}</td>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </div>
      <hr class="my-4">
    @endforeach

    @if (count($invoices) > 0)
      <div class="row justify-content-end mt-4">
        <div class="col d-flex justify-content-end">
          <a href="/administrasi/kendaraan/{{ $vehicle->id }}/cetak-memo" class="btn btn-primary">
            <i class="bi bi-download px-1"></i>Unduh Memo Persiapan Barang
          </a>
        </div>
      </div>
    @endif
  </div>
@endsection

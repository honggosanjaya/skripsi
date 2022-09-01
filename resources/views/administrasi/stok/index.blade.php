@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Stok</li>
  </ol>
@endsection

@section('main_content')
  <div class="px-5 pt-4">
    <h1 class="fs-4 fw-bold mb-4">Stok Marketing</h1>

    <a href="/administrasi/stok/produk" class="btn btn-primary me-2"><span class="iconify me-2 fs-4"
        data-icon="icon-park-outline:ad-product"></span>List Produk</a>
    <a href="/administrasi/stok/pengadaan?route=pengadaan" class="btn btn-success me-2"><span class="iconify me-2 fs-4"
        data-icon="material-symbols:add-shopping-cart"></span>Pengadaan</a>
    <a href="/administrasi/stok/opname?route=opname" class="btn btn-success me-2"><span class="iconify fs-4 me-2"
        data-icon="healthicons:rdt-result-out-stock-outline"></span>Stok Opname</a>
    <a href="/administrasi/stok/riwayat" class="btn btn_purple me-2"><span class="iconify me-2 fs-4"
        data-icon="ant-design:history-outlined"></span>Riwayat Pengadaan</a>
    <a href="/administrasi/stok/opname/riwayat" class="btn btn_purple me-2"><span class="iconify me-2 fs-4"
        data-icon="ant-design:history-outlined"></span>Riwayat Stok Opname</a>

    <div class="row justify-content-end mt-4">
      <div class="col-4 d-flex justify-content-end">
        <form method="GET" action="/administrasi/stok/cari">
          <div class="input-group">
            <input type="text" class="form-control" name="cari" placeholder="Cari Stok..."
              value="{{ request('cari') }}">
            <button type="submit" class="btn btn-primary">
              <span class="iconify me-2" data-icon="fe:search"></span>Cari
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table teble-hover table-sm mt-4">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Kode<br>Barang</th>
            <th scope="col" class="text-center">Nama Barang</th>
            <th scope="col" class="text-center">Jumlah</th>
            <th scope="col" class="text-center">Satuan</th>
            <th scope="col" class="text-center">Stok<br>Min</th>
            <th scope="col" class="text-center">Stok<br>Maks</th>
            <th scope="col" class="text-center">Pengadaan<br>Maks</th>
            <th scope="col" class="text-center">Harga (Rp)</th>
            <th scope="col" class="text-center">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($items as $item)
            @php
              $stock25 = (($item->max_stok - $item->min_stok) * 25) / 100 + $item->min_stok;
            @endphp
            @if ($item->stok < $item->min_stok)
              <tr class="bg-danger">
              @elseif($item->stok < $stock25)
              <tr class="bg-warning">
              @else
              <tr>
            @endif
            <th scope="row" class="text-center">
              {{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</th>
            <td class="text-center">{{ $item->kode_barang }}</td>
            <td>{{ $item->nama }}</td>
            <td class="text-center">{{ number_format($item->stok, 0, '', '.') }}</td>
            <td class="text-center">{{ $item->satuan }}</td>
            <td class="text-center">{{ number_format($item->min_stok, 0, '', '.') }}</td>
            <td class="text-center">{{ number_format($item->max_stok, 0, '', '.') }}</td>
            <td class="text-center">{{ number_format($item->max_pengadaan, 0, '', '.') }}</td>
            <td>{{ number_format($item->harga_satuan, 0, '', '.') }}</td>
            <td class="text-capitalize text-center">{{ $item->status_enum == '1' ? 'Active' : 'Inactive' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="d-flex flex-row mt-4">
      {{ $items->links() }}
    </div>
  </div>
@endsection

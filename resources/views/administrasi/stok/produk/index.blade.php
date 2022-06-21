@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item active" aria-current="page">Produk</li>
  </ol>
@endsection

@section('main_content')
  @if (session()->has('pesanSukses'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('pesanSukses') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <div class="px-5 pt-4">
    <div class="d-flex justify-content-between">
      <form method="GET" action="/administrasi/stok/produk/cari">
        <div class="input-group">
          <input type="text" class="form-control" name="cari" placeholder="Cari Produk..."
            value="{{ request('cari') }}">
          <button type="submit" class="btn btn-primary">
            <span class="iconify me-2" data-icon="fe:search"></span>Cari
          </button>
        </div>
      </form>
      <a href="/administrasi/stok/produk/create" class="btn btn-primary">
        <span class="iconify fs-4" data-icon="dashicons:database-add"></span> Tambah Produk
      </a>
    </div>

    <div class="table-responsive mt-5">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Kode Barang</th>
            <th scope="col" class="text-center">Gambar</th>
            <th scope="col" class="text-center">Satuan</th>
            <th scope="col" class="text-center">Stok</th>
            <th scope="col" class="text-center">Min Stok</th>
            <th scope="col" class="text-center">Max Stok</th>
            <th scope="col" class="text-center">Harga Satuan (Rp)</th>
            <th scope="col" class="text-center">Volume</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($items as $item)
            @php
              $stock25 = (($item->max_stok - $item->min_stok) * 25) / 100 + $item->min_stok;
            @endphp
            @if ($item->stok < $item->min_stok)
              <tr class="bg-dangerr">
              @elseif($item->stok < $stock25)
              <tr class="bg-warningg">
              @else
              <tr>
            @endif
            <td class="text-center">{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
            <td>{{ $item->kode_barang }}</td>
            <td class="text-center">
              @if ($item->gambar)
                <img src="{{ asset('storage/item/' . $item->gambar) }}" class="img-fluid" width="40">
              @else
                <img src="{{ asset('images/default_produk.png') }}" class="img-fluid" width="40">
              @endif
            </td>
            <td>{{ $item->satuan }}</td>
            <td>{{ $item->stok }}</td>
            <td>{{ $item->min_stok }}</td>
            <td>{{ $item->max_stok }}</td>
            <td>{{ number_format($item->harga_satuan, 0, '', '.') }}</td>
            <td class="text-capitalize">{{ $item->volume }}</td>
            <td>{{ $item->linkStatus->nama }}</td>
            <td>
              <div class="d-flex flex-column">
                <a href="/administrasi/stok/produk/{{ $item->id }}/edit" class="btn btn-sm btn-warning border w-75">
                  <span class="iconify me-2" data-icon="ant-design:edit-filled"></span> edit
                </a>

                <form action="/administrasi/stok/produk/ubahstatus/{{ $item->id }}" method="POST">
                  @csrf
                  <button type="submit"
                    class="btn btn-sm mt-2 {{ $item->linkStatus->nama === 'active' ? 'btn-danger' : 'btn-success' }}">
                    @if ($item->linkStatus->nama === 'active')
                      <span class="iconify" data-icon="material-symbols:cancel-outline"></span>
                    @else
                      <span class="iconify" data-icon="akar-icons:double-check"></span>
                    @endif
                    {{ $item->linkStatus->nama === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                  </button>
                </form>
              </div>
            </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      {{ $items->links() }}
    </div>
  @endsection

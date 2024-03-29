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
  @if (session()->has('successMessage'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('successMessage') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <div class="px-5 pt-4">
    <a href="/administrasi/stok/produk/create" class="btn btn-primary me-2">
      <span class="iconify fs-3 me-2" data-icon="dashicons:database-add"></span> Tambah Produk
    </a>
    <a href="/administrasi/stok/produk/pricelist" class="btn btn-success me-2">
      <span class="iconify fs-3 me-2" data-icon="material-symbols:price-change-outline"></span> Price List
    </a>
    <a href="/administrasi/stok/produk/grouplist" class="btn btn-danger">
      <span class="iconify fs-3 me-2" data-icon="fluent-mdl2:engineering-group"></span> Group List
    </a>

    <div class="table-responsive mt-3">
      <table class="table table-hover table-sm" id="table">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Kode Barang</th>
            <th scope="col" class="text-center">Nama Barang</th>
            <th scope="col" class="text-center">Kategori</th>
            <th scope="col" class="text-center">Satuan</th>
            <th scope="col" class="text-center">Stok</th>
            <th scope="col" class="text-center">Min Stok</th>
            <th scope="col" class="text-center">Max Stok</th>
            <th scope="col" class="text-center">Harga1 Satuan (Rp)</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($items as $item)
            @php
              $stock25 = ((($item->max_stok ?? 0) - ($item->min_stok ?? 0)) * 25) / 100 + ($item->min_stok ?? 0);
            @endphp
            @if (($item->stok ?? 0) < ($item->min_stok ?? 0))
              <tr class="bg-dangerr">
              @elseif(($item->stok ?? 0) < $stock25)
              <tr class="bg-warningg">
              @else
              <tr>
            @endif
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ $item->kode_barang ?? null }}</td>
            <td>{{ $item->nama ?? null }}</td>
            <td class="text-center">{{ $item->id_category ? $item->linkCategoryItem->nama : null }}</td>
            <td>{{ $item->satuan ?? null }}</td>
            <td>{{ number_format($item->stok ?? 0, 0, '', '.') }}</td>
            <td>{{ number_format($item->min_stok ?? 0, 0, '', '.') }}</td>
            <td>{{ number_format($item->max_stok ?? 0, 0, '', '.') }}</td>
            <td>{{ number_format($item->harga1_satuan ?? 0, 0, '', '.') }}</td>
            @if ($item->status_enum != null)
              <td>{{ $item->status_enum == '1' ? 'Active' : 'Inactive' }}</td>
            @endif
            <td>
              <div class="d-flex flex-column">
                <a href="/administrasi/stok/produk/{{ $item->id }}/edit" class="btn btn-sm btn-warning w-100">
                  <span class="iconify me-2" data-icon="ant-design:edit-filled"></span> Edit
                </a>

                <form action="/administrasi/stok/produk/ubahstatus/{{ $item->id }}" method="POST">
                  @csrf
                  @if ($item->status_enum != null)
                    <button type="submit"
                      class="btn btn-sm mt-2 {{ $item->status_enum === '1' ? 'btn-danger' : 'btn-success' }}">
                      @if ($item->status_enum === '1')
                        <span class="iconify" data-icon="material-symbols:cancel-outline"></span>
                      @else
                        <span class="iconify" data-icon="akar-icons:double-check"></span>
                      @endif
                      {{ $item->status_enum === '1' ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                  @endif
                </form>
              </div>
            </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @push('JS')
      <script src="{{ mix('js/administrasi.js') }}"></script>
    @endpush
  @endsection

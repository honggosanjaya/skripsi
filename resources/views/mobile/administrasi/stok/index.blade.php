@extends('layouts.mainmobile')
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
  <div class="container pt-4">
    <h1 class="fs-5 mb-4">Stok Marketing</h1>

    <div class="table-responsive">
      <table class="table teble-hover table-sm" id="table">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Kode<br>Barang</th>
            <th scope="col" class="text-center">Nama Barang</th>
            <th scope="col" class="text-center">Jumlah</th>
            <th scope="col" class="text-center">Satuan</th>
            <th scope="col" class="text-center">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($items as $item)
            @php
              $stock25 = ((($item->max_stok ?? 0) - ($item->min_stok ?? 0)) * 25) / 100 + ($item->min_stok ?? 0);
            @endphp
            @if (($item->stok ?? 0) < ($item->min_stok ?? 0))
              <tr class="bg-danger">
              @elseif(($item->stok ?? 0) < $stock25)
              <tr class="bg-warning">
              @else
              <tr>
            @endif
            <th scope="row" class="text-center">
              {{ $loop->iteration }}</th>
            <td class="text-center">{{ $item->kode_barang ?? null }}</td>
            <td>{{ $item->nama ?? null }}</td>
            <td class="text-center">{{ number_format($item->stok ?? 0, 0, '', '.') }}</td>
            <td class="text-center">{{ $item->satuan ?? null }}</td>
            @if ($item->status_enum != null)
              <td class="text-capitalize text-center">{{ $item->status_enum == '1' ? 'Active' : 'Inactive' }}</td>
            @endif
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

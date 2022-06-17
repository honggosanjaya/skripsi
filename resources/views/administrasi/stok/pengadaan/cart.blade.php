@extends('layouts.main')
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
  <li class="breadcrumb-item"><a href="/administrasi/stok/pengadaan">Pengadaan</a></li>
  <li class="breadcrumb-item active" aria-current="page">Cart</li>
</ol>
@endsection
@section('main_content')
  @if ($message = Session::get('success'))
    <p class="text-success">{{ $message }}</p>
  @endif

  <table class="table table-hover table-sm">
    <thead>
      <tr>
        <th scope="col">Kode Barang</th>
        <th scope="col">Nama</th>
        <th scope="col">Jumlah</th>
        <th scope="col">Satuan</th>
        <th scope="col">Total Harga</th>
      </tr>
    </thead>
    <tbody>

      @foreach ($cartItems as $item)
        <tr>
          <td>{{ $item->attributes->kode_barang }}</td>
          <td>{{ $item->name }}</td>
          <td>{{ $item->quantity }}</td>
          <td>{{ $item->attributes->satuan }}</td>
          <td>{{ $item->attributes->total_harga }}</td>
          {{-- <td>
            <form action="{{ route('cart.remove') }}" method="POST">
              @csrf
              <input type="hidden" value="{{ $item->id }}" name="id">
              <button class="btn btn-sm text-danger">x</button>
            </form>
          </td> --}}
        </tr>
      @endforeach
    </tbody>
  </table>


  @php
  $totalAkhir = 0;
  foreach ($cartItems as $item) {
      $totalAkhir += $item->attributes->total_harga;
  }
  @endphp

  <form method="POST" action="/administrasi/stok/pengadaan/tambahpengadaan?route=pengadaan" enctype="multipart/form-data">
    @csrf
    <div class="my-3">
      <label for="harga_total" class="form-label">Total Harga</label>
      <input type="text" class="form-control" id="harga_total" name="harga_total" value="{{ $totalAkhir }}" readonly>
    </div>

    <div class="my-3">
      <label for="no_nota" class="form-label">No Nota</label>
      <input type="text" class="form-control @error('no_nota') is-invalid @enderror" id="no_nota" name="no_nota"
        value="{{ old('no_nota') }}">
      @error('no_nota')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="keterangan" class="form-label">Keterangan</label>
      <input type="text" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
        value="{{ old('keterangan') }}">
      @error('keterangan')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
  </form>


  <div>
    <form action="{{ route('cart.clear').'?route=pengadaan' }}" method="POST">
      @csrf
      <button class="btn btn-danger">Remove All Cart</button>
    </form>
  </div>
@endsection

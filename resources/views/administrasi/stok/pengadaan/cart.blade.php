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
  <div id="data-pengadaan">
    <div class="px-5 pt-4">
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
              <td>{{ $item->attributes->kode_barang ?? null }}</td>
              <td>{{ $item->name ?? null }}</td>
              <td>{{ $item->quantity ?? null }}</td>
              <td>{{ $item->attributes->satuan ?? null }}</td>
              <td>{{ number_format($item->attributes->total_harga ?? 0, 0, '', '.') }}</td>
              {{-- <td> --}}
              {{-- <form action="{{ route('cart.remove') . '?route=pengadaan' }}" method="POST">
                  @csrf --}}
              {{-- <input type="hidden" value="{{ $item->id }}" name="id"> --}}
              {{-- <form>
                  <button class="btn btn-sm text-danger" data-iditem="{{ $item->id }}">x</button>
                </form> --}}
              {{-- </td> --}}
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
      <hr class="mt-5 mb-4">
      <form method="POST" id='data-form' action="/administrasi/stok/pengadaan/tambahpengadaan?route=pengadaan"
        enctype="multipart/form-data">
        @csrf

        <div class="row">
          <div class="col">
            <div class="mb-3">
              <label for="harga_total" class="form-label">Total Harga</label>
              <input type="text" class="form-control" id="harga_total" name="harga_total"
                value="{{ $totalAkhir ?? null }}" readonly>
            </div>
          </div>
          <div class="col">
            <div class="mb-3">
              <label for="no_nota" class="form-label">No Nota <span class='text-danger'>*</span></label>
              <input type="text" class="form-control @error('no_nota') is-invalid @enderror" id="no_nota"
                name="no_nota" value="{{ old('no_nota') }}">
              @error('no_nota')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="keterangan" class="form-label">Keterangan <span class='text-danger'>*</span></label>
          <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan">{{ old('keterangan') }}</textarea>
          @error('keterangan')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="row justify-content-end mt-4">
          <div class="col d-flex justify-content-end">
            {{-- <form action="{{ route('cart.clear') . '?route=pengadaan' }}" method="POST">
            @csrf
            <button class="btn btn-danger me-2">
              <span class="iconify fs-3 me-1" data-icon="bxs:trash"></span>Remove All Car
            </button>
          </form> --}}
            <a type="button" class="btn btn-danger me-3" href="/administrasi/stok/pengadaan/clear?route=pengadaan">
              <span class="iconify fs-3 me-1" data-icon="bxs:trash"></span>Remove All Cart
            </a>

            <button type="submit" class="btn btn-success btn-submit"><span class="iconify fs-3 me-1"
                data-icon="akar-icons:double-check"></span>Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

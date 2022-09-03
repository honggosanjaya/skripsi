@extends('layouts.main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/opname">Stok Opname</a></li>
    <li class="breadcrumb-item active" aria-current="page">Cart</li>
  </ol>
@endsection

@section('main_content')
  @if ($message = Session::get('success'))
    <p class="text-success">{{ $message }}</p>
  @endif
  <div id="data-opname">
    <div class="px-5 pt-4">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Jumlah Setelah<br>Perubahan</th>
            <th scope="col">Perubahan</th>
            <th scope="col">Keterangan</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($cartItems as $item)
            <tr>
              {{-- <form action="{{ '/administrasi/stok/opname/update-final?route=opname' }}" method="POST" enctype="multipart/form-data"> --}}
              {{-- @csrf --}}
              <td>{{ $item->attributes->kode_barang ?? null }}</td>
              <td>{{ $item->name ?? null }}</td>
              <td>{{ $item->quantity ?? null }}</td>
              <td>{{ ($item->quantity ?? null) + ($item->attributes->jumlah ?? null) }}</td>
              <td>{{ $item->attributes->jumlah ?? null }}</td>
              <td>{{ $item->attributes->keterangan ?? null }}</td>

              {{-- <td>
              <input type="hidden" value="{{ $item->id }}" name="id">
              <input type="hidden" value="{{$item->attributes->kode_barang }}" name="kode_barang">
              <input type="number" class="form-control" id="quantity" name="jumlah" 
                value="{{ $item->attributes->jumlah??null }}"></td>
            <td><input type="text" class="form-control" id="keterangan" name="keterangan"
                value="{{ $item->attributes->keterangan??null }}"></td>

            <td><button type="submit">Submit</button></td> --}}
              {{-- </form> --}}

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
        $totalAkhir = null;
        foreach ($cartItems as $item) {
            $totalAkhir += $item->attributes->total_harga;
        }
      @endphp

      <div class="row justify-content-end mt-4">
        <div class="col d-flex justify-content-end">
          <a type="button" class="btn btn-danger me-3" href="/administrasi/stok/opname/clear?route=opname">
            <span class="iconify fs-3 me-1" data-icon="bxs:trash"></span>Remove All Cart
          </a>
          <a type="button" class="btn btn-success btn-submit" href="/administrasi/stok/opname/tambahopname?route=opname">
            <span class="iconify fs-3 me-1" data-icon="akar-icons:double-check"></span>
            Submit
          </a>
        </div>
      </div>
    </div>
  </div>
  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

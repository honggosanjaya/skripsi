@extends('layouts.main')

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
        <th scope="col">Jumlah setelah berubah</th>
        <th scope="col">perubahan</th>
        <th scope="col">keterangan</th>
        {{-- <th scope="col">action</th> --}}
      </tr>
    </thead>
    <tbody>

      @foreach ($cartItems as $item)
        <tr>
          {{-- <form action="{{ '/administrasi/stok/opname/update-final?route=opname' }}" method="POST" enctype="multipart/form-data"> --}}
            {{-- @csrf --}}
            <td>{{ $item->attributes->kode_barang }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->quantity + $item->attributes->jumlah}}</td>
            <td>{{ $item->attributes->jumlah}}</td>
            <td>{{ $item->attributes->keterangan}}</td>

            {{-- <td>
              <input type="hidden" value="{{ $item->id }}" name="id">
              <input type="hidden" value="{{$item->attributes->kode_barang }}" name="kode_barang">
              <input type="number" class="form-control" id="quantity" name="jumlah" 
                value="{{ $item->attributes->jumlah??null }}"></td>
            <td><input type="text" class="form-control" id="keterangan" name="keterangan"
                value="{{ $item->attributes->keterangan??null }}"></td>

            <td><button type="submit">Submit</button></td> --}}
          </form>

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

  <a type="button" class="btn btn-primary" href="/administrasi/stok/opname/tambahopname?route=opname">Submit</button>
  <a type="button" class="btn btn-primary" href="/administrasi/stok/opname/clear?route=opname">Remove All Cart</button>

    
@endsection

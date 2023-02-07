@extends('layouts.main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/stokretur">Stok Retur</a></li>
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
            <th scope="col" class="text-center">Kode Barang</th>
            <th scope="col" class="text-center">Nama</th>
            <th scope="col" class="text-center">Satuan</th>
            <th scope="col" class="text-center">Pengurangan<br>Stok Retur</th>
            <th scope="col" class="text-center">Penambahan<br>Stok Retur</th>
            {{-- <th scope="col">Metode</th> --}}
          </tr>
        </thead>
        <tbody>
          @foreach ($cartItems as $item)
            <tr>
              <td class="text-center">{{ $item->attributes->kode_barang ?? null }}</td>
              <td>{{ $item->name ?? null }}</td>
              <td class="text-center">{{ $item->attributes->satuan ?? null }}</td>
              <td class="text-center">{{ $item->quantity ?? null }}</td>
              <td class="text-center">
                @if ($item->attributes->metode ?? null)
                  @if ($item->attributes->metode == 'tukarguling')
                    {{ $item->quantity ?? null }}
                  @else
                    0
                  @endif
                @else
                  {{ $item->attributes->stok ?? null }}
                @endif
              </td>

              {{-- <td>{{ $item->attributes->metode ?? null }}</td> --}}
            </tr>
          @endforeach
        </tbody>
      </table>


      {{-- <hr class="mt-5 mb-4"> --}}
      <form method="POST" id='data-form' action="/administrasi/stok/stokretur/tambahstokretur?route=stokretur"
        enctype="multipart/form-data" class="mt-5">
        @csrf

        @if ($shouldShowKas == true)
          <div class="row">
            <div class="col">
              <div class="mb-3">
                <label class="form-label">Jumlah Pengembalian <span class='text-danger'>*</span></label>
                <div class="input-group">
                  <span class="input-group-text" id="basic-addon1">Rp.</span>
                  <input type="number" class="form-control @error('uang') is-invalid @enderror" id="uang"
                    name="uang" value="{{ old('uang') }}">
                  @error('uang')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
              </div>
            </div>

            @if ($defaultpengadaan ?? null)
              <div class="col">
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
            @endif
          </div>
        @endif

        <div class="row justify-content-end mt-4">
          <div class="col d-flex justify-content-end">
            <a type="button" class="btn btn-danger me-3" href="/administrasi/stok/stokretur/clear?route=stokretur">
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

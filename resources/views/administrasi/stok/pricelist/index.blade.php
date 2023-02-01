@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/produk">Produk</a></li>
    <li class="breadcrumb-item active" aria-current="page">Price list</li>
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
    <form action="/administrasi/stok/produk/pricelist" method="get" class="mb-5">
      <div class="row">
        <div class="col-4">
          <select class="form-select" name="tipe_harga">
            <option value="1" {{ $input['tipe_harga'] == '1' ? 'selected' : '' }}>Harga 1</option>
            <option value="2" {{ $input['tipe_harga'] == '2' ? 'selected' : '' }}>Harga 2</option>
            <option value="3" {{ $input['tipe_harga'] == '3' ? 'selected' : '' }}>Harga 3</option>
            <option value="hpp" {{ $input['tipe_harga'] == 'hpp' ? 'selected' : '' }}>HPP</option>
          </select>
        </div>
        <div class="col-5">
          <button type="submit" class="btn btn-primary"><span class="iconify fs-4 me-1"
              data-icon="ic:baseline-filter-alt"></span>Filter</button>
        </div>
        <div class="col-3">
          <div class="float-end">
            <a href="/administrasi/stok/produk/pricelist/edit" class="btn btn-warning"><span class="iconify fs-5 me-1"
                data-icon="eva:edit-2-fill"></span>Edit Price List</a>
          </div>
        </div>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-hover table-sm table-top" id="table">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Nama Barang</th>
            <th scope="col" class="text-center">Now</th>
            @foreach ($tanggal as $tgl)
              <th scope="col" class="text-center">{{ $tgl }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach ($items as $item)
            <tr>
              <td class="text-center" style="vertical-align: middle !important;">{{ $loop->iteration }}</td>
              <td class="{{ $item->status_enum == '1' ? 'text-success' : 'text-danger' }}"
                style="vertical-align: middle !important;">
                {{ $item->nama ?? null }}
              </td>

              @if ($input['tipe_harga'] ?? null)
                @if ($input['tipe_harga'] == '1' && $item->harga1_satuan ?? null)
                  <td class="text-end">{{ number_format($item->harga1_satuan, 0, '', '.') }}</td>
                @elseif ($input['tipe_harga'] == '2' && $item->harga2_satuan ?? null)
                  <td class="text-end">{{ number_format($item->harga2_satuan, 0, '', '.') }}</td>
                @elseif ($input['tipe_harga'] == '3' && $item->harga3_satuan ?? null)
                  <td class="text-end">{{ number_format($item->harga3_satuan, 0, '', '.') }}</td>
                @elseif ($input['tipe_harga'] == 'hpp' && $item->hargahpp_satuan ?? null)
                  <td class="text-end">{{ number_format($item->hargahpp_satuan, 0, '', '.') }}</td>
                @else
                  <td></td>
                @endif
              @else
                <td class="text-end">{{ number_format($item->harga1_satuan ?? null, 0, '', '.') }}</td>
              @endif

              @foreach ($tanggal as $tgl)
                <td class="text-end">
                  @foreach ($pricetgl[$tgl] as $price)
                    @if ($price->id_item == $item->id)
                      @if ($price->price ?? null)
                        {{ number_format($price->price, 0, '', '.') }} <br>
                      @endif
                    @endif
                  @endforeach
                </td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endsection

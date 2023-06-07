@extends('layouts.mainreact')

@push('CSS')
@endpush

@push('JS')
@endpush

@section('main_content')
  <div class="page_container pt-4">
    <div class="d-flex justify-content-end">
      <a href="/salesman/itemkanvas/history" class="btn btn-primary btn-sm mb-4">
        <span class="iconify fs-3 text-white me-2" data-icon="ic:round-history"></span>History
      </a>
    </div>

    @if (count($kanvas) == 0)
      <p class="mb-0 text-danger text-center mt-5">Tidak Ada Kanvas yang Sedang Aktif</p>
    @else
      <div class='info-2column'>
        <span class='d-flex'>
          <b>Nama kanvas</b>
          <p class='mb-0 word_wrap'>{{ $kanvas[0]->nama ?? null }}</p>
        </span>
        <span class='d-flex'>
          <b>Waktu Dibawa</b>
          <p class='mb-0 word_wrap'>{{ date('j F Y, g:i a', strtotime($kanvas[0]->waktu_dibawa)) }}</p>
        </span>
      </div>

      <div class="table-responsive">
        <table class="table mt-3">
          <thead>
            <tr>
              <th scope="col" class='text-center'>No</th>
              <th scope="col" class='text-center'>Nama Barang</th>
              <th scope="col" class='text-center'>Stok Awal</th>
              <th scope="col" class='text-center'>Sisa Stok</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($kanvas as $data)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $data->linkItem->nama ?? null }}</td>
                <td class="text-center">{{ $data->stok_awal ?? null }}</td>
                <td class="text-center">{{ $data->sisa_stok ?? null }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
@endsection

@extends('layouts/main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/stokopname">Stok Opname</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <div class="row mt-5">
      <div class="col-6">
        <div class="informasi-list d-flex flex-column">
          <span><b>Staf pengaju</b> {{ $opname->linkStaff->nama ?? null }}</span>
          <span><b>Tanggal Pengajuan</b> {{ date('d F Y', strtotime($opname->created_at ?? '-')) }}</span>
          @if ($opname->status ?? null)
            <span><b>Status Pengajuan</b>
              @if ($opname->status == 15)
                <p class="text-danger fw-bold d-inline">Diajukan</p>
              @elseif ($opname->status == 14)
                <p class="text-success fw-bold d-inline">Dikonfirmasi</p>
              @endif
            </span>
          @endif
        </div>
      </div>
    </div>

    <table class="table table-bordered mt-4">
      <thead>
        <tr>
          <th scope="col">Kode Barang</th>
          <th scope="col">Nama Barang</th>
          <th scope="col">Kuantitas</th>
          <th scope="col">Keterangan</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($opname->linkOrderItem as $item)
          <tr>
            <td>{{ $item->linkItem->kode_barang ?? null }}</td>
            <td>{{ $item->linkItem->nama ?? null }}</td>
            <td>{{ $item->kuantitas ?? null }}</td>
            <td>{{ $item->keterangan ?? null }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="row justify-content-end mt-4">
      <div class="col d-flex justify-content-end">
        <form action="/supervisor/stokopname/tolak/{{ $opname->id ?? null }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-sm btn-danger me-3">
            <span class="iconify fs-5 me-1" data-icon="emojione-monotone:heavy-multiplication-x"></span> Tolak
          </button>
        </form>

        <form action="/supervisor/stokopname/setuju/{{ $opname->id ?? null }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-sm btn-success">
            <span class="iconify fs-5 me-1" data-icon="akar-icons:check"></span> Setuju
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection

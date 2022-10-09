@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Kanvas</li>
  </ol>
@endsection

@section('main_content')
  @if (session()->has('pesanSukses'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('pesanSukses') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <div class="px-5 pt-4" id="kanvas">
    <a href="/administrasi/kanvas/create" class="btn btn-primary mb-5">
      <span class="iconify fs-3 me-2" data-icon="dashicons:database-add"></span>Tambah Kanvas
    </a>

    <a href="/administrasi/kanvas/history" class="btn btn_purple mb-5">
      <span class="iconify fs-3 me-2" data-icon="bx:history"></span>History Kanvas
    </a>

    <h1 class="mb-0 fs-4">Kanvas yang Sedang Aktif</h1>
    <table class="table table-hover table-sm mt-4" id="table">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col" class="text-center">Nama</th>
          <th scope="col" class="text-center">Pengonfirmasi Pembawaan</th>
          <th scope="col" class="text-center">Yang Membawa</th>
          <th scope="col" class="text-center">Waktu Dibawa</th>
          <th scope="col" class="text-center">Banyak Jenis Item</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($listkanvas as $kanvas)
          <tr>
            <th scope="row" class="text-center">{{ $loop->iteration }}</th>
            <td>{{ $kanvas->nama ?? null }}</td>
            <td>{{ $kanvas->linkStaffPengonfirmasiPembawaan->nama ?? null }}</td>
            <td>{{ $kanvas->linkStaffYangMembawa->nama ?? null }}</td>
            @if ($kanvas->waktu_dibawa ?? null)
              <td> {{ date('d F Y, g:i a', strtotime($kanvas->waktu_dibawa)) }}</td>
            @else
              <td></td>
            @endif
            <td class="text-center">{{ $kanvas->banyak_jenis_item ?? null }}</td>
            <td class="text-center">
              <a class="btn btn-primary detail_trigger" data-bs-toggle="modal"
                data-bs-target="#kanvas{{ str_replace(',', '-', $kanvas->ids) }}"
                data-idkanvas="{{ str_replace(',', '-', $kanvas->ids) }}">
                <span class="iconify fs-4" data-icon="fluent:apps-list-detail-24-filled"></span> Detail
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>


    @foreach ($listkanvas as $kanvas)
      <div class="modal fade" id="kanvas{{ str_replace(',', '-', $kanvas->ids) }}" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Detail Kanvas</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="info-list">
                <span class="d-flex"><b>Nama Kanvas</b>
                  {{ $kanvas->nama ?? null }}</span>
                <span class="d-flex"><b>Sales yang Membawa</b>
                  {{ $kanvas->linkStaffYangMembawa->nama ?? null }}</span>
                @if ($kanvas->waktu_dibawa ?? null)
                  <span class="d-flex"><b>Waktu Dibawa</b>
                    {{ date('d F Y, g:i a', strtotime($kanvas->waktu_dibawa)) }}</span>
                @else
                  <span class="d-flex"><b>Waktu Dibawa</b></span>
                @endif
              </div>

              <div class="table-responsive mt-4">
                <table class="table table-hover table-sm">
                  <thead>
                    <tr>
                      <th scope="col" class="text-center">No</th>
                      <th scope="col" class="text-center">Nama Barang</th>
                      <th scope="col" class="text-center">Stok Awal</th>
                      <th scope="col" class="text-center">Sisa Stok</th>
                    </tr>
                  </thead>
                  <tbody class="table_body">
                  </tbody>
                </table>
              </div>

              <div class="row">
                <div class="col d-flex justify-content-end">
                  <form action="/administrasi/kanvas/dikembalikan/{{ str_replace(',', '-', $kanvas->ids) }}"
                    method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                      <span class="iconify fs-4" data-icon="fluent:archive-arrow-back-32-regular"></span> Dikembalikan
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

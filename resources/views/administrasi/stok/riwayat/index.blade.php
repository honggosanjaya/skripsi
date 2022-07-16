@extends('layouts/main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item active" aria-current="page">Riwayat Pengadaan</li>
  </ol>
@endsection

@section('main_content')
  <div class="px-5 pt-4">
    <h1 class="fs-4 fw-bold">Riwayat Pengadaan</h1>
    <table class="table" id="table">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col" class="text-center">Tanggal</th>
          <th scope="col" class="text-center">No Nota</th>
          <th scope="col" class="text-center">Keterangan</th>
          <th scope="col" class="text-center">Total Harga</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($pengadaans as $pengadaan)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ date('d M Y G:i', strtotime($pengadaan->created_at)) }}</td>
            <td>{{ $pengadaan->no_nota }}</td>
            <td>{{ $pengadaan->keterangan }}</td>
            <td>{{ number_format($pengadaan->harga, 0, '', '.') }}</td>
            <td>
              <div class="d-flex justify-content-center">
                <a href="/administrasi/stok/riwayat/detail/{{ $pengadaan->no_pengadaan }}" class="btn btn-primary"><span
                    class="iconify fs-4 me-1" data-icon="fluent:apps-list-detail-24-filled"></span>Detail</a>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{-- <div class="d-flex flex-row mt-4">
      {{ $pengadaans->links() }}
    </div> --}}
  </div>
  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

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
    <table class="table" id="table">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Tanggal</th>
          <th scope="col">No Nota</th>
          <th scope="col">Keterangan</th>
          <th scope="col">Total Harga</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($pengadaans as $pengadaan)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $pengadaan->created_at }}</td>
            <td>{{ $pengadaan->no_nota }}</td>
            <td>{{ $pengadaan->keterangan }}</td>
            <td>{{ $pengadaan->harga }}</td>
            <td>
              <a href="/administrasi/stok/riwayat/detail/{{ $pengadaan->no_pengadaan }}"
                class="btn btn-primary">Detail</a>
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

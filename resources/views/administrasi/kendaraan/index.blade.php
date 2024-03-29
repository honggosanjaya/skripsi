@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Kendaraan</li>
  </ol>
@endsection
@section('main_content')
  @if (session()->has('successMessage'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
        {{ session('successMessage') }}
        <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  @endif


  <div class="px-5 pt-4">
    <div class="d-flex align-items-center justify-content-between">
      <form method="GET" action="/administrasi/kendaraan/cari">
        <div class="input-group">
          <input type="text" class="form-control" name="cari" placeholder="Cari Kendaraan..."
            value="{{ request('cari') }}">
          <button type="submit" class="btn btn-primary">
            <span class="iconify me-2" data-icon="fe:search"></span>Cari
          </button>
        </div>
      </form>
      <a href="/administrasi/kendaraan/tambah" class="btn btn-primary"><span class="iconify fs-4 me-1"
          data-icon="dashicons:database-add"></span>Tambah Kendaraan</a>
    </div>

    <div class="table-responsive mt-4">
      <table class="table table-bordered table-sm">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Plat Nomor</th>
            <th scope="col" class="text-center">Nama Kendaraan</th>
            <th scope="col" class="text-center">Kapasitas<br>Volume (cm3)</th>
            <th scope="col" class="text-center">Kapasitas<br>Harga (Rp)</th>
            <th scope="col" class="text-center">Tanggal<br>Pajak</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($vehicles as $vehicle)
            <tr>
              <th scope="row" class="text-center">
                {{ ($vehicles->currentPage() - 1) * $vehicles->perPage() + $loop->iteration }}</th>
              <td class="text-uppercase">{{ $vehicle->kode_kendaraan ?? null }}</td>
              <td>{{ $vehicle->nama ?? null }}</td>
              <td>{{ number_format($vehicle->kapasitas_volume ?? 0, 0, '', '.') }}</td>
              <td>{{ number_format($vehicle->kapasitas_harga ?? 0, 0, '', '.') }}</td>
              @if ($vehicle->tanggal_pajak != null)
                <td class="text-center" data-order="{{ date('Y-m-d', strtotime($vehicle->tanggal_pajak ?? '-')) }}">
                  {{ date('d M Y', strtotime($vehicle->tanggal_pajak ?? '-')) }}
                </td>
              @else
                <td></td>
              @endif
              <td class="text-center">
                <a href="/administrasi/kendaraan/ubah/{{ $vehicle->id }}" class="btn btn-warning"><span
                    class="iconify fs-5 me-1" data-icon="eva:edit-2-fill"></span>Ubah</a>

                <a href="/administrasi/kendaraan/{{ $vehicle->id }}" class="btn btn-primary">
                  <span class="iconify fs-4" data-icon="fluent:apps-list-detail-24-filled"></span> Detail</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="d-flex flex-row mt-4">
      {{ $vehicles->links() }}
    </div>
  </div>
@endsection

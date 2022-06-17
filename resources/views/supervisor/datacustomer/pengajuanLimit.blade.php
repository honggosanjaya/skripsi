@extends('layouts/main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/datacustomer">Data Customer</a></li>
    <li class="breadcrumb-item active" aria-current="page">Pengajuan</li>
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

  <div class="table-responsive mt-3">
    <table class="table table-hover table-sm">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col" class="text-center">Foto Tempat Usaha</th>
          <th scope="col" class="text-center">Nama</th>
          <th scope="col" class="text-center">Alamat Lengkap</th>
          <th scope="col" class="text-center">Limit Pembelian</th>
          <th scope="col" class="text-center">Pengajuan Limit Pembelian</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @if (sizeof($customers) > 0)
          @foreach ($customers as $customer)
            <tr>
              <th scope="row">{{ $loop->iteration }}</th>
              <td class="text-center">
                @if ($customer->foto != null)
                  <img src="{{ asset('storage/customer/' . $customer->foto) }}" class="img-fluid" width="40">
                @else
                  <img src="{{ asset('images/default_fototoko.png') }}" class="img-fluid" width="40">
                @endif
              </td>
              <td class="text-center">{{ $customer->nama }}</td>
              <td class="text-center">{{ $customer->full_alamat }}</td>
              <td class="text-center">{{ $customer->limit_pembelian }}</td>
              <td class="text-center">{{ $customer->pengajuan_limit_pembelian }}</td>
              <td class="text-center">
                <a href="/supervisor/datacustomer/pengajuan/{{ $customer->id }}" class="btn btn-primary">Detail</a>
              </td>
            </tr>
          @endforeach
        @else
          <p class="text-danger tecxt-center">Tidak ada data pengajuan limit pembelian</p>
        @endif
      </tbody>
    </table>
  </div>
@endsection

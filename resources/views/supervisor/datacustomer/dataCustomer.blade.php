@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/supervisor.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Customer</li>
  </ol>
@endsection

@section('main_content')
  <div class="px-5 pt-4">
    @if (session()->has('pesanSukses'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('pesanSukses') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    <div class="d-flex align-items-center justify-content-between">
      <form method="GET" action="/supervisor/datacustomer/cari">
        <div class="input-group">
          <input type="text" class="form-control" name="cari" placeholder="Cari Customer..."
            value="{{ request('cari') }}">
          <button type="submit" class="btn btn-primary">Cari</button>
        </div>
      </form>

      <a href="/supervisor/datacustomer/pengajuan" class="btn btn-primary"><span class="iconify fs-5 me-1"
          data-icon="carbon:data-view-alt"></span>Pengajuan Limit Pembelian</a>
    </div>


    <div class="table-responsive mt-4">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Foto<br>Tempat Usaha</th>
            <th scope="col" class="text-center">Nama</th>
            <th scope="col" class="text-center">Email</th>
            <th scope="col" class="text-center">Alamat Lengkap</th>
            <th scope="col" class="text-center">Telepon</th>
            <th scope="col" class="text-center">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($customers as $customer)
            <tr>
              <th scope="row" class="text-center">
                {{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</th>
              <td class="text-center">
                @if ($customer->foto != null)
                  <img src="{{ asset('storage/customer/' . $customer->foto) }}" class="img-fluid" width="40">
                @else
                  <img src="{{ asset('images/default_fototoko.png') }}" class="img-fluid" width="40">
                @endif
              </td>
              <td>{{ $customer->nama }}</td>
              <td>{{ $customer->email }}</td>
              <td>{{ $customer->full_alamat }}</td>
              <td>{{ $customer->telepon }}</td>
              <td class="text-capitalize text-center">{{ $customer->linkStatus->nama }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      {{ $customers->links() }}
    </div>
  </div>
@endsection

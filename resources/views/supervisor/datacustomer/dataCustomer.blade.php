@extends('layouts/main')
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Data Customer</li>
</ol>
@endsection
@section('main_content')
  @if (session()->has('pesanSukses'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('pesanSukses') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <a href="/supervisor/datapengajuan" class="btn btn-primary">Pengajuan Limit Pembelian</a>

  <div class="row">
    <div class="col-5">
      <div class="mt-3 search-box">
        <form method="GET" action="/supervisor/datacustomer/cari">
          <div class="input-group">
            <input type="text" class="form-control" name="cari" placeholder="Cari Customer..."
              value="{{ request('cari') }}">
            <button type="submit" class="btn btn-primary">Cari</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="table-responsive mt-3">
    <table class="table table-hover table-sm">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col" class="text-center">Nama</th>
          <th scope="col" class="text-center">Alamat Lengkap</th>
          <th scope="col" class="text-center">Email</th>
          <th scope="col" class="text-center">Telepon</th>
          <th scope="col" class="text-center">Tipe Retur</th>
          <th scope="col" class="text-center">Limit Pembelian</th>
          {{-- <th scope="col" class="text-center">Pengajuan Limit Pembelian</th>
          <th scope="col" class="text-center">Status limit</th> --}}
          <th scope="col" class="text-center">Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($customers as $customer)
          <tr>
            <th scope="row">{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</th>
            <td>{{ $customer->nama }}</td>
            <td>{{ $customer->full_alamat }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->telepon }}</td>
            <td>{{ $customer->linkReturType->nama ?? null }}</td>
            <td>{{ $customer->limit_pembelian }}</td>
            {{-- <td>{{ $customer->pengajuan_limit_pembelian }}</td>
            <td>{{ $customer->linkStatusLimit->nama ?? null }}</td> --}}
            <td>{{ $customer->linkStatus->nama }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    {{ $customers->links() }}
  </div>
@endsection

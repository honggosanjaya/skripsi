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
    @if (session()->has('successMessage'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('successMessage') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    <a href="/supervisor/datacustomer/pengajuan" class="btn btn-primary"><span class="iconify fs-5 me-1"
        data-icon="carbon:data-view-alt"></span>Pengajuan Limit Pembelian</a>

    <div class="table-responsive">
      <table class="table table-hover table-sm" id="table">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
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
              <th scope="row" class="text-center">{{ $loop->iteration }}</th>
              <td>
                <a href="/supervisor/datacustomer/{{ $customer->id ?? null }}"
                  class="text-decoration-none">{{ $customer->nama ?? null }}</a>
              </td>
              <td>{{ $customer->email ?? null }}</td>
              <td>{{ $customer->full_alamat ?? null }}</td>
              <td>{{ $customer->telepon ?? null }}</td>
              @if ($customer->status_enum != null)
                <td>{{ $customer->status_enum == '1' ? 'Active' : ($customer->status_enum == '0' ? 'Hide' : 'Inactive') }}
                </td>
              @endif
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

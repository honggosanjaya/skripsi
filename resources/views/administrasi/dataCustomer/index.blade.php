@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Customer</li>
  </ol>
@endsection
@section('main_content')
  @if (session()->has('successMessage'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('successMessage') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif


  <div class="px-5">
    <a href="/administrasi/datacustomer/create" class="btn btn-primary btn_add-relative">
      <span class="iconify fs-3 me-2" data-icon="dashicons:database-add"></span> Tambah Customer
    </a>

    <div class="table-responsive mt-3">
      <table class="table table-hover table-sm mt-4" id="table">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Nama</th>
            <th scope="col" class="text-center">Alamat Lengkap</th>
            <th scope="col" class="text-center">Telepon</th>
            <th scope="col" class="text-center">Penetapan Harga</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($customers as $customer)
            <tr>
              <th scope="row">{{ $loop->iteration }}</th>
              <td>
                <a href="/administrasi/datacustomer/{{ $customer->id }}"
                  class="text-decoration-none">{{ $customer->nama ?? null }}</a>
              </td>
              <td>{{ $customer->full_alamat ?? null }}</td>
              <td>{{ $customer->telepon ?? null }}</td>
              <td>harga {{ $customer->tipe_harga ?? null }}</td>
              @if ($customer->status_enum != null)
                <td>{{ $customer->status_enum == '1' ? 'Active' : ($customer->status_enum == '0' ? 'Hide' : 'Inactive') }}
                </td>
              @else
                <td></td>
              @endif
              <td width="20%" class="text-center">
                <a href="/administrasi/datacustomer/ubah/{{ $customer->id }}" class="btn btn-warning">
                  <span class="iconify fs-5 me-1" data-icon="eva:edit-2-fill"></span> Edit
                </a>
                <a href="/administrasi/datacustomer/{{ $customer->id }}/generate-qr" class="btn btn-primary">
                  <span class="iconify fs-5 me-1" data-icon="bx:qr-scan"></span> QR
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

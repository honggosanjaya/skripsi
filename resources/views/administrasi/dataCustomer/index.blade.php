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
  @if (session()->has('pesanSukses'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('pesanSukses') }}
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
            <th scope="col" class="text-center">Status</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($customers as $customer)
            <tr onclick="window.location='/administrasi/datacustomer/{{ $customer->id }}';">
              <th scope="row">{{ $loop->iteration }}</th>
              <td>{{ $customer->nama }}</td>
              <td>{{ $customer->full_alamat }}</td>
              <td>{{ $customer->telepon }}</td>
              <td>{{ $customer->linkStatus->nama }}</td>
              <td>
                <div class="d-flex justify-content-center">
                  <a href="/administrasi/datacustomer/ubah/{{ $customer->id }}" class="btn btn-sm btn-warning me-3">
                    <span class="iconify fs-5 me-1" data-icon="eva:edit-2-fill"></span> Edit
                  </a>

                  {{-- <form action="/administrasi/datacustomer/ubahstatus/{{ $customer->id }}" method="POST">
                    @csrf
                    <button type="submit"
                      class="btn btn-sm {{ $customer->linkStatus->nama === 'active' ? 'btn-danger' : 'btn-success' }}">
                      @if ($customer->linkStatus->nama === 'active')
                        <span class="iconify fs-5 me-1" data-icon="material-symbols:cancel-outline"></span>
                      @else
                        <span class="iconify fs-5 me-1" data-icon="akar-icons:double-check"></span>
                      @endif
                      {{ $customer->linkStatus->nama === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                  </form> --}}
                </div>
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

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Retur</li>
  </ol>
@endsection

@section('main_content')
  <div class="px-5 pt-4">
    <table class="table table-hover table-sm mt-4" id="table">
      <thead>
        <tr>
          <th scope="col" class="text-center">Nomor <br> Retur</th>
          <th scope="col" class="text-center">Tanggal Retur</th>
          <th scope="col" class="text-center">Nama Customer</th>
          <th scope="col" class="text-center">Alamat</th>
          <th scope="col" class="text-center">Pengirim</th>
          <th scope="col" class="text-center">Status Retur</th>
          <th scope="col" class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($returs as $retur)
          <tr>
            <th scope="row" class="text-center">{{ $retur->no_retur }}</th>
            <td>{{ date('d-m-Y', strtotime($retur->created_at)) }}</td>
            <td>{{ $retur->linkCustomer->nama }}</td>
            <td>{{ $retur->linkCustomer->alamat_utama . ' ' . $retur->linkCustomer->alamat_nomor }}</td>
            <td>{{ $retur->linkStaffPengaju->nama }}</td>
            <td class="text-capitalize">{{ $retur->linkStatus->nama }}</td>
            <td>
              <a href="/administrasi/retur/{{ $retur->no_retur }}" class="btn btn-primary"><span
                  class="iconify fs-4 me-1" data-icon="fluent:apps-list-detail-24-filled"></span>Detail</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    @push('JS')
      <script src="{{ mix('js/administrasi.js') }}"></script>
    @endpush
  </div>
@endsection

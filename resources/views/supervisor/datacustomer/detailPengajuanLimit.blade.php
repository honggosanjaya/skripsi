@extends('layouts/main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/datacustomer">Data Customer</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/datacustomer/pengajuan">Pengajuan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
@endsection
@section('main_content')
  <div class="row pt-4">
    <div class="col-7 offset-1">
      <div class="informasi-list">
        <span><b>Nama Customer</b>{{ $customer->nama }}</span>
        <span><b>Jenis Customer</b>{{ $customer->linkCustomerType->nama }}</span>
        <span><b>Wilayah</b>{{ $customer->linkDistrict->nama }}</span>
        <span><b>Email</b>{{ $customer->email ? $customer->email : 'tidak ada data' }}</span>
        <span><b>Alamat</b>{{ $customer->full_alamat }}</span>
        <span><b>Telepon</b>{{ $customer->telepon ? $customer->telepon : 'tidak ada data' }}</span>
        <span><b>Durasi Kunjungan</b>{{ $customer->durasi_kunjungan . ' hari' }}</span>
        <span><b>Counter Effective Call</b>{{ $customer->counter_to_effective_call }}</span>
        <span><b>Limit Pembelian</b>{{ $customer->limit_pembelian }}</span>
        <span><b>Pengajuan Limit Pembelian</b>{{ $customer->pengajuan_limit_pembelian }}</span>
        <span><b>Status Limit Pembelian</b>{{ $customer->status_limit_pembelian }}</span>
        <span><b>Status</b>{{ $customer->linkStatus->nama }}</span>
      </div>

      <div class="d-flex justify-content-between">
        <p class="fw-500 mb-0">Foto</p>
        @if ($customer->foto)
          <img src="{{ asset('storage/customer/' . $customer->foto) }}" class="img-preview img-fluid d-block py-5">
        @else
          <p>tidak ada data</p>
        @endif
      </div>
    </div>


    <form action="/supervisor/datacustomer/pengajuan/setuju/{{ $customer->id }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-sm btn-success">
        Setuju
      </button>
    </form>

    <form action="/supervisor/datacustomer/pengajuan/tolak/{{ $customer->id }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-sm btn-danger">
        Tolak
      </button>
    </form>

  </div>
@endsection

@extends('layouts/main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/datacustomer">Data Customer</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <h3 class="mb-5">Detail Customer</h3>
    <div class="informasi-list mb_big">
      <span><b>Nama Customer</b>{{ $customer->nama }}</span>
      <span><b>Jenis Customer</b>{{ $customer->linkCustomerType->nama }}</span>
      <span><b>Wilayah</b>{{ $customer->linkDistrict->nama }}</span>
      <span><b>Email</b>{{ $customer->email ? $customer->email : 'tidak ada data' }}</span>
      <span><b>Alamat</b>{{ $customer->full_alamat }}</span>
      <span><b>Koordinat</b> {{ $customer->koordinat ?? null }}</span>
      <span><b>Telepon</b>{{ $customer->telepon ? $customer->telepon : 'tidak ada data' }}</span>
      <span><b>Durasi Kunjungan</b>{{ $customer->durasi_kunjungan . ' hari' }}</span>
      <span><b>Counter Effective Call</b>{{ $customer->counter_to_effective_call }}</span>
      <span><b>Tipe Retur</b> {{ $customer->tipe_retur ?? null }}</span>
      <span><b>Limit Pembelian</b>{{ $customer->limit_pembelian }}</span>
      <span><b>Pengajuan Limit Pembelian</b>{{ $customer->pengajuan_limit_pembelian }}</span>
      <span><b>Status Limit
          Pembelian</b>{{ $customer->status_limit_pembelian_enum == '1' ? 'Disetujui' : ($customer->status_limit_pembelian_enum == -1 ? 'Tidak Disetujui' : 'Diajukan') }}</span>
      <span><b>Status</b>{{ $customer->status_enum == '1' ? 'Active' : 'Inactive' }}</span>
      <span><b>Foto Tempat Usaha</b>
        @if ($customer->foto)
          <img src="{{ asset('storage/customer/' . $customer->foto) }}" class="img-preview img-fluid">
        @else
          Tidak ada foto
        @endif
      </span>
    </div>
  </div>
@endsection

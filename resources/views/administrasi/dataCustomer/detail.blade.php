@extends('layouts/main')

@section('main_content')
  <div class="row pt-4">
    <div class="col-7 offset-1">
      <div class="informasi-list">
        <span><b>Nama Customer</b> {{ $customer->nama ?? null }}</span>
        <span><b>Jenis Customer</b> {{ $customer->linkCustomerType->nama ?? null }}</span>
        <span><b>Wilayah</b> {{ $customer->linkDistrict->nama ?? null }}</span>
        <span><b>Wilayah</b> {{ $customer->linkDistrict->nama ?? null }}</span>
        <span><b>Ditambahkan oleh</b> {{ $customer->linkStaff->nama ?? null }}</span>
        <span><b>Email</b> {{ $customer->email ? $customer->email : 'tidak ada data' }}</span>
        <span><b>Alamat Utama</b> {{ $customer->alamat_utama ?? null }}</span>
        <span><b>Alamat Nomor</b> {{ $customer->alamat_nomor ? $customer->alamat_nomor : 'tidak ada data' }}</span>
        <span><b>Keterangan Alamat</b>
          {{ $customer->keterangan_alamat ? $customer->keterangan_alamat : 'tidak ada data' }}</span>
        <span><b>Koordinat</b> {{ $customer->koordinat ? $customer->koordinat : 'tidak ada data' }}</span>
        <span><b>Telepon</b> {{ $customer->telepon ? $customer->telepon : 'tidak ada data' }}</span>
        <span><b>Durasi Kunjungan</b> {{ $customer->durasi_kunjungan . ' hari' }}</span>
        <span><b>Counter Effective Call</b>
          {{ $customer->counter_to_effective_call ? $customer->counter_to_effective_call : 'tidak ada data' }}</span>
        <span><b>Tipe Retur</b> {{ $customer->tipe_retur ? $customer->tipe_retur : 'tidak ada data' }}</span>
        <span><b>Limit Pembelian</b>
          {{ $customer->limit_pembelian ? $customer->limit_pembelian : 'tidak ada data' }}</span>
        <span><b>Pengajuan Limit Pembelian</b>
          {{ $customer->pengajuan_limit_pembelian ? $customer->pengajuan_limit_pembelian : 'tidak ada data' }}</span>
        <span><b>Status Limit Pembelian</b>
          {{ $customer->status_limit_pembelian ? $customer->linkStatusLimit->nama : 'tidak ada data' }}</span>
        @if ($customer->foto)
          <span><b>Foto</b></span>
          <img src="{{ asset('storage/customer/' . $customer->foto) }}" class="img-preview img-fluid d-block py-5">
        @else
          <span><b>Foto</b> 'tidak ada data'</span>
        @endif
        <span><b>Status</b> {{ $customer->linkStatus->nama ?? null }}</span>
      </div>
    </div>
  </div>
@endsection

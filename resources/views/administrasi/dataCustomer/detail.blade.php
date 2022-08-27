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
        <span><b>Email</b> {{ $customer->email ?? null }}</span>
        <span><b>Alamat Utama</b> {{ $customer->alamat_utama ?? null }}</span>
        <span><b>Alamat Nomor</b> {{ $customer->alamat_nomor ?? null }}</span>
        <span><b>Keterangan Alamat</b>
          {{ $customer->keterangan_alamat ?? null }}</span>
        <span><b>Koordinat</b> {{ $customer->koordinat ?? null }}</span>
        <span><b>Telepon</b> {{ $customer->telepon ?? null }}</span>
        <span><b>Durasi Kunjungan</b> {{ $customer->durasi_kunjungan . ' hari' }}</span>
        <span><b>Counter Effective Call</b>
          {{ $customer->counter_to_effective_call ?? null }}</span>
        <span><b>Tipe Retur</b> {{ $customer->tipe_retur ?? null }}</span>
        <span><b>Limit Pembelian</b>
          {{ $customer->limit_pembelian ?? null }}</span>

        <span><b>Pengajuan Limit Pembelian</b>
          @if ($old_data)
            {{ $old_data['pengajuan_limit_pembelian'] }}
          @else
            {{ $customer->pengajuan_limit_pembelian ?? null }}
        </span>
        @endif

        <span><b>Status Limit Pembelian</b>
          @if ($old_data)
            {{ $old_data['status_limit_pembelian_enum'] == 1
                ? 'Disetujui'
                : ($old_data['status_limit_pembelian_enum'] == -1
                    ? 'Tidak Disetujui'
                    : 'Diajukan') }}
          @else
            @if ($customer->status_limit_pembelian_enum != null)
              {{ $customer->status_limit_pembelian_enum == 1
                  ? 'Disetujui'
                  : ($customer->status_limit_pembelian_enum == -1
                      ? 'Tidak Disetujui'
                      : 'Diajukan') }}
            @endif
        </span>
        @endif

        @if ($customer->foto)
          <span><b>Foto Tempat Usaha</b></span>
          <img src="{{ asset('storage/customer/' . $customer->foto) }}" class="img-preview img-fluid d-block py-5">
        @else
          <span><b>Foto Tempat Usaha</b> </span>
        @endif

        @if ($customer->status_enum)
          <span><b>Status</b> {{ $customer->status_enum == 1 ? 'Active' : 'Inactive' }}</span>
        @else
          <span><b>Status</b> </span>
        @endif
      </div>
    </div>
  </div>
@endsection

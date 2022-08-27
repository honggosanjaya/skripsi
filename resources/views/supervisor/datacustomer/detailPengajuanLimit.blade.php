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
  <div class="pt-4 px-5">
    <div class="informasi-list mb_big">
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
      <span><b>Status Limit
          Pembelian</b>{{ $customer->status_limit_pembelian_enum == 1 ? 'Disetujui' : ($customer->status_limit_pembelian_enum == -1 ? 'Tidak Disetujui' : 'Diajukan') }}</span>
      <span><b>Status</b>{{ $customer->status_enum == 1 ? 'Active' : 'Inactive' }}</span>
      <span><b>Foto Tempat Usaha</b>
        @if ($customer->foto)
          <img src="{{ asset('storage/customer/' . $customer->foto) }}" class="img-preview img-fluid">
        @else
          Tidak ada foto
        @endif
      </span>
    </div>

    <div class="row justify-content-end mt-4">
      <div class="col d-flex justify-content-end">
        <form action="/supervisor/datacustomer/pengajuan/tolak/{{ $customer->id }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-sm btn-danger me-3">
            <span class="iconify fs-5 me-1" data-icon="emojione-monotone:heavy-multiplication-x"></span> Tolak
          </button>
        </form>

        <form action="/supervisor/datacustomer/pengajuan/setuju/{{ $customer->id }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-sm btn-success">
            <span class="iconify fs-5 me-1" data-icon="akar-icons:check"></span> Setuju
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection

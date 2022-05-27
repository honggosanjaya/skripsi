@extends('layouts/main')

@section('main_content')
  <div class="row pt-4">
    <div class="col-5 offset-1">
      <div class="d-flex justify-content-between">
        <p>Nama Customer:</p>
        <p>{{ $customer->nama }}</p>
      </div>

      <div class="d-flex justify-content-between">
        <p>Jenis Customer:</p>
        <p>{{ $customer->linkCustomerType->nama }}</p>
      </div>

      <div class="d-flex justify-content-between">
        <p>Wilayah:</p>
        <p>{{ $customer->linkDistrict->nama }}</p>
      </div>

      <div class="d-flex justify-content-between">
        <p>Email:</p>
        <p>{{ $customer->email ? $customer->email : 'tidak ada data' }}</p>
      </div>

      <div class="d-flex justify-content-between">
        <p>Alamat Utama:</p>
        <p>{{ $customer->alamat_utama }}</p>
      </div>

      <div class="d-flex justify-content-between">
        <p>Alamat Nomor:</p>
        <p>{{ $customer->alamat_nomor ? $customer->alamat_nomor : 'tidak ada data' }}</p>
      </div>

      <div class="d-flex justify-content-between">
        <p>Telepon:</p>
        <p>{{ $customer->telepon ? $customer->telepon : 'tidak ada data' }}</p>
      </div>

      <div class="d-flex justify-content-between">
        <p>Durasi Kunjungan:</p>
        <p>{{ $customer->durasi_kunjungan . ' hari' }}</p>
      </div>

      <div class="d-flex justify-content-between">
        <p>Counter Effective Call:</p>
        <p>{{ $customer->counter_to_effective_call }}</p>
      </div>

      <div class="d-flex justify-content-between">
        <p>Limit Pembelian:</p>
        <p>{{ $customer->limit_pembelian }}</p>
      </div>

      <div class="d-flex justify-content-between">
        <p>Pengajuan Limit Pembelian:</p>
        <p>{{ $customer->pengajuan_limit_pembelian }}</p>
      </div>

      <div class="d-flex justify-content-between">
        <p>Status Limit Pembelian:</p>
        <p>{{ $customer->status_limit_pembelian }}</p>
      </div>

      <div class="d-flex justify-content-between">
        <p>Foto:</p>
        @if ($customer->foto)
          <img src="{{ asset('storage/customer/' . $customer->foto) }}" class="img-preview img-fluid d-block py-5">
        @else
          <p>tidak ada data</p>
        @endif
      </div>

      <div class="d-flex justify-content-between">
        <p>Status:</p>
        <p>{{ $customer->linkStatus->nama }}</p>
      </div>
    </div>

    <form action="/supervisor/datapengajuan/setuju/{{ $customer->id }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-sm btn-success">
        Setuju
      </button>
    </form>

    <form action="/supervisor/datapengajuan/tolak/{{ $customer->id }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-sm btn-danger">
        Tolak
      </button>
    </form>
  </div>
@endsection
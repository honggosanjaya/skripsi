@extends('layouts/main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admnistrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/admnistrasi/reimbusement">Reimbursement</a></li>
    <li class="breadcrumb-item"><a href="/admnistrasi/reimbusement/pengajuan">Pengajuan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <div class="informasi-list mb_big">
      <span><b>Nama Pengaju</b>{{ $reimbursement->linkStaffPengaju->nama }}</span>
      <span><b>Nama Pengonfirmasi</b>{{ $reimbursement->linkStaffPengonfirmas->nama ?? null }}</span>
      <span><b>Kegunaan</b>{{ $reimbursement->linkCashAccount->nama }}</span>
      <span><b>Jumlah</b>{{ $reimbursement->jumlah_uang }}</span>
      <span><b>Keterangan Pengajuan</b>{{ $reimbursement->keterangan_pengajuan }}</span>
      <span><b>Keterangan Konfirmasi</b>{{ $reimbursement->keterangan_konfirmasi ?? null }}</span>
      <span><b>Status</b>{{ $reimbursement->linkStatus->nama }}</span>
      <span><b>Foto Bukti</b>
        <img src="{{ asset('storage/reimbursement/' . $reimbursement->foto) }}" class="img-preview img-fluid">
      </span>
    </div>

    <div class="row justify-content-end mt-4">
      <div class="col d-flex justify-content-end">
        <form action="/administrasi/reimbursement/pengajuan/tolak/{{ $reimbursement->id }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-sm btn-danger me-3">
            <span class="iconify fs-5 me-1" data-icon="emojione-monotone:heavy-multiplication-x"></span> Tolak
          </button>
        </form>

        <form action="/administrasi/reimbursement/pengajuan/setuju/{{ $reimbursement->id }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-sm btn-success">
            <span class="iconify fs-5 me-1" data-icon="akar-icons:check"></span> Setuju
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection

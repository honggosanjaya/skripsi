@extends('layouts/main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/reimbursement">Reimbursement</a></li>
    @if ($reimbursement->status_enum == '0')
      <li class="breadcrumb-item"><a href="/administrasi/reimbursement/pengajuan">Pengajuan</a></li>
    @else
      <li class="breadcrumb-item"><a href="/administrasi/reimbursement/pembayaran">Pembayaran</a></li>
    @endif
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <div class="informasi-list mb_big">
      <span><b>Nama Pengaju</b>{{ $reimbursement->linkStaffPengaju->nama ?? null }}</span>
      <span><b>Nama Pengonfirmasi</b>{{ $reimbursement->linkStaffPengonfirmas->nama ?? null }}</span>
      <span><b>Kegunaan</b>{{ $reimbursement->linkCashAccount->nama ?? null }}</span>
      <span><b>Jumlah</b>{{ $reimbursement->jumlah_uang ?? null }}</span>
      <span><b>Keterangan Pengajuan</b>{{ $reimbursement->keterangan_pengajuan ?? null }}</span>
      <span><b>Keterangan Konfirmasi</b>{{ $reimbursement->keterangan_konfirmasi ?? null }}</span>
      @if ($reimbursement->status_enum ?? null)
        @if ($reimbursement->status_enum == '0')
          <span><b>Status</b>Diajukan</span>
        @elseif ($reimbursement->status_enum == '1')
          <span><b>Status</b>Diproses</span>
        @elseif ($reimbursement->status_enum == '2')
          <span><b>Status</b>Dibayar</span>
        @elseif ($reimbursement->status_enum == '-1')
          <span><b>Status</b>Ditolak</span>
        @endif
      @endif

      @if ($reimbursement->foto)
        <span><b>Foto Bukti</b>
          <img src="{{ asset('storage/reimbursement/' . $reimbursement->foto) }}" class="img-preview img-fluid">
        </span>
      @endif
    </div>

    <div class="row justify-content-end mt-4">
      @if ($reimbursement->status_enum ?? null)
        <div class="col d-flex justify-content-end">
          @if ($reimbursement->status_enum == '0')
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
          @elseif ($reimbursement->status_enum == '1')
            <form action="/administrasi/reimbursement/pengajuan/dibayar/{{ $reimbursement->id }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-sm btn-success">
                <span class="iconify fs-5 me-1" data-icon="akar-icons:check"></span> Dibayarkan
              </button>
            </form>
          @endif
        </div>
      @endif
    </div>
  </div>
@endsection

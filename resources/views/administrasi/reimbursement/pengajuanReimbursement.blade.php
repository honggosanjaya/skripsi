@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/reimbursement">Reimbursement</a></li>
    @if ($type == 'pengajuan')
      <li class="breadcrumb-item active" aria-current="page">Pengajuan</li>
    @elseif($type == 'pembayaran')
      <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
    @endif

  </ol>
@endsection

@section('main_content')
  <div class="px-5">
    <div class="table-responsive mt-3">
      <table class="table table-hover table-sm" id="table">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Pengaju</th>
            <th scope="col" class="text-center">Pengonfirmasi</th>
            <th scope="col" class="text-center">Cash Account</th>
            <th scope="col" class="text-center">Jumlah (Rp)</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($reimbursements as $reimbursement)
            <tr>
              <th scope="row" class="text-center">
                {{ $loop->iteration }}
              </th>
              <td>{{ $reimbursement->linkStaffPengaju->nama ?? null }}</td>
              <td>{{ $reimbursement->linkStaffPengonfirmasi->nama ?? null }}</td>
              <td>{{ $reimbursement->linkCashAccount->nama ?? null }}</td>
              <td>{{ $reimbursement->jumlah_uang ?? null }}</td>
              @if ($reimbursement->status_enum != null)
                <td class="text-capitalize text-center">
                  <p class="mb-0 badge badge-reimbursement-{{ $reimbursement->status_enum }}">
                    @if ($reimbursement->status_enum == '0')
                      {{ 'Diajukan' }}
                    @elseif ($reimbursement->status_enum == '1')
                      {{ 'Diproses' }}
                    @elseif ($reimbursement->status_enum == '2')
                      {{ 'Dibayar' }}
                    @elseif ($reimbursement->status_enum == '-1')
                      {{ 'Ditolak' }}
                    @endif
                  </p>
                </td>
              @endif
              <td class="text-center">
                <a href="/administrasi/reimbursement/pengajuan/{{ $reimbursement->id ?? null }}"
                  class="btn btn-primary"><span class="iconify fs-4 me-1"
                    data-icon="fluent:apps-list-detail-24-filled"></span>Detail</a>
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

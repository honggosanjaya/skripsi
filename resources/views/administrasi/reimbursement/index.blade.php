@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Reimbursement</li>
  </ol>
@endsection

@section('main_content')
  @if (session()->has('successMessage'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('successMessage') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <div class="px-5">
    <a href="/administrasi/reimbursement/pengajuan" class="btn btn-primary btn_add-relative"><span
        class="iconify fs-5 me-1" data-icon="carbon:data-view-alt"></span>Pengajuan
    </a>

    <a href="/administrasi/reimbursement/pembayaran" class="btn btn-success btn_add-relative"><span
        class="iconify fs-5 me-1" data-icon="carbon:data-view-alt"></span>Pembayaran
    </a>

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
              <td>{{ number_format($reimbursement->jumlah_uang ?? 0, 0, '', '.') }}</td>
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

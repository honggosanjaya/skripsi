@extends('layouts.mainmobile')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard de</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/reimbursement">Reimbursement</a></li>
    @if ($type == 'pengajuan')
      <li class="breadcrumb-item active" aria-current="page">Pengajuan</li>
    @elseif($type == 'pembayaran')
      <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
    @endif
  </ol>
@endsection

@section('main_content')
  <div class="container pt-4">
    @if (count($reimbursements) == 0)
      <small class="text-danger text-center d-block mt-5">Tidak ada data</small>
    @endif
    @foreach ($reimbursements as $reimbursement)
      <div class="list-mobile mt-4">
        <div class="d-flex justify-content-between align-items-start mb-4">
          <h1 class="fs-5 mb-0 title">{{ $reimbursement->linkStaffPengaju->nama ?? null }}</h1>
          @if ($reimbursement->status_enum != null)
            <p class="mb-0 badge badge-reimbursement-{{ $reimbursement->status_enum }} text-center text-capitalize">
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
          @endif
        </div>

        <div class="row">
          <div class="col">
            <div class="flex-item">
              <p><small>Cash Account</small><br><strong>{{ $reimbursement->linkCashAccount->nama ?? null }}</strong></p>
            </div>
          </div>
          <div class="col">
            <div class="flex-item">
              <p><small>Uang</small><br><strong>Rp {{ number_format($reimbursement->jumlah_uang, 0, '', '.') }}</strong>
              </p>
            </div>
          </div>
          @if ($reimbursement->linkStaffPengonfirmasi->nama ?? null)
            <div class="col">
              <div class="flex-item">
                <p>
                  <small>Pengonfirmasi</small><br><strong>{{ $reimbursement->linkStaffPengonfirmasi->nama ?? null }}</strong>
                </p>
              </div>
            </div>
          @endif
        </div>

        <a href="/administrasi/reimbursement/pengajuan/{{ $reimbursement->id ?? null }}"
          class="btn btn-purple-gradient w-100"><span class="iconify fs-4 me-1"
            data-icon="fluent:apps-list-detail-24-filled"></span>Detail</a>
      </div>
    @endforeach

    <div class="mt-5 d-flex justify-content-center">
      {{ $reimbursements->appends(request()->except('page'))->links() }}
    </div>
  </div>
  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

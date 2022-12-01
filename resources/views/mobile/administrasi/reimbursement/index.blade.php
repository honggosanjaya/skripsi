@extends('layouts.mainmobile')
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

  <div class="container pt-4">
    <div class="row justify-content-end mb-4">
      <div class="col d-flex justify-content-end">
        <a href="/administrasi/reimbursement/pengajuan" class="btn btn-primary me-3"><span class="iconify fs-5 me-1"
            data-icon="carbon:data-view-alt"></span>Pengajuan
        </a>
        <a href="/administrasi/reimbursement/pembayaran" class="btn btn-success"><span class="iconify fs-5 me-1"
            data-icon="carbon:data-view-alt"></span>Pembayaran
        </a>
      </div>
    </div>

    @foreach ($reimbursements as $reimbursement)
      <div class="list-mobile">
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

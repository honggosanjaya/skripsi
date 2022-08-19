@extends('layouts/main')

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/reimbursement">Reimbursement</a></li>
    <li class="breadcrumb-item active" aria-current="page">Pengajuan</li>
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
          @if (sizeof($reimbursements) > 0)
            @foreach ($reimbursements as $reimbursement)
              <tr>
                <th scope="row" class="text-center">
                  {{ ($reimbursements->currentPage() - 1) * $reimbursements->perPage() + $loop->iteration }}
                </th>
                <td>{{ $reimbursement->linkStaffPengaju->nama }}</td>
                <td>{{ $reimbursement->linkStaffPengonfirmasi->nama ?? null }}</td>
                <td>{{ $reimbursement->linkCashAccount->nama }}</td>
                <td>{{ $reimbursement->jumlah_uang }}</td>
                <td class="text-capitalize text-center">{{ $reimbursement->linkStatus->nama }}</td>
                <td class="text-center">
                  <a href="/administrasi/reimbursement/pengajuan/{{ $reimbursement->id }}" class="btn btn-primary"><span
                      class="iconify fs-4 me-1" data-icon="fluent:apps-list-detail-24-filled"></span>Detail</a>
                </td>
              </tr>
            @endforeach
          @else
            <p class="text-danger tecxt-center">Tidak ada data pengajuan reimbursement</p>
          @endif
        </tbody>
      </table>

      {{ $reimbursements->links() }}
    </div>
  </div>
  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

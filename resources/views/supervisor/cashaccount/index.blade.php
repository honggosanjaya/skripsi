@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/supervisor.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Cash Account</li>
  </ol>
@endsection

@section('main_content')
  <div class="px-5 pt-4">
    @if (session()->has('addCashAccountSuccess'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
          {{ session('addCashAccountSuccess') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    @endif
    @if (session()->has('updateCashAccountSuccess'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
          {{ session('updateCashAccountSuccess') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
      <form method="GET" action="/supervisor/cashaccount/cari">
        <div class="input-group">
          <input type="text" class="form-control" name="cari" placeholder="Cari Cash Account..."
            value="{{ request('cari') }}">
          <button type="submit" class="btn btn-primary">
            <span class="iconify" data-icon="fe:search"></span>
          </button>
        </div>
      </form>
      <a href="/supervisor/cashaccount/tambah" class="btn btn-primary my-3 py-2"><span class="iconify fs-4 me-1"
          data-icon="dashicons:database-add"></span>Tambah Cash Account</a>
    </div>

    <div class="table-responsive mt-4">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Nama Cash Account</th>
            <th scope="col" class="text-center">Keterangan</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cashaccounts as $cashaccount)
            <tr>
              <th scope="row" class="text-center">
                {{ ($cashaccounts->currentPage() - 1) * $cashaccounts->perPage() + $loop->iteration }}
              </th>
              <td>{{ $cashaccount->nama ?? null }}</td>
              <td>{{ $cashaccount->keterangan ?? null }}</td>
              <td class="text-center">
                <a href="/supervisor/cashaccount/ubah/{{ $cashaccount->id ?? null }}" class="btn btn-warning"><span
                    class="iconify fs-5 me-1" data-icon="eva:edit-2-fill"></span>
                  Ubah</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="d-flex flex-row mt-4">
      {{ $cashaccounts->links() }}
    </div>
  </div>
@endsection

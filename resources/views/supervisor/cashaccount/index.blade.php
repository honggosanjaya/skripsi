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
    @if (session()->has('successMessage'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
          {{ session('successMessage') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    @endif

    <a href="/supervisor/cashaccount/tambah" class="btn btn-primary"><span class="iconify fs-4 me-1"
        data-icon="dashicons:database-add"></span>Tambah Cash Account</a>

    <div class="table-responsive">
      <table class="table table-hover table-sm" id="table">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Nama Cash Account</th>
            <th scope="col" class="text-center">Keterangan</th>
            <th scope="col" class="text-center">Account</th>
            <th scope="col" class="text-center">Default</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cashaccounts as $cashaccount)
            <tr>
              <th scope="row" class="text-center">{{ $loop->iteration }}</th>
              <td>{{ $cashaccount->nama ?? null }}</td>
              <td>{{ $cashaccount->keterangan ?? null }}</td>
              <td>{{ $cashaccount->account ?? null }}</td>
              @if ($cashaccount->default ?? null)
                <td>
                  {{ $cashaccount->default == '1' ? 'pengadaan' : ($cashaccount->default == '2' ? 'penjualan' : 'parent') }}
                </td>
              @else
                <td></td>
              @endif
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
  </div>
@endsection

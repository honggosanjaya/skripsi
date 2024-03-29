@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/supervisor.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Target</li>
  </ol>
@endsection

@section('main_content')
  @if (session()->has('successMessage'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
        {{ session('successMessage') }}
        <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  @endif


  <div class="px-5 pt-4">
    <a href="/supervisor/target/tambah" class="btn btn-primary">
      <span class="iconify fs-4 me-1" data-icon="dashicons:database-add"></span>
      Tambah Target
    </a>

    <div class="table-responsive">
      <table class="table table-sm table-hover" id="table">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Jenis Target / Hari</th>
            <th scope="col" class="text-center">Nilai Target</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($targets as $target)
            <tr>
              <td class="text-center">{{ $loop->iteration }}</td>
              @if ($target->jenis_target ?? null)
                @if ($target->jenis_target == '1')
                  <td class="text-center">Target Omset</td>
                @elseif ($target->jenis_target == '2')
                  <td class="text-center">Target Tagihan</td>
                @elseif ($target->jenis_target == '3')
                  <td class="text-center">Target Kunjungan</td>
                @elseif ($target->jenis_target == '4')
                  <td class="text-center">Target Effective Call</td>
                @endif
              @else
                <td></td>
              @endif

              @if ($target->jenis_target ?? null)
                @if ($target->jenis_target == '1' || $target->jenis_target == '2')
                  <td class="text-center">Rp. {{ number_format($target->value ?? 0, 0, '', '.') }}</td>
                @elseif ($target->jenis_target == '3' || $target->jenis_target == '4')
                  <td class="text-center">{{ $target->value ?? null }}</td>
                @endif
              @else
                <td class="text-center">{{ $target->value ?? null }}</td>
              @endif
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <h1 class="fs-5">Nb:</h1>
    <p class="mb-0">1 bulan = 25 hari</p>
    <p>1 tahun = 300 hari</p>
  </div>
@endsection

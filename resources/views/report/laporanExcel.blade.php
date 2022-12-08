@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Laporan Excel</li>
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

  <div class="px-5 pt-4">
    <form action="" method="get">
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label class="form-label">Date Start</label>
            <input type="date" name="dateStart" class="form-control" value="{{ $input['dateStart'] ?? null }}"
              id="dateStart">
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label class="form-label">Date End</label>
            <input type="date" name="dateEnd" class="form-control" min="{{ $input['dateStart'] ?? null }}"
              value="{{ $input['dateEnd'] ?? null }}" id="dateEnd">
          </div>
        </div>
      </div>

      <button class="btn btn-success mt-3 download-report me-3" data-excel="penjualan-sales" type="button">
        <i class="bi bi-download px-1 me-1"></i>Laporan Aktivitas Penjualan
      </button>

      <button class="btn btn-success mt-3 download-report me-3" data-excel="penjualan-bersih" type="button">
        <i class="bi bi-download px-1 me-1"></i>Rekap Penjualan Bersih
      </button>

      @foreach ($bukuKas as $kas)
        <button class="btn btn-success mt-3 download-report me-3" data-excel="rincian-kas/{{ $kas->id }}"
          data-id="{{ $kas->id }}" type="button">
          <i class="bi bi-download px-1 me-1"></i>Rincian Kas {{ $kas->nama }}
        </button>
      @endforeach
    </form>
  </div>


  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
    <script>
      $(".download-report").click(function() {
        let data = $("form").serialize();
        const url = new URL(`https://example.com?${data}`);
        const params = new URLSearchParams(url.search);
        params.delete('_token');

        const data_id = $(this).data('id');
        if (data_id !== undefined) {
          params.append('id', data_id);
        }

        location.href = window.location.origin + "/administrasi/excel/" + $(this).data('excel') + "?" + params
          .toString()
      });
    </script>
  @endpush
@endsection

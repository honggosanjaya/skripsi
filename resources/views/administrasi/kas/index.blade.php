@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Kas</li>
  </ol>
@endsection

@section('main_content')
  @if (session()->has('pesanSukses'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('pesanSukses') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <div class="px-5 pt-4" id="sistem-kas">
    <a href="/administrasi/kas/create" class="btn btn-primary">
      <span class="iconify fs-3 me-2" data-icon="dashicons:database-add"></span> Tambah Kas
    </a>

    <div class="table-responsive mt-3">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Tgl</th>
            <th scope="col" class="text-center">Cash Account</th>
            <th scope="col" class="text-center">Nama</th>
            <th scope="col" class="text-center">Kontak</th>
            <th scope="col" class="text-center">Debet</th>
            <th scope="col" class="text-center">Kredit</th>
            <th scope="col" class="text-center">Saldo</th>
            <th scope="col" class="text-center">Keterangan 1</th>
            <th scope="col" class="text-center">Keterangan 2</th>
            <th scope="col" class="text-center">No. Bukti</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($listsofkas as $kas)
            <tr>
              <th scope="row" class="text-center">{{ $loop->iteration }}</th>
              <td>{{ date('d M Y', strtotime($kas['original']->tanggal ?? null)) }}</td>
              <td>{{ $kas['original']->linkCashAccount->nama ?? null }}</td>
              <td>{{ $kas['original']->linkStaff->nama ?? null }}</td>
              <td>{{ $kas['original']->kontak ?? null }}</td>

              @if ($kas['original']->debit_kredit != null)
                @if ($kas['original']->debit_kredit == '1')
                  <td class="text-end">{{ $kas['original']->uang ?? null }}</td>
                  <td class="text-end"></td>
                @elseif ($kas['original']->debit_kredit == '-1')
                  <td class="text-end"></td>
                  <td class="text-end">{{ $kas['original']->uang ?? null }}</td>
                @endif
              @else
                <td class="text-end"></td>
                <td class="text-end"></td>
              @endif

              <td class="text-end">{{ $kas['totalKas'] }}</td>
              <td>{{ $kas['original']->keterangan_1 ?? null }}</td>
              <td>{{ $kas['original']->keterangan_2 ?? null }}</td>
              <td>{{ $kas['original']->no_bukti ?? null }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

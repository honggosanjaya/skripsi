@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/supervisor.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Perubahan Kas</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    @if (session()->has('successMessage'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('successMessage') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    <h1 class="fs-4">Pengajuan Penghapusan Kas</h1>

    <div class="table-responsive">
      <table class="table table-hover table-sm" id="table">
        <thead>
          <tr>
            <th scope="col" class="text-center">Tanggal</th>
            <th scope="col" class="text-center">Cash Account</th>
            <th scope="col" class="text-center">Nama</th>
            <th scope="col" class="text-center">Kontak</th>
            <th scope="col" class="text-center">Keterangan 1</th>
            <th scope="col" class="text-center">Keterangan 2</th>
            <th scope="col" class="text-center">Debet</th>
            <th scope="col" class="text-center">Kredit</th>
            <th scope="col" class="text-center">No Bukti</th>
            <th scope="col" class="text-center">Kas</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pengajuansKas as $kas)
            <tr>
              <td data-order="{{ date('Y-m-d', strtotime($kas->tanggal ?? '-')) }}">
                {{ date('d M Y', strtotime($kas->tanggal ?? '-')) }}
              </td>
              <td>{{ $kas->linkCashAccount->nama ?? null }}</td>
              <td>{{ $kas->linkStaff->nama ?? null }}</td>
              <td>{{ $kas->kontak ?? null }}</td>
              <td>{{ $kas->keterangan_1 ?? null }}</td>
              <td>{{ $kas->keterangan_2 ?? null }}</td>
              @if ($kas->debit_kredit != null)
                @if ($kas->debit_kredit == '1')
                  <td class="text-end">{{ $kas->uang ?? null }}</td>
                  <td class="text-end"></td>
                @elseif ($kas->debit_kredit == '-1')
                  <td class="text-end"></td>
                  <td class="text-end">{{ $kas->uang ?? null }}</td>
                @endif
              @else
                <td class="text-end"></td>
                <td class="text-end"></td>
              @endif
              <td>{{ $kas->no_bukti ?? null }}</td>
              <td>{{ $kas->linkCashAccount->nama ?? null }}</td>
              <td>
                <form action="/supervisor/perubahankas/setuju/{{ $kas->id }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-success mb-2">
                    Terima
                  </button>
                </form>

                <form action="/supervisor/perubahankas/tolak/{{ $kas->id }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-danger">
                    Tolak
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

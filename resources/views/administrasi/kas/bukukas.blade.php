@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/kas">Kas</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
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

  <div class="px-5 pt-4" id="sistem-kas">
    <h1 class="fs-5">Buku Kas - {{ $title }}</h1>

    <a href="/administrasi/kas/create/{{ $idCashaccount }}" class="btn btn-primary mt-3 mb-5">
      <span class="iconify fs-3 me-2" data-icon="dashicons:database-add"></span> Tambah Kas
    </a>

    <a href="/administrasi/kas/print/{{ $idCashaccount }}" class="btn btn_purple mt-3 mb-5">
      <span class="iconify fs-3 me-2" data-icon="ic:round-print"></span> Cetak Kas
    </a>

    <div class="table-panel">
      <div class="table-responsive mt-3">
        <table class="table table-hover table-sm">
          <thead>
            <tr>
              <th scope="col" class="text-center">No</th>
              <th scope="col" class="text-center">Tgl</th>
              <th scope="col" class="text-center">Cash Account</th>
              <th scope="col" class="text-center">Nama</th>
              <th scope="col" class="text-center">Kontak</th>
              <th scope="col" class="text-center">Keterangan 1</th>
              <th scope="col" class="text-center">Keterangan 2</th>
              <th scope="col" class="text-center">Debet</th>
              <th scope="col" class="text-center">Kredit</th>
              <th scope="col" class="text-center">Saldo</th>
              <th scope="col" class="text-center">No. Bukti</th>
              <th scope="col" class="text-center">Pengajuan Penghapusan</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($listsofkas as $kas)
              <tr class="{{ $kas['original']->debit_kredit == '1' ? 'bg-debit' : 'bg-kredit' }}">
                <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                <td>{{ date('d M Y', strtotime($kas['original']->tanggal ?? null)) }}</td>
                <td>{{ $kas['original']->linkCashAccount->nama ?? null }}</td>
                <td>{{ $kas['original']->linkStaff->nama ?? null }}</td>
                <td>{{ $kas['original']->kontak ?? null }}</td>
                <td>{{ $kas['original']->keterangan_1 ?? null }}</td>
                <td>{{ $kas['original']->keterangan_2 ?? null }}</td>
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
                @if ($kas['original']->status == '-1')
                  <td class="text-end"></td>
                @else
                  <td class="text-end">{{ $kas['totalKas'] }}</td>
                @endif
                <td>{{ $kas['original']->no_bukti ?? null }}</td>
                <td class="text-center">
                  @if ($kas['original']->status_pengajuan == null || $kas['original']->status_pengajuan == '-1')
                    <form action="/administrasi/kas/pengajuanpenghapusan/{{ $kas['original']->id }}" method="POST">
                      @csrf
                      <button type="submit" class="btn btn-danger">
                        <span class="iconify fs-3 me-2" data-icon="fluent:delete-lines-20-filled"></span>
                      </button>
                    </form>
                  @elseif ($kas['original']->status_pengajuan == '0' && $kas['original']->status == null)
                    <p class="mb-0 badge badge-kas-diajukan">Diajukan</p>
                  @elseif ($kas['original']->status_pengajuan == '0' && $kas['original']->status == '-1')
                    <p class="mb-0 badge badge-kas-disetujui">Disetujui</p>
                  @elseif ($kas['original']->status_pengajuan == '0' && $kas['original']->status == '1')
                    <p class="mb-0 badge badge-kas-ditolak">Ditolak</p>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    const tablePanel = document.querySelector(".table-panel");
    tablePanel.scrollTop = tablePanel.scrollHeight;
  </script>
@endsection

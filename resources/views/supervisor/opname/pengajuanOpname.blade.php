@extends('layouts/main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Stok Opname</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    @if (session()->has('pesanSukses'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('pesanSukses') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    <div class="table-responsive mt-4">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Diajukan Oleh</th>
            <th scope="col" class="text-center">Diajukan Pada</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($opnames as $opname)
            <tr>
              <th scope="row" class="text-center">{{ $loop->iteration }}</th>
              <td class="text-center">{{ $opname->linkStaff->nama ?? null }}</td>
              <td class="text-center">
                @if ($opname->status_enum != null)
                  <p class="badge badge-opname-{{ $opname->status_enum }} mb-0">
                    {{ $opname->status_enum == '-1' ? 'Diajukan' : 'Dikonfirmasi' }}</p>
                @endif
              </td>
              <td class="text-center">
                {{ date('d F Y', strtotime($opname->created_at ?? '-')) }}
              </td>
              <td class="text-center">
                <a href="/supervisor/stokopname/{{ $opname->id ?? null }}" class="btn btn-primary"><span
                    class="iconify fs-4 me-1" data-icon="fluent:apps-list-detail-24-filled"></span>Detail</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

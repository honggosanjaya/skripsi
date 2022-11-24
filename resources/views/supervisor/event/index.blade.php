@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/supervisor.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Event</li>
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
    <a href="/supervisor/event/tambah" class="btn btn-primary"><span class="iconify fs-4 me-1"
        data-icon="dashicons:database-add"></span>Tambah Event</a>

    <div class="table-responsive">
      <table class="table table-sm table-hover" id="table">
        <thead>
          <tr>
            <th scope="col" class="text-center">Kode Event</th>
            <th scope="col" class="text-center">Nama Event</th>
            <th scope="col" class="text-center">Tanggal Mulai</th>
            <th scope="col" class="text-center">Tanggal Selesai</th>
            <th scope="col" class="text-center">PIC</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col" class="text-center">Gambar</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($events as $event)
            <tr>
              <td>{{ $event->kode ?? null }}</td>
              <td>{{ $event->nama ?? null }}</td>
              <td data-order="{{ date('Y-m-d', strtotime($event->date_start ?? '-')) }}">
                {{ date('d-m-Y', strtotime($event->date_start ?? '-')) }}
              </td>
              <td data-order="{{ date('Y-m-d', strtotime($event->date_end ?? '-')) }}">
                {{ date('d-m-Y', strtotime($event->date_end ?? '-')) }}
              </td>
              <td>{{ $event->linkStaff->nama ?? null }}</td>
              @if ($event->status_enum != null)
                <td>
                  @if ($event->status_enum == '1')
                    @if (strtotime($event->date_start) <= strtotime(date('d-m-Y')))
                      Active
                    @else
                      Belum mulai
                    @endif
                  @elseif ($event->status_enum == '0')
                    Belum mulai
                  @elseif ($event->status_enum == '-1')
                    Inactive
                  @endif
                </td>
              @else
                <td></td>
              @endif

              <td>
                @if ($event->gambar ?? null)
                  <img src="{{ asset('storage/event/' . $event->gambar) }}" class="img-fluid d-block mx-auto"
                    width="50px" height="50px">
                @endif
              </td>
              <td>
                <div class="d-flex justify-content-center">
                  <a href="/supervisor/event/ubah/{{ $event->id ?? null }}" class="btn btn-warning"><span
                      class="iconify fs-5" data-icon="eva:edit-2-fill"></span>Ubah</a>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="d-flex flex-row mt-4">
      {{ $events->links() }}
    </div>
  </div>
@endsection

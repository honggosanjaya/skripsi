@extends('layouts.mainreact')

@push('JS')
@endpush

@section('main_content')
  <div class="page_container pt-4">
    <form action="/salesman/historytrip" class="mb-4">
      <div class="mb-3">
        <label class="form-label">Tanggal Kunjungan</label>
        <input type='date' class="form-control" value="{{ $tanggal_kunjungan ?? date('Y-m-d') }}" name="tanggal_kunjungan">
      </div>
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm px-4">
          <i class="bi bi-search me-2"></i>Cari
        </button>
      </div>
    </form>


    @if ($targetkunjungan ?? null)
      <h6>Jumlah Kunjungan : {{ count($trips) }} / {{ $targetkunjungan->value ?? 0 }}
        <span class='text-primary'>( {{ round((count($trips) / $targetkunjungan->value) * 100, 3) }}% terpenuhi)</span>
      </h6>
    @else
      <h6>Jumlah Kunjungan : {{ count($trips) }}</h6>
    @endif

    @if ($targetec ?? null)
      <h6>Jumlah Effective Call : {{ count($ectrips) }} / {{ $targetec->value ?? 0 }}
        <span class='text-primary'>( {{ round((count($ectrips) / $targetec->value) * 100, 3) }}% terpenuhi)</span>
      </h6>
    @else
      <h6>Jumlah Effective Call : {{ count($ectrips) }}</h6>
    @endif


    <div class="table-responsive mt-4">
      <table class="table">
        <thead>
          <tr>
            <th scope="col" class='text-center'>Nama Toko</th>
            <th scope="col" class='text-center'>Wilayah</th>
            <th scope="col" class='text-center'>Jam Masuk</th>
            <th scope="col" class='text-center'>Jam Keluar</th>
            <th scope="col" class='text-center'>Effective Call</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($trips as $data)
            <tr data-bs-toggle="modal" data-bs-target="#exampleModal{{ $data->id }}">
              <td>{{ $data->linkCustomer->nama ?? null }}</td>
              <td>{{ $data->linkCustomer->linkDistrict->nama ?? null }}</td>
              <td class="text-center">
                @if ($data->waktu_masuk ?? null)
                  {{ date('G:i', strtotime($data->waktu_masuk)) }}
                @endif
              </td>
              <td class="text-center">
                @if ($data->waktu_keluar ?? null)
                  {{ date('G i', strtotime($data->waktu_keluar)) }}
                @endif
              </td>
              <td class="text-center">{{ $data->status_enum == '2' ? 'Ya' : 'Tidak' }}</td>
            </tr>

            <div class="modal fade" id="exampleModal{{ $data->id }}" tabindex="-1"
              aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Trip</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class='info-2column'>
                      <span class='d-flex'>
                        <b>Customer</b>
                        <p class='mb-0 word_wrap'>{{ $data->linkCustomer->nama ?? null }}</p>
                      </span>
                      <span class='d-flex'>
                        <b>Waktu Masuk</b>
                        <p class='mb-0 word_wrap'>
                          @if ($data->waktu_masuk ?? null)
                            {{ date('d F Y, g:i a', strtotime($data->waktu_masuk)) }}
                          @endif
                        </p>
                      </span>
                      <span class='d-flex'>
                        <b>Waktu Keluar</b>
                        <p class='mb-0 word_wrap'>
                          @if ($data->waktu_keluar ?? null)
                            {{ date('d F Y, g:i a', strtotime($data->waktu_keluar)) }}
                          @endif
                        </p>
                      </span>
                      <span class='d-flex'>
                        <b>Status</b>
                        <p class='mb-0 word_wrap'>
                          {{ $data->status_enum == '1' ? 'Tidak Effective Call' : 'Effective Call' }}</p>
                      </span>
                      @if ($data->alasan_penolakan)
                        <span class='d-flex'>
                          <b>Alasan Penolakan</b>
                          <p class='mb-0 word_wrap'>{{ $data->alasan_penolakan }}</p>
                        </span>
                      @endif
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                      <span class="iconify fs-3 me-1" data-icon="carbon:close-outline"></span>Tutup
                    </button>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

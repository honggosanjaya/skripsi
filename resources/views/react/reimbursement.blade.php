@extends('layouts.mainreact')


@push('JS')
  <script>
    @if (session()->has('successMessage'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: "{{ session('successMessage') }}",
        showConfirmButton: false,
        timer: 2000,
      });
    @endif

    $('.btn_history').on('click', function() {
      $('.history_tab').removeClass('d-none');
      $('.submission_tab').addClass('d-none');
    })

    $('.btn_submission').on('click', function() {
      $('.history_tab').addClass('d-none');
      $('.submission_tab').removeClass('d-none');
    })
  </script>
@endpush

@section('main_content')
  <div class="page_container pt-4">
    <div class="d-flex justify-content-center gap-4">
      <button class="btn btn-primary btn_history w-50">History</button>
      <button class="btn btn-outline-success btn_submission w-50">Submission</button>
    </div>

    <div class='mt-4 history_tab'>
      @foreach ($histories as $history)
        <div class="card_reimbursement" data-bs-toggle="modal" data-bs-target="#detailModal{{ $history->id }}">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <p class="fs-7 mb-0 fw-bold">Tanggal pengajuan:</p>
              {{ date('d F Y', strtotime($history->created_at)) }}
            </div>
            @if ($history->status_enum == 0)
              <div class="badge bg-primary">Diajukan</div>
            @elseif ($history->status_enum == 1)
              <div class="badge bg-warning">Diproses</div>
            @elseif ($history->status_enum == 2)
              <div class="badge bg-success">Dibayar</div>
            @else
              <div class="badge bg-danger">Ditolak</div>
            @endif
          </div>
          <p class="fs-7 mb-0 fw-bold">Pengajuan sebesar Rp {{ number_format($history->jumlah_uang ?? 0, 0, '', '.') }}
          </p>
        </div>

        <div class="modal fade" id="detailModal{{ $history->id }}" tabindex="-1" aria-labelledby="detailModalLabel"
          aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="detailModalLabel">Detail Pengajuan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                @if ($history->foto ?? null)
                  <img src="{{ asset('storage/reimbursement/' . $history->foto) }}"
                    class="img_reimbursement img-fluid mb-3">
                @endif
                <div class='info-2column'>
                  <span class='d-flex'>
                    <b>Status</b>
                    <div class='word_wrap'>
                      @if ($history->status_enum == 0)
                        Diajukan
                      @elseif ($history->status_enum == 1)
                        Diproses
                      @elseif ($history->status_enum == 2)
                        Dibayar
                      @else
                        Ditolak
                      @endif
                    </div>
                  </span>
                  @if ($history->id_staff_pengonfirmasi ?? null)
                    <span class='d-flex'>
                      <b>Dikonfirmasi Oleh</b>
                      <div class='word_wrap'>{{ $history->linkStaffPengonfirmasi->nama }}</div>
                  @endif
                  <span class='d-flex'>
                    <b>Jumlah</b>
                    <div class='word_wrap'>{{ $history->jumlah_uang }}</div>
                  </span>
                  @if ($history->keterangan_konfirmasi ?? null)
                    <span class='d-flex'>
                      <b>Ket. Konfirmasi</b>
                      <div class='word_wrap'>{{ $history->keterangan_konfirmasi }}</div>
                    </span>
                  @endif
                  @if ($history->keterangan_pengajuan ?? null)
                    <span class='d-flex'>
                      <b>Ket. Pengajuan</b>
                      <div class='word_wrap'>{{ $history->keterangan_pengajuan }}</div>
                    </span>
                  @endif
                  <span class='d-flex'>
                    <b>Keperluan</b>
                    <div class='word_wrap'>{{ $history->linkCashAccount->nama }}</div>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-4 submission_tab d-none">
      <form method="POST" action="/lapangan/submitreimbursement" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
          <label class="form-label">Jenis Pengeluaran <span class='text-danger'>*</span></label>
          <select class="form-select selectdua" name="id_cash_account">
            @foreach ($cashaccount as $akun)
              @if ($akun[3] > 100 && $akun[2] != '3')
                <option value={{ $akun[1] }}>{{ $akun[0] }}</option>
              @endif
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Jumlah Pengeluaran <span class='text-danger'>*</span></label>
          <div class="input-group mb-3">
            <span class="input-group-text">Rp</span>
            <input type="number" class="form-control @error('jumlah_uang') is-invalid @enderror" name="jumlah_uang" />
            @error('jumlah_uang')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Keterangan</label>
          <textarea class="form-control" name="keterangan_pengajuan"></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Foto Bukti Pembayaran <span class='text-danger'>*</span></label>
          <img class="img-preview img-fluid">
          <input type="file" accept="image/*" capture="camera"
            class="form-control @error('gambar') is-invalid @enderror input-gambar" id="gambar" name="gambar"
            onchange="previewImage()" required />
          @error('gambar')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-4">
          Submit
        </button>
      </form>
    </div>
  </div>
@endsection

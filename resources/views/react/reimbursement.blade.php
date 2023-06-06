@extends('layouts.mainreact')

@section('main_content')
  <div class="page_container pt-4">
    <div class="d-flex justify-content-center">
      <button class="btn btn-primary">History</button>
      <button class="btn btn-outline-success">Submission</button>
    </div>

    <div class='mt-4 history_tab'>
      <h1 class='fw-bold fs-5'>History Pengajuan</h1>
      <hr />
      {{-- historyReimbursement --}}
      @foreach ($collection as $item)
        <div class="card_reimbursement">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <p class="fs-7 mb-0 fw-bold">Tanggal pengajuan:</p>
              {history.created_at && <p class="fs-7 mb-0">{getDatePengajuan(history.created_at)}</p>}
            </div>
            <div class="badge bg-warning text-black fw-normal">
              {history.status_enum == 0 ? 'Diajukan' :
              history.status_enum == 1 ? 'Diproses' :
              history.status_enum == 2 ? 'Dibayar' :
              'Ditolak'
              }
            </div>
          </div>
          {history.jumlah_uang && <p class="fs-7 mb-0 fw-bold">Pengajuan sebesar {convertPrice(history.jumlah_uang)}
          </p>}
        </div>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
          data-bs-target="#detailModal{{ $data->id }}">
          Launch demo modal
        </button>


        <div class="modal fade" id="detailModal{{ $data->id }}" tabindex="-1" aria-labelledby="detailModalLabel"
          aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="detailModalLabel">Detail Pengajuan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                {history.foto && <img src={`${urlAsset}/storage/reimbursement/${history.foto}`}
                  class="img-fluid img_reimbursement mb-3" />}
                <div class='info-2column'>
                  <span class='d-flex'>
                    <b>Status</b>
                    <div class='word_wrap'>
                      {history.status_enum == 0 ? 'Diajukan' :
                      history.status_enum == 1 ? 'Diproses' :
                      history.status_enum == 2 ? 'Dibayar' :
                      'Ditolak'
                      }
                    </div>
                  </span>
                  {history.status_enum != '0' && <span class='d-flex'>
                    <b>Dikonfirmasi Oleh</b>
                    {history.link_staff_pengonfirmasi && <div class='word_wrap'>{history.link_staff_pengonfirmasi.nama}
                    </div>}
                  </span>}
                  <span class='d-flex'>
                    <b>Jumlah</b>
                    {history.jumlah_uang && <div class='word_wrap'>{convertPrice(history.jumlah_uang)}</div>}
                  </span>
                  <span class='d-flex'>
                    <b>Keterangan</b>
                    <div class='word_wrap'>{history.keterangan_pengajuan ?? null}</div>
                  </span>
                  <span class='d-flex'>
                    <b>Keperluan</b>
                    {history.link_cash_account && <div class='word_wrap'>{history.link_cash_account.nama}</div>}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach

    </div>


    <div class="mt-4 submission_tab">
      <h1 class='fw-bold fs-5'>Pengajuan</h1>
      <hr />
      <form method="POST" id='data-form' action="/lapangan/submitreimbursement" enctype="multipart/form-data">

        <div class="mb-3">
          <label class="form-label">Jenis Pengeluaran <span class='text-danger'>*</span></label>
          <select class="form-select" name="jenis_pengeluaran">
            {{-- @foreach ($cashaccount as $akun)
                      
                  @endforeach --}}
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Jumlah Pengeluaran <span class='text-danger'>*</span></label>
          <div class="input-group mb-3">
            <span class="input-group-text">Rp</span>
            <input type="number" class="form-control" name="jumlah_pengeluaran" />
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Keterangan</label>
          <textarea class="form-control" name="keterangan"></textarea>
        </div>

        <label class="form-label d-block mt-4">Foto Bukti Pembayaran <span class='text-danger'>*</span></label>

        <button type='button' class='btn btn-primary mb-3'>
          <span class="iconify fs-3 me-1" data-icon="clarity:image-gallery-solid"></span> Galeri
        </button>

        <button type='button' class='btn btn-secondary ms-3 mb-3'>
          <span class="iconify fs-3 me-1" data-icon="charm:camera"></span>Kamera
        </button>

        <input type="file" accept="image/png, image/jpeg" name="foto_bukti">

        <button type="submit" class="btn btn-primary w-100 mt-4">
          Submit
        </button>
      </form>
    </div>


  </div>
@endsection

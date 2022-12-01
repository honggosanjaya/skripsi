@extends('layouts/main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/reimbursement">Reimbursement</a></li>
    @if ($reimbursement->status_enum == '0')
      <li class="breadcrumb-item"><a href="/administrasi/reimbursement/pengajuan">Pengajuan</a></li>
    @else
      <li class="breadcrumb-item"><a href="/administrasi/reimbursement/pembayaran">Pembayaran</a></li>
    @endif
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5" id="detail-reimbursement">
    <div class="informasi-list mb_big">
      <span><b>Nama Pengaju</b>{{ $reimbursement->linkStaffPengaju->nama ?? null }}</span>
      <span><b>Nama Pengonfirmasi</b>{{ $reimbursement->linkStaffPengonfirmas->nama ?? null }}</span>

      @if ($reimbursement->status_enum == '0')
        <span class="d-flex"><b>Cash Account</b>
          <div class="row">
            <div class="col col-btn-edit">
              {{ $reimbursement->linkCashAccount->nama ?? null }}
              <button class="btn btn-warning ms-4 btn-sm btn-edit">
                <span class="iconify fs-5 me-1" data-icon="eva:edit-2-fill"></span> Edit
              </button>
            </div>

            <div class="col col-edit-cash-account d-none">
              <div class="mb-3">
                <select class="form-select select-cash-account">
                  @foreach ($cash_accounts as $account)
                    @if (old('id_cash_account', $reimbursement->id_cash_account ?? null) == $account[1] &&
                        $account[2] != '3' &&
                        $account[3] > 100)
                      <option value="{{ $account[1] }}" selected>{{ $account[3] . ' - ' . $account[0] }}</option>
                    @elseif($account[2] != '3' && $account[3] > 100)
                      <option value="{{ $account[1] }}">{{ $account[3] . ' - ' . $account[0] }}</option>
                    @endif
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </span>
      @else
        <span class="d-flex"><b>Cash Account</b>{{ $reimbursement->linkCashAccount->nama ?? null }}</span>
      @endif
      <span><b>Jumlah</b>Rp {{ number_format($reimbursement->jumlah_uang ?? 0, 0, '', '.') }}</span>
      <span><b>Keterangan Pengajuan</b>{{ $reimbursement->keterangan_pengajuan ?? null }}</span>
      <span><b>Keterangan Konfirmasi</b>{{ $reimbursement->keterangan_konfirmasi ?? null }}</span>
      @if ($reimbursement->status_enum != null)
        @if ($reimbursement->status_enum == '0')
          <span><b>Status</b>Diajukan</span>
        @elseif ($reimbursement->status_enum == '1')
          <span><b>Status</b>Diproses</span>
        @elseif ($reimbursement->status_enum == '2')
          <span><b>Status</b>Dibayar</span>
        @elseif ($reimbursement->status_enum == '-1')
          <span><b>Status</b>Ditolak</span>
        @endif
      @endif

      @if ($reimbursement->foto)
        <span><b>Foto Bukti</b>
          <img src="{{ asset('storage/reimbursement/' . $reimbursement->foto) }}" class="img-preview img-fluid">
        </span>
      @endif
    </div>

    @if ($reimbursement->status_enum != null)
      @if ($reimbursement->status_enum == '0')
        <form id="formtolak" action="/administrasi/reimbursement/pengajuan/tolak/{{ $reimbursement->id }}"
          method="POST">
          @csrf
          <input type="hidden" name="id_cash_account" class="input_id_cash_account">
          <textarea class="d-none" id="keterangan_konfirmasi" name="keterangan_konfirmasi"></textarea>
        </form>

        <form id="formsetuju" action="/administrasi/reimbursement/pengajuan/setuju/{{ $reimbursement->id }}"
          method="POST">
          @csrf
          <input type="hidden" name="id_cash_account" class="input_id_cash_account">
          <div class="row">
            <div class="col-6">
              <div class="mb-3">
                <label for="keterangan_konfirmasi" class="form-label">Keterangan <span
                    class="text-danger">*</span></label>
                <textarea class="form-control @error('keterangan_konfirmasi') is-invalid @enderror" id="keterangan_konfirmasi"
                  name="keterangan_konfirmasi">{{ old('keterangan_konfirmasi') }}</textarea>
                @error('keterangan_konfirmasi')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
          </div>

          <div class="row justify-content-end mt-4">
            <div class="col d-flex justify-content-end">
              <button type="submit" form="formtolak" class="btn btn-sm btn-danger me-3">
                <span class="iconify fs-5 me-1" data-icon="emojione-monotone:heavy-multiplication-x"></span> Tolak
              </button>
              <button type="submit" form="formsetuju" class="btn btn-sm btn-success">
                <span class="iconify fs-5 me-1" data-icon="akar-icons:check"></span> Setuju
              </button>
            </div>
          </div>
        </form>
      @elseif ($reimbursement->status_enum == '1')
        <hr>
        <form action="/administrasi/reimbursement/pengajuan/dibayar/{{ $reimbursement->id }}" method="POST">
          @csrf
          <div class="row">
            <input type="hidden" value="{{ $reimbursement->linkCashAccount->id }}" id="idCashAccount"
              name="idCashAccount">
            <div class="col-6">
              <div class="mb-3">
                <label for="kas" class="form-label">Pilih Kas yang Berkurang <span
                    class='text-danger'>*</span></label>
                <select class="form-select" name="kas">
                  @foreach ($listskas as $kas)
                    @if (old('kas') == $kas->id)
                      <option value="{{ $kas->id }}" selected>{{ $kas->nama }}</option>
                    @else
                      <option value="{{ $kas->id }}">{{ $kas->nama }}</option>
                    @endif
                  @endforeach
                </select>
              </div>

              <div class="row justify-content-end mt-4">
                <div class="col d-flex justify-content-end">
                  <button type="submit" class="btn btn-sm btn-success">
                    <span class="iconify fs-5 me-1" data-icon="akar-icons:check"></span> Dibayarkan
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      @endif
    @endif
  </div>

  @push('JS')
    <script>
      const keterangan = document.querySelector("#formsetuju #keterangan_konfirmasi");
      const inputfield = document.querySelector("#formtolak #keterangan_konfirmasi");

      keterangan.addEventListener("change", function() {
        inputfield.value = keterangan.value;
      });

      $(document).on('click', '#detail-reimbursement .btn-edit', function() {
        $('#detail-reimbursement .col-btn-edit').addClass("d-none");
        $("#detail-reimbursement .col-edit-cash-account").removeClass("d-none");
      });

      $(document).on('change', '#detail-reimbursement .select-cash-account', function(e) {
        $('.input_id_cash_account').val(e.target.value);
      });
    </script>
  @endpush
@endsection

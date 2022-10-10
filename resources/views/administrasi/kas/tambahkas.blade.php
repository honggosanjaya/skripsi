@extends('layouts.main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/kas">Kas</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5" id="tambahkas">
    <h1 class="fs-5 mb-4">{{ $title }}</h1>
    <form method="POST" id='data-form' action="/administrasi/kas/store" enctype="multipart/form-data">
      @csrf
      <input type="hidden" value="{{ $idCashaccount }}" id="kas" name="kas">
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="no_bukti" class="form-label">No. Bukti </label>
            <input type="text" class="form-control @error('no_bukti') is-invalid @enderror" id="no_bukti"
              name="no_bukti" value="{{ old('no_bukti') }}">
            @error('no_bukti')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>

        <div class="col">
          <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal <span class='text-danger'>*</span></label>
            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal"
              name="tanggal" value="{{ old('tanggal') }}">
            @error('tanggal')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="kontak" class="form-label">Kontak</label>
            <input type="text" class="form-control @error('kontak') is-invalid @enderror" id="kontak" name="kontak"
              value="{{ old('kontak') }}">
            @error('kontak')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label for="id_cash_account" class="form-label">Cash Account <span class='text-danger'>*</span></label>
            <select class="form-select select-cash-account" name="id_cash_account" id="id_cash_account">
              <option disabled selected value>Pilih Cash Account</option>
              @foreach ($cash_accounts as $account)
                @if (old('id_cash_account') == $account[1] && $account[2] != '3')
                  <option value="{{ $account[1] }}" selected>{{ $account[3] . ' - ' . $account[0] }}</option>
                @elseif($account[2] != '3')
                  <option value="{{ $account[1] }}">{{ $account[3] . ' - ' . $account[0] }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="debit_kredit" class="form-label">Debit / Kredit <span class="text-danger">*</span></label>
            <select class="form-select" name="debit_kredit">
              @foreach ($debitkredits as $key => $val)
                @if (old('debit_kredit') == $key)
                  <option value="{{ $key }}" selected>{{ $val }}</option>
                @else
                  <option value="{{ $key }}">{{ $val }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label for="uang" class="form-label">Uang<span class='text-danger'>*</span></label>
            <div class="input-group">
              <span class="input-group-text" id="basic-addon1">Rp.</span>
              <input type="text" class="form-control @error('uang') is-invalid @enderror" id="uang" name="uang"
                value="{{ old('uang') }}">
            </div>
            @error('uang')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="keterangan_1" class="form-label">Keterangan 1</label>
            <textarea class="form-control @error('keterangan_1') is-invalid @enderror" id="keterangan_1" name="keterangan_1">{{ old('keterangan_1') }}</textarea>
            @error('keterangan_1')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label for="keterangan_2" class="form-label">Keterangan 2</label>
            <textarea class="form-control @error('keterangan_2') is-invalid @enderror" id="keterangan_2" name="keterangan_2">{{ old('keterangan_2') }}</textarea>
            @error('keterangan_2')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row justify-content-end mt-4">
        <div class="col-3 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary btn-submit"><span class="iconify me-1 fs-3"
              data-icon="dashicons:database-add"></span>Tambah Data</button>
        </div>
      </div>
    </form>
  </div>

  @push('JS')
    <script>
      $(document).ready(function() {
        $('.select-cash-account').select2();
      });
    </script>
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

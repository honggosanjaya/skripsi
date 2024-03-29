@extends('layouts/main')

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/cashaccount">Cash Account</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <form id="form_submit" class="form-submit" method="POST" action="/supervisor/cashaccount/tambah">
      @csrf
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Cash Account<span class='text-danger'>*</span></label>
            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
              value="{{ old('nama') }}">
            @error('nama')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label for="account" class="form-label">Account<span class='text-danger'>*</span></label>
            <input type="number" class="form-control @error('account') is-invalid @enderror" id="account"
              name="account" value="{{ old('account') }}">
            @error('account')
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
            <label for="account_parent" class="form-label">Account yang Dituju (Parent)</label>
            <select class="form-select select-account-parent @error('account_parent') is-invalid @enderror"
              name="account_parent">
              <option value="">-- Pilih Account --</option>
              @foreach ($dropdown as $d)
                @if ($d[1] == old('account_parent'))
                  <option value="{{ $d[1] }}" selected>{{ $d[1] }} - {{ $d[0] }}</option>
                @else
                  <option value="{{ $d[1] }}">{{ $d[1] }} - {{ $d[0] }}</option>
                @endif
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="default" class="form-label">Default</label>
            <select class="form-select" name="default">
              <option value="">Tidak Ada</option>
              @foreach ($defaults as $key => $val)
                @if (old('default') == $key)
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
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan">{{ old('keterangan') }}</textarea>
            @error('keterangan')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row justify-content-end mt-4">
        <div class="col-3 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary"><span class="iconify me-1 fs-3"
              data-icon="dashicons:database-add"></span>Tambah Data</button>
        </div>
      </div>
    </form>
  </div>

  @push('JS')
    <script>
      $(document).ready(function() {
        $('.select-account-parent').select2();
      });
    </script>
  @endpush
@endsection

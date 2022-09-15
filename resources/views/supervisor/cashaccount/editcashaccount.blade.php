@extends('layouts/main')

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/cashaccount">Cash Account</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ubah</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <form id="form_submit" class="form-submit" method="POST" action="/supervisor/cashaccount/ubah/{{ $cashaccount->id }}"
      enctype="multipart/form-data">
      @method('put')
      @csrf
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Cash Account <span class='text-danger'>*</span></label>
            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
              value="{{ old('nama', $cashaccount->nama ?? null) }}">
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
              name="account" value="{{ old('account', $cashaccount->account) }}">
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
            <label for="default" class="form-label">Default <span class="text-danger">*</span></label>
            <select class="form-select" name="default">
              <option value="">Tidak Ada</option>
              @foreach ($defaults as $key => $val)
                @if (old('default', $cashaccount->default) == $key)
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
            <label for="keterangan" class="form-label">Keterangan <span class='text-danger'>*</span></label>
            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan">{{ $cashaccount->keterangan ?? null }}</textarea>
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
          <button type="submit" class="btn btn-warning">Ubah</button>
        </div>
      </div>
    </form>
  </div>
@endsection

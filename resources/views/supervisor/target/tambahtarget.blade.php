@extends('layouts/main')

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/target">Target</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  </ol>
@endsection

@section('main_content')
  <div id="event-form">
    <div class="pt-4 px-5">
      <form id="form_submit" class="form-submit" method="POST" action="/supervisor/target/tambah"
        enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col">
            <div class="mb-3">
              <label for="jenis_target" class="form-label">Jenis Target <span class="text-danger">*</span></label>
              <select class="form-select" name="jenis_target">
                @foreach ($jenis as $key => $val)
                  @if (old('jenis_target') == $key)
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
              <label for="value" class="form-label">Value <span class='text-danger'>*</span></label>
              <input type="text" class="form-control @error('value') is-invalid @enderror" id="value"
                name="value" value="{{ old('value') }}">
              @error('value')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>

        <div class="row justify-content-end mt-4">
          <div class="col-3 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
              <span class="iconify me-1 fs-3" data-icon="dashicons:database-add"></span>
              Tambah Data
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
  @push('JS')
    <script src="{{ mix('js/supervisor.js') }}"></script>
  @endpush
@endsection

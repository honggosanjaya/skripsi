@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/supervisor.css') }}" rel="stylesheet">
@endpush

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/profil">Profil</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ubah Password</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <div class="row justify-content-center">
      <div class="col-8">
        @if (session()->has('passwordError'))
          <div id="hideMeAfter3Seconds">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ session('passwordError') }}
              <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>
        @endif

        <form method="POST" action="/supervisor/profil/check/{{ auth()->user()->id }}">
          @csrf
          <div class="mb-3">
            <label class="form-label" hidden>User ID</label>
            <input type="text" class="form-control" value="{{ auth()->user()->id }}" readonly hidden>
          </div>
          <div class="mb-3">
            <label for="passwordLama" class="form-label">Password Lama</label>
            <input id="passwordLama" type="password" class="form-control @error('passwordLama') is-invalid @enderror"
              name="passwordLama" placeholder="Masukkan Password Lama">
            @error('passwordLama')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="row justify-content-end mt-4">
            <div class="col d-flex justify-content-end">
              <a href="/supervisor/profil" class="batalkanAksi_btn btn btn-danger me-3"><span class="iconify fs-4 me-1"
                  data-icon="emojione-monotone:heavy-multiplication-x"></span>Batal</a>
              <button type="submit" class="btn btn-success"><span class="iconify fs-4 me-1"
                  data-icon="bi:check-lg"></span>
                Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

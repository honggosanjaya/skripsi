@extends('customer.layouts.customerLayouts')

@section('header')
  <header class='header_mobile d-flex justify-content-between align-items-center'>
    <div class="d-flex">
      <a href="/customer/profil">
        <span class="iconify fs-3 text-white me-2" data-icon="eva:arrow-back-fill"></span>
      </a>
      <h1 class="page_title">Ubah Password</h1>
    </div>
  </header>
@endsection

@section('content')
  <div class="pt-4">
    @if (session()->has('passwordSuccess'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('passwordSuccess') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    @if (session()->has('matchError'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('matchError') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    <form method="POST" action="/customer/profil/gantipassword/{{ auth()->user()->id }}">
      @csrf
      <div>
        <label class="form-label" hidden>User ID</label>
        <input type="text" class="form-control" value="{{ auth()->user()->id }}" readonly hidden>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label fw-bold">Password Baru</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"
          placeholder="Masukkan Password Baru">
        @error('password')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="konfirmasiPasswordBaru" class="form-label fw-bold">Konfirmasi Password Baru</label>
        <input id="konfirmasiPasswordBaru" type="password"
          class="form-control @error('konfirmasiPasswordBaru') is-invalid @enderror" name="konfirmasiPasswordBaru"
          placeholder="Masukkan Konfirmasi Password Baru">
        @error('konfirmasiPasswordBaru')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <button type="submit" class="btn btn-outline-primary w-100 mt-4">Submit</button>
    </form>
  </div>
@endsection

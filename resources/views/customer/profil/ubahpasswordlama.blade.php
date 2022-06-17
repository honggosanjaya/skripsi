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
    @if (session()->has('passwordError'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('passwordError') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    <form method="POST" action="/customer/profil/check/{{ auth()->user()->id }}">
      @csrf
      <div>
        <label class="form-label" hidden>User ID</label>
        <input type="text" class="form-control" value="{{ auth()->user()->id }}" readonly hidden>
      </div>

      <div class="mb-3">
        <label for="passwordLama" class="form-label fw-bold">Password Lama</label>
        <input id="passwordLama" type="password" class="form-control @error('passwordLama') is-invalid @enderror"
          name="passwordLama" placeholder="Masukkan Password Lama">
        @error('passwordLama')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <button type="submit" class="btn btn-outline-primary w-100 mt-5">Submit</button>
    </form>
  </div>
@endsection

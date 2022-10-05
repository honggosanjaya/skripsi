@extends('layouts.main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/kas">Kas</a></li>
    <li class="breadcrumb-item active" aria-current="page">Cetak</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <h1 class="fs-5 mb-4">{{ $title }}</h1>

    <form id="form_submit" class="form-submit" method="POST"
      action="/administrasi/kas/print/{{ $cashaccount->id }}/cetak-kas">
      @csrf
      <div class="row">
        <div class="col-sm-6">
          <div class="mb-3">
            <label class="form-label">Date Start</label>
            <input type="date" name="dateStart" class="form-control" value="{{ $input['dateStart'] ?? null }}"
              id="dateStart">
          </div>
        </div>
        <div class="col-sm-6">
          <div class="mb-3">
            <label class="form-label">Date End</label>
            <input type="date" name="dateEnd" class="form-control" min="{{ $input['dateStart'] ?? null }}"
              value="{{ $input['dateEnd'] ?? null }}" id="dateEnd">
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-6">
          <label for="id_akun" class="form-label">Pilih Account</label>
          <select class="form-select" name="id_akun">
            <option value="{{ null }}">Pilih Semua</option>
            @foreach ($cashaccounts as $account)
              @if (old('id_akun') == $account[1] && $account[2] != '3')
                <option value="{{ $account[1] }}" selected>{{ $account[0] }}</option>
              @elseif($account[2] != '3')
                <option value="{{ $account[1] }}">{{ $account[0] }}</option>
              @endif
            @endforeach
          </select>
        </div>
      </div>

      <div class="row justify-content-end">
        <div class="col d-flex justify-content-end">
          <button type="submit" class="btn btn-primary"> <span class="iconify fs-3 me-2"
              data-icon="ic:round-print"></span>Cetak</button>
        </div>
      </div>
    </form>
  </div>

  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

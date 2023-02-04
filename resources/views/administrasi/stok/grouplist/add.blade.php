@extends('layouts.main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/produk">Produk</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/produk/grouplist">Group List</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5" id="data-produk">
    <form method="POST" id='data-form' action="/administrasi/stok/produk/grouplist/create" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <div class="col">
          <div class="mb-2">
            <label class="form-label">Group Item <span class='text-danger'>*</span></label>
            <select class="form-select" name="id_group_item" required>
              <option disabled selected value>-- Pilih Group Item --</option>
              @foreach ($items_group as $item)
                @if (old('id_group_item') == $item->id)
                  <option value="{{ $item->id }}" selected>{{ $item->nama ?? null }}</option>
                @else
                  <option value="{{ $item->id }}">{{ $item->nama ?? null }}</option>
                @endif
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <div class="input-group">
              <span class="input-group-text">Value <span class='text-danger'>*</span></span>
              <input type="number" class="form-control @error('value') is-invalid @enderror" id="value"
                name="value" value="{{ old('value') }}" min="1" required>
            </div>
            @error('value')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
        <div class="col">
          @if (old('id_item'))
            <section id="multiple-select">
              <div class="form-group">
                <div class="row">
                  <div class="col">
                    <label class="form-label">Item Pembentuk<span class='text-danger'>*</span></label>
                  </div>
                </div>

                @foreach (old('id_item') as $index => $data)
                  <div class="form-input">
                    <div class="row">
                      <div class="col">
                        <select class="select-multiple form-select @error('id_item') is-invalid @enderror"
                          name="id_item[]" required>
                          <option disabled value>-- Pilih Item --</option>
                          @foreach ($items as $item)
                            @if (old('id_item')[$index] == $item->id)
                              <option value="{{ $item->id }}" selected>{{ $item->nama ?? null }}</option>
                            @else
                              <option value="{{ $item->id }}">{{ $item->nama ?? null }}</option>
                            @endif
                          @endforeach
                        </select>
                        @error('id_item')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    <div class="row mt-2">
                      <div class="col">
                        <div class="input-group mb-3">
                          <span class="input-group-text">Kuantitas <span class='text-danger'>*</span></span>
                          <input type="number" class="form-control" name="value_item[]"
                            value="{{ old('value_item')[$index] }}" min="1" required>
                        </div>
                        @error('value_item')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="d-flex justify-content-end my-3">
                          <button class="btn btn-danger remove-form me-3 {{ count(old('id_item')) > 1 ? '' : 'd-none' }}"
                            type="button">
                            -
                          </button>
                          <button class="btn btn-success add-form" type="button">
                            +
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </section>
          @else
            <section id="multiple-select">
              <div class="form-group">
                <div class="row">
                  <div class="col">
                    <label class="form-label">Item Pembentuk<span class='text-danger'>*</span></label>
                  </div>
                </div>
                <div class="form-input">
                  <div class="row">
                    <div class="col">
                      <select class="select-multiple form-select @error('id_item') is-invalid @enderror" name="id_item[]"
                        required>
                        <option disabled selected value>-- Pilih Item --</option>
                        @foreach ($items as $item)
                          <option value="{{ $item->id }}">{{ $item->nama ?? null }}</option>
                        @endforeach
                      </select>
                      @error('id_item')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="row mt-2">
                    <div class="col">
                      <div class="input-group mb-3">
                        <span class="input-group-text">Kuantitas <span class='text-danger'>*</span></span>
                        <input type="number" class="form-control" name="value_item[]" min="1" required>
                      </div>
                      @error('value_item')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="d-flex justify-content-end my-3">
                        <button class="btn btn-danger remove-form me-3 d-none" type="button">
                          -
                        </button>
                        <button class="btn btn-success add-form" type="button">
                          +
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          @endif
        </div>
      </div>

      <div class="row justify-content-end mt-4">
        <div class="col d-flex justify-content-end">
          <button type="submit" class="btn btn-primary"><span class="iconify me-1 fs-3"
              data-icon="dashicons:database-add"></span>Tambah Data</button>
        </div>
      </div>
    </form>
  </div>

  @push('JS')
    <script>
      const ids = {!! json_encode(old('id_item')) !!};
      if (ids) {
        ids.forEach((id) => {
          $('.select-multiple option[value="' + id + '"]').addClass('disabled-option');
        });
      }
    </script>
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

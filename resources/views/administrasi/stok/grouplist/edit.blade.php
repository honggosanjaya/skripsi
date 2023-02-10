@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/produk">Produk</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/produk/grouplist">Group List</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
  </ol>
@endsection

@section('main_content')
  <div class="px-5 pt-4">
    @if (session()->has('successMessage'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('successMessage') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    <form method="POST" action="/administrasi/stok/produk/grouplist/update/{{ $id_group_item }}">
      @method('put')
      @csrf
      <div class="row">
        <div class="col">
          <div class="mb-2">
            <label class="form-label">Group Item <span class='text-danger'>*</span></label>
            <select class="form-select @error('id_group_item') is-invalid @enderror" name="id_group_item" required>
              <option disabled selected value>-- Pilih Group Item --</option>
              @foreach ($items_group as $item)
                @if ($item->id == old('id_group_item', $id_group_item))
                  <option value="{{ $item->id }}" selected>{{ $item->nama ?? null }}</option>
                @else
                  <option value="{{ $item->id }}">{{ $item->nama ?? null }}</option>
                @endif
              @endforeach
            </select>
            @error('id_group_item')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
          <div class="mb-3">
            <div class="input-group">
              <span class="input-group-text">Value <span class='text-danger'>*</span></span>
              <input type="number" class="form-control @error('value') is-invalid @enderror" id="value"
                name="value" value="{{ old('value', $groupitems[0]->value) }}" min="1" required>
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
                          <span class="input-group-text">Kuantitas<span class='text-danger'>*</span></span>
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
                @foreach ($groupitems as $index => $data)
                  <div class="form-input">
                    <div class="row">
                      <div class="col">
                        <select class="select-multiple form-select @error('id_item') is-invalid @enderror"
                          name="id_item[]" required>
                          <option disabled value>-- Pilih Item --</option>
                          @foreach ($items as $item)
                            @if ($data->id_item == $item->id)
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
                          <span class="input-group-text">Kuantitas<span class='text-danger'>*</span></span>
                          <input type="number" class="form-control" name="value_item[]" value="{{ $data->value_item }}"
                            min="1" required>
                        </div>
                        @error('value_item')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="d-flex justify-content-end my-3">
                          <button class="btn btn-danger remove-form me-3 {{ count($groupitems) > 1 ? '' : 'd-none' }}"
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
          @endif
        </div>
      </div>

      <div class="row justify-content-end mt-4">
        <div class="col d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">
            <span class="iconify fs-3 me-2" data-icon="bi:send-check"></span>Submit
          </button>
        </div>
      </div>
    </form>
  </div>

  @push('JS')
    <script>
      const oldIds = {!! json_encode(old('id_item')) !!};
      const ids = {!! json_encode($selected_id_item) !!};

      if (oldIds) {
        oldIds.forEach((id) => {
          $('.select-multiple option[value="' + id + '"]').addClass('disabled-option');
        });
      } else {
        ids.forEach((id) => {
          $('.select-multiple option[value="' + id + '"]').addClass('disabled-option');
        });
      }
    </script>
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

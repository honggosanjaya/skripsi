@extends('layouts.main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/produk">Produk</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  </ol>
@endsection

@section('main_content')
  <div id="data-produk">
    <div class="pt-4 px-5">
      <form method="POST" id='data-form' action="/administrasi/stok/produk" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col">
            <div class="mb-3">
              <label for="nama" class="form-label">Nama <span class='text-danger'>*</span></label>
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
              <label for="kode_barang" class="form-label">Kode Barang <span class='text-danger'>*</span></label>
              <input type="text" class="form-control @error('kode_barang') is-invalid @enderror" id="kode_barang"
                name="kode_barang" value="{{ old('kode_barang') }}">
              @error('kode_barang')
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
              <label for="satuan" class="form-label">Satuan <span class='text-danger'>*</span></label>
              <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan"
                name="satuan" value="{{ old('satuan') }}">
              @error('satuan')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>

          <div class="col">
            <div class="mb-3">
              <label for="link_item" class="form-label">Item yang Dituju (Parent)</label>
              <select class="form-select @error('nama_wilayah') is-invalid @enderror" name="link_item">
                <option value="">-- Pilih Item --</option>
                @foreach ($parentItems as $parentItem)
                  @if (old('link_item') == $parentItem->id)
                    <option value="{{ $parentItem->id }}" selected>{{ $parentItem->nama ?? null }}</option>
                    {{-- <option value="{{ $parentItem[1] }}" selected>{{ $parentItem[0] }}</option> --}}
                  @else
                    <option value="{{ $parentItem->id }}">{{ $parentItem->nama ?? null }}</option>
                    {{-- <option value="{{ $parentItem[1] }}">{{ $parentItem[0] }}</option> --}}
                  @endif
                @endforeach
              </select>
              @error('link_item')
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
              <label for="stok" class="form-label">Stok</label>
              <input type="text" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok"
                value="{{ old('stok') }}">
              @error('stok')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col">
            <div class="mb-3">
              <label for="min_stok" class="form-label">Min Stok</label>
              <input type="text" class="form-control @error('min_stok') is-invalid @enderror" id="min_stok"
                name="min_stok" value="{{ old('min_stok') }}">
              @error('min_stok')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col">
            <div class="mb-3">
              <label for="max_stok" class="form-label">Max Stok</label>
              <input type="text" class="form-control @error('max_stok') is-invalid @enderror" id="max_stok"
                name="max_stok" value="{{ old('max_stok') }}">
              @error('max_stok')
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
              <label for="harga1_satuan" class="form-label">Harga1 Satuan <span class='text-danger'>*</span></label>
              <div class="input-group">
                <span class="input-group-text" id="basic-addon1">Rp.</span>
                <input type="text" class="form-control @error('harga1_satuan') is-invalid @enderror"
                  id="harga1_satuan" name="harga1_satuan" value="{{ old('harga1_satuan') }}">
              </div>
              @error('harga1_satuan')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col">
            <div class="mb-3">
              <label for="harga2_satuan" class="form-label">Harga2 Satuan</label>
              <div class="input-group">
                <span class="input-group-text" id="basic-addon1">Rp.</span>
                <input type="text" class="form-control @error('harga2_satuan') is-invalid @enderror"
                  id="harga2_satuan" name="harga2_satuan" value="{{ old('harga2_satuan') }}">
              </div>
              @error('harga2_satuan')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col">
            <div class="mb-3">
              <label for="harga3_satuan" class="form-label">Harga3 Satuan</label>
              <div class="input-group">
                <span class="input-group-text" id="basic-addon1">Rp.</span>
                <input type="text" class="form-control @error('harga3_satuan') is-invalid @enderror"
                  id="harga3_satuan" name="harga3_satuan" value="{{ old('harga3_satuan') }}">
              </div>
              @error('harga3_satuan')
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
              <label for="volume" class="form-label">Volume Barang (cm3) <span class='text-danger'>*</span></label>
              <input type="number" class="form-control @error('volume') is-invalid @enderror" id="volume"
                name="volume" value="{{ old('volume') }}" step=".01">
              @error('volume')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col">
            <div class="mb-3">
              <label for="category" class="form-label">Category Item</label>
              <select class="form-select" name="category">
                @foreach ($categories as $category)
                  @if (old('category') == $category->id)
                    <option value="{{ $category->id }}" selected>{{ $category->nama }}</option>
                  @else
                    <option value="{{ $category->id }}">{{ $category->nama }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col">
            <div class="mb-3">
              <label for="status_enum" class="form-label">Status <span class='text-danger'>*</span></label>
              <select class="form-select" name="status_enum">
                @foreach ($statuses as $key => $val)
                  @if (old('status_enum') == $key)
                    <option value="{{ $key }}" selected>{{ $val }}</option>
                  @else
                    <option value="{{ $key }}">{{ $val }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-6">
            <div class="mb-3">
              <label for="deskripsi" class="form-label">Deskripsi</label>
              <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi">{{ old('deskripsi') }}</textarea>
              @error('deskripsi')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>

          <div class="col-6">
            <div class="form-group">
              <div>
                <label for="gambar" class="form-label">Gambar</label>
                <div class="form-input">
                  <img class="img-preview img-fluid">
                  <input type="file" id="gambar" name="gambar[]" accept="image/*"
                    class="form-control input-gambar @error('gambar') is-invalid @enderror" onchange="prevImg(1)">
                  @error('gambar')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
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
        </div>

        <div class="row justify-content-end mt-4">
          <div class="col-3 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-submit"><span class="iconify me-1 fs-3"
                data-icon="dashicons:database-add"></span>Tambah Data</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  @push('JS')
    <script>
      function prevImg(id) {
        const image = document.getElementsByClassName('input-gambar')[id - 1];
        const imgPreview = document.getElementsByClassName('img-preview')[id - 1];
        imgPreview.style.display = 'block';
        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);
        oFReader.onload = function(OFREvent) {
          imgPreview.src = OFREvent.target.result;
        }
      }

      let countCust = $(".form-group").children().length;
      $(document).on('click', '.add-form', function(e) {
        countCust++;
        $('.form-input').last().clone().appendTo('.form-group');
        $('.form-input').find('.remove-form').removeClass('d-none');
        $('.form-input').last().find('.input-gambar').val('');

        let inputGambarLength = $('.input-gambar').length;
        $('.form-input').last().find('.input-gambar').attr('onchange', 'prevImg(' + inputGambarLength + ')');
        $(".img-preview").last().attr('src', '');

        if (countCust == 1) {
          $('.form-input').find('.remove-form').addClass('d-none');
        }
      })

      $(document).on('click', '.remove-form', function(e) {
        countCust--;
        $(this).parents('.form-input').remove();
        if (countCust == 1) {
          $('.form-input').find('.remove-form').addClass('d-none');
        }
      })
    </script>
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

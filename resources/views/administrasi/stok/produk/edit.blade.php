@extends('layouts/main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/produk">Produk</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ubah</li>
  </ol>
@endsection
@section('main_content')
  <div id="data-produk">
    <div class="px-5 pt-4">
      <form method="POST" id='data-form' action="/administrasi/stok/produk/{{ $item->id }}"
        enctype="multipart/form-data">
        @method('put')
        @csrf

        <div class="row">
          <div class="col">
            <div class="mb-3">
              <label for="nama" class="form-label">Nama <span class='text-danger'>*</span></label>
              <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                value="{{ old('nama', $item->nama) }}">
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
                name="kode_barang" value="{{ old('kode_barang', $item->kode_barang) }}">
              @error('kode_barang')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>

        <input type="hidden" name="oldGambar" value="{{ $item->gambar }}">

        <div class="row">
          <div class="col">
            <div class="mb-3">
              <label for="satuan" class="form-label">Satuan <span class='text-danger'>*</span></label>
              <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan"
                name="satuan" value="{{ old('satuan', $item->satuan) }}">
              @error('satuan')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>

          @php
            $newItems = [];
            foreach ($parentItems as $parentItem) {
                if (strpos($parentItem[0], $item->nama) === false) {
                    array_push($newItems, $parentItem);
                }
            }
          @endphp

          <div class="col">
            <div class="mb-3">
              <label for="link_item" class="form-label">Item yang Dituju (Parent)</label>
              <select class="form-select" name="link_item">
                <option value="">-- Pilih Item --</option>
                @foreach ($newItems as $parentItem)
                  @if (old('link_item', $item->link_item) == $parentItem[1])
                    <option value="{{ $parentItem[1] }}" selected>{{ $parentItem[0] }}</option>
                  @else
                    <option value="{{ $parentItem[1] }}">{{ $parentItem[0] }}</option>
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
              <label for="min_stok" class="form-label">Min Stok</label>
              <input type="text" class="form-control @error('min_stok') is-invalid @enderror" id="min_stok"
                name="min_stok" value="{{ old('min_stok', $item->min_stok) }}">
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
                name="max_stok" value="{{ old('max_stok', $item->max_stok) }}">
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
                <input type="text" class="form-control @error('harga1_satuan') is-invalid @enderror" id="harga1_satuan"
                  name="harga1_satuan" value="{{ old('harga1_satuan', $item->harga1_satuan) }}">
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
                  id="harga2_satuan" name="harga2_satuan" value="{{ old('harga2_satuan', $item->harga2_satuan) }}">
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
                  id="harga3_satuan" name="harga3_satuan" value="{{ old('harga3_satuan', $item->harga3_satuan) }}">
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
              <label for="volume" class="form-label">Volume Barang <span class='text-danger'>*</span></label>
              <input type="number" class="form-control @error('volume') is-invalid @enderror" id="volume"
                name="volume" value="{{ old('volume', $item->volume) }}" step=".01">
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
                  @if (old('status', $item->id_category) == $category->id)
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
              <label for="status" class="form-label">Status</label>
              <select class="form-select" name="status">
                @foreach ($statuses as $status)
                  @if (old('status', $item->status) == $status->id)
                    <option value="{{ $status->id }}" selected>{{ $status->nama }}</option>
                  @else
                    <option value="{{ $status->id }}">{{ $status->nama }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="gambar" class="form-label">Gambar</label>
          <input type="hidden" name="oldImage" value="{{ $item->gambar }}">
          @if ($item->gambar)
            <img src="{{ asset('storage/item/' . $item->gambar) }}" class="img-preview img-fluid d-block">
          @else
            <img class="img-preview img-fluid">
            <p>Belum ada gambar</p>
          @endif
          <input class="form-control @error('gambar') is-invalid @enderror" type="file" id="gambar"
            name="gambar" onchange="prevImg()">
          @error('gambar')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="row justify-content-end mt-4">
          <div class="col-3 d-flex justify-content-end">
            <button type="submit" class="btn btn-warning btn-submit">Edit</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script>
    function prevImg() {
      const image = document.querySelector('#gambar');
      const imgPreview = document.querySelector('.img-preview');

      imgPreview.style.display = 'block';
      const oFReader = new FileReader();
      oFReader.readAsDataURL(image.files[0]);

      oFReader.onload = function(OFREvent) {
        imgPreview.src = OFREvent.target.result;
      }
    }
  </script>
  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

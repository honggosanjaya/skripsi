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

          {{-- @php
            $newItems = [];
            foreach ($parentItems as $parentItem) {
                if (strpos($parentItem[0], $item->nama) === false) {
                    array_push($newItems, $parentItem);
                }
            }
          @endphp --}}

          <div class="col">
            <div class="mb-3">
              <label for="link_item" class="form-label">Item yang Dituju (Parent)</label>
              <select class="form-select" name="link_item">
                <option value="">-- Pilih Item --</option>
                {{-- @foreach ($newItems as $parentItem)
                  @if (old('link_item', $item->link_item) == $parentItem[1])
                    <option value="{{ $parentItem[1] }}" selected>{{ $parentItem[0] }}</option>
                  @else
                    <option value="{{ $parentItem[1] }}">{{ $parentItem[0] }}</option>
                  @endif
                @endforeach --}}

                @foreach ($parentItems as $parentItem)
                  @if (old('link_item', $item->link_item) == $parentItem->id)
                    <option value="{{ $parentItem->id }}" selected>{{ $parentItem->nama }}</option>
                  @else
                    <option value="{{ $parentItem->id }}">{{ $parentItem->nama }}</option>
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
                  @if (old('category', $item->id_category) == $category->id)
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
              <label for="status_enum" class="form-label">Status</label>
              <select class="form-select" name="status_enum">
                @foreach ($statuses as $key => $val)
                  @if (old('status_enum', $item->status_enum) == $key)
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
          <div class="col">
            <div class="mb-3">
              <label for="deskripsi" class="form-label">Deskripsi</label>
              <textarea class="form-control @error('volume') is-invalid @enderror" id="deskripsi" name="deskripsi">{{ old('deskripsi', $item->deskripsi) }}</textarea>
              @error('deskripsi')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <div>
                <label for="gambar" class="form-label">Gambar</label>
                <input type="hidden" name="oldImage" value="{{ $item->gambar }}">

                {{-- <br>
                <label class="form-label">id add</label> --}}
                <input type="hidden" name="listIdGalery" class="input-galery-change">

                {{-- <br>
                <label class="form-label">id remove</label> --}}
                <input type="hidden" name="listIdGaleryRmv" class="input-galery-remove">

                {{-- <br>
                <label class="form-label">first change</label> --}}
                <input type="hidden" name="isFirstPositionChange" class="isFirstPositionChange">

                {{-- <br>
                <label class="form-label">first remove</label> --}}
                <input type="hidden" name="isFirstPositionChangeRmv" class="isFirstPositionChangeRmv">

                @if (count($galeryItems) > 0)
                  @foreach ($galeryItems as $key => $galeryItem)
                    <div class="form-input">
                      <img src="{{ asset('storage/item/' . $galeryItem->image) }}"
                        class="img-preview img-fluid d-block">
                      <input type="file" id="gambar" name="gambar[]"
                        class="form-control input-gambar @error('gambar') is-invalid @enderror"
                        onchange="prevImg({{ $key + 1 }}, {{ $galeryItem->id ?? 0 }})">
                      @error('gambar')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                      <div class="d-flex justify-content-end my-3">
                        <button class="btn btn-danger remove-form-type2 me-3" type="button"
                          onclick="rmvImg({{ $key + 1 }}, {{ $galeryItem->id ?? 0 }}, this)">
                          -
                        </button>
                        <button class="btn btn-success add-form" type="button">
                          +
                        </button>
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="form-input">
                    <img class="img-preview img-fluid">
                    <input type="file" id="gambar" name="gambar[]" accept="image/*"
                      class="form-control input-gambar @error('gambar') is-invalid @enderror" onchange="prevImg(1, 0)"
                      isjustinsert="true">
                    @error('gambar')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                    <div class="d-flex justify-content-end my-3">
                      <button class="btn btn-danger remove-form-type2 me-3" type="button" onclick="rmvImg(1, 0, this)"
                        isjustinsert="true">
                        -
                      </button>
                      {{-- <button class="btn btn-danger remove-form me-3" type="button">
                        -
                      </button> --}}
                      <button class="btn btn-success add-form" type="button">
                        +
                      </button>
                    </div>
                  </div>
                @endif

              </div>
            </div>
          </div>
        </div>

        <div class="row justify-content-end mt-4">
          <div class="col-3 d-flex justify-content-end">
            <button type="submit" class="btn btn-warning btn-submit">Edit</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  @push('JS')
    <script>
      let countCust = $(".form-group .form-input").length;

      if (countCust <= 1) {
        $('.form-input').find('.remove-form').addClass('d-none');
        $('.form-input').find('.remove-form-type2').addClass('d-none');
      }

      function rmvImg(pos, idGalery, thisis) {
        Swal.fire({
          title: 'Apakah anda yakin untuk menghapus gambar ?',
          showDenyButton: true,
          confirmButtonText: 'Ya',
          denyButtonText: `Tidak`,
        }).then((result) => {
          if (result.isConfirmed) {
            countCust--;
            $(thisis).parents('.form-input').remove();

            if (countCust <= 1) {
              $('.form-input').find('.remove-form').addClass('d-none');
              $('.form-input').find('.remove-form-type2').addClass('d-none');
            }

            const attr = $(thisis).attr('isjustinsert');
            // console.log('attr', attr)

            if (attr === undefined || attr === false) {
              if (pos == 1) {
                $('.isFirstPositionChangeRmv').val('true');
              }
              let oldVal = $('.input-galery-remove').val();
              if (oldVal != '') {
                $('.input-galery-remove').val(oldVal + '-' + idGalery);
              } else {
                $('.input-galery-remove').val(idGalery);
              }
            } else if (attr === 'true') {
              if (pos == 1) {
                $('.isFirstPositionChange').val('');
              }

              $('.input-galery-change').val(
                function(index, value) {
                  return value.substr(0, value.length - 1);
                })

              const fullStr = $('.input-galery-change').val();
              const lastChar = fullStr.substr(fullStr.length - 1);

              if (lastChar == '+') {
                $('.input-galery-change').val(
                  function(index, value) {
                    return value.substr(0, value.length - 1);
                  })
              }
            }
          } else if (result.isDenied) {
            Swal.fire('Aksi dibatalkan', '', 'info');
          }
        })
      }

      function prevImg(id, idGalery) {
        if (id == 1) {
          $('.isFirstPositionChange').val('true');
        }
        const image = document.getElementsByClassName('input-gambar')[id - 1];
        const imgPreview = document.getElementsByClassName('img-preview')[id - 1];
        imgPreview.style.display = 'block';
        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);
        oFReader.onload = function(OFREvent) {
          imgPreview.src = OFREvent.target.result;
        }

        if (idGalery == undefined) {
          idGalery = 0;
        }

        let oldVal = $('.input-galery-change').val();
        if (oldVal != '') {
          $('.input-galery-change').val(oldVal + '+' + idGalery);
        } else {
          $('.input-galery-change').val(idGalery);
        }
      }

      $(document).on('click', '.add-form', function(e) {
        countCust++;
        $('.form-input').last().clone().appendTo('.form-group');
        $('.form-input').find('.remove-form').removeClass('d-none');
        $('.form-input').find('.remove-form-type2').removeClass('d-none');
        $('.form-input').last().find('.input-gambar').val('');
        $('.form-input').last().find('.remove-form-type2').attr('isjustinsert', 'true');
        $('.form-input').last().find('.input-gambar').attr('isjustinsert', 'true');

        let inputGambarLength = $('.input-gambar').length;
        $('.form-input').last().find('.input-gambar').attr('onchange', 'prevImg(' + inputGambarLength + ')');
        $(".img-preview").last().attr('src', '');

        if (countCust <= 1) {
          $('.form-input').find('.remove-form').addClass('d-none');
          $('.form-input').find('.remove-form-type2').addClass('d-none');
        }
      })

      $(document).on('click', '.remove-form', function(e) {
        countCust--;
        $(this).parents('.form-input').remove();
        if (countCust <= 1) {
          $('.form-input').find('.remove-form').addClass('d-none');
          $('.form-input').find('.remove-form-type2').addClass('d-none');
        }
      })
    </script>
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection

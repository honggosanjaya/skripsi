@extends('layouts.mainreact')


@push('JS')
  <script>
    @if (session()->has('successMessage'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: "{{ session('successMessage') }}",
        showConfirmButton: false,
        timer: 2000,
      });
    @endif
  </script>
@endpush

@section('main_content')
  <div class="page_container pt-4">
    <button class="btn btn-primary">Lihat Produk</button>

    {{-- {isShowProduct &&
          <div className="retur-product_wrapper my-3 border">
            {Object.keys(latestOrderItems).length > 0 && newHistoryItems.map((item) => (
              <div className="list_history-item p-3" key={item.id_item}>
                <div className="d-flex align-items-center">
                  {item.link_item.gambar ?
                    <img src={`${urlAsset}/storage/item/${item.link_item.gambar}`} className="item_image me-3" />
                    : <img src={`${urlAsset}/images/default_produk.png`} className="item_image me-3" />}
                  <div>
                    <h2 className='fs-6 text-capitalize fw-bold'>{item.link_item.nama ?? null}</h2>
                    {latestOrderItems[item.id_item][0].harga_satuan && <p className="mb-0">
                      {convertPrice(latestOrderItems[item.id_item][0].harga_satuan)}
                    </p>}
                  </div>
                </div>

                <div className="row mt-2 align-items-center">
                  <div className="col-5">
                    <label className="form-label">Jumlah Retur</label>
                  </div>
                  <div className="col-7 d-flex justify-content-around">
                    <button className="btn btn-primary btn_qty"
                      onClick={() => handleKurangJumlah(item.link_item, item.alasan, latestOrderItems[item.id_item][0].harga_satuan)}> - </button>
                    <input type="number" className="form-control mx-2"
                      value={checkifexist(item.link_item)}
                      onChange={(e) => handleValueChange(item.link_item, e.target.value, item.alasan, latestOrderItems[item.id_item][0].harga_satuan)} />
                    <button className="btn btn-primary btn_qty" onClick={() => handleTambahJumlah(item.link_item, item.alasan, latestOrderItems[item.id_item][0].harga_satuan)}> + </button>
                  </div>
                </div>

                <label className="form-label">Alasan Retur</label>
                <input type="text" className="form-control"
                  value={item.alasan ? item.alasan : ''}
                  onChange={(e) => handleAlasanChange(item, e.target.value)}
                />
                {item.error && !item.alasan && <small className="text-danger mb-0">{item.error}</small>}
              </div>
            ))}
          </div>}

        {cartItems.length > 0 && <div className="mt-3">
          <h1 className='fs-5 fw-bold mt-4'>Rincian Retur</h1>
          {cartItems.map((item) => (
            <div className="info-product_retur mt-2" key={item.id}>
              <div className="d-flex align-items-center border-bottom">
                {item.gambar ? <img src={`${urlAsset}/storage/item/${item.gambar}`} className="img-fluid item_image me-3" />
                  : <img src={`${urlAsset}/images/default_produk.png`} className="img-fluid item_image me-3" />}
                <div>
                  <h2 className='fs-6 mb-0 fw-bold'>{item.nama ?? null}</h2>
                  <h5 className='mb-0'>{item.kuantitas ?? null} barang</h5>
                </div>
              </div>
              <span className='title'>Harga Satuan</span><span className='desc'>{item.harga_satuan ?? null}</span>
              <span className='title'>Alasan</span><span className='desc'>{item.alasan ?? null}</span>
            </div>
          ))}
          {cartItems.length > 0 &&
            <Fragment>
              <div className="row justify-content-end mt-4">
                <div className="col d-flex justify-content-end">
                  <button className="btn btn-success mt-3" onClick={handlePengajuanRetur} disabled={isLoading}>
                    <span className="iconify fs-3 me-1" data-icon="ic:baseline-assignment-return"></span>Ajukan Retur
                  </button>
                </div>
              </div>
            </Fragment>
          }
        </div>} --}}

  </div>
@endsection

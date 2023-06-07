@extends('layouts.mainreact')

@push('JS')

@endpush

@section('main_content')
<div class="page_container py-4">

  {id && tipeHarga != '' &&
    <Fragment>
      <button class="btn btn-primary mb-3 me-3" onClick={handletripretur}>
        <span class="iconify fs-4 me-2" data-icon="material-symbols:change-circle-outline-rounded"></span>Retur
      </button>

      <button class="btn btn-purple mb-3" onClick={viewCatalog}>
        <span class="iconify fs-4 me-2" data-icon="carbon:shopping-catalog"></span>Katalog
      </button>
    </Fragment>
  }
  <form>
    <div class={`${errorValidasi.nama ? '' : 'mb-3'}`}>
      <label class="form-label">Nama Customer <span class='text-danger'>*</span></label>
      <input type="text" class="form-control"
        value={namaCust || ''}
        onChange={(e) => setNamaCust(e.target.value)} />
    </div>
    {errorValidasi.nama && <small class="text-danger mb-3">{errorValidasi.nama}</small>}

    <div class={`${errorValidasi.email ? '' : 'mb-3'}`}>
      <label class="form-label">Email Customer</label>
      <input type="email" class="form-control"
        value={email || ''}
        onChange={(e) => setEmail(e.target.value)}
        readOnly={emailInput} />
    </div>
    {errorValidasi.email && <small class="text-danger mb-3">{errorValidasi.email}</small>}

    <div class={`${errorValidasi.alamat_utama ? '' : 'mb-3'}`}>
      <label class="form-label">Alamat Utama <span class='text-danger'>*</span></label>
      <input type="text" class="form-control"
        value={alamatUtama}
        onChange={(e) => setAlamatUtama(e.target.value)} />
    </div>
    {errorValidasi.alamat_utama && <small class="text-danger mb-3">{errorValidasi.alamat_utama}</small>}

    <div class="mb-3">
      <label class="form-label">Alamat Nomor</label>
      <input type="text" class="form-control"
        value={alamatNomor}
        onChange={(e) => setAlamatNomor(e.target.value)} />
    </div>

    <div class="mb-3">
      <label class="form-label">Jenis Customer <span class='text-danger'>*</span></label>
      <select
        value={jenis}
        onChange={(e) => setJenis(e.target.value)}
        class="form-select">
        {showListCustomerType}
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Wilayah</label>
      <Select
        value={selectWilayahValue}
        onChange={handleChange}
        options={showListDistrict}
      />
    </div>

    <div class="mb-3">
      <label class="form-label">Keterangan Alamat</label>
      <textarea class="form-control"
        value={ketAlamat}
        onChange={(e) => setKetAlamat(e.target.value)}></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Telepon</label>
      <input type="text" class="form-control"
        value={telepon}
        onChange={(e) => setTelepon(e.target.value)} />
    </div>

    <div class="mb-3">
      <label class="form-label">Status Telepon</label>
      <div class="input-group mb-3">
        <input type="text" class="form-control"
          value={statusTelepon}
          onChange={(e) => setStatusTelepon(e.target.value)} />
        <button class="btn btn-outline-primary" type="button" onClick={() => setStatusTelepon('WhatsApp (WA)')}>Nomor WA</button>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Durasi Trip <span class='text-danger'>*</span></label>
      <div class="position-relative">
        <input type="number" class="form-control"
          value={durasiTrip}
          onChange={(e) => setDurasiTrip(e.target.value)}
          min='0'
        />
        <span class='satuan'>Hari</span>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Metode Pembayaran <span class='text-danger'>*</span></label>
      <select
        value={metodePembayaran}
        onChange={(e) => setMetodePembayaran(e.target.value)}
        class="form-select">
        <option value="1">Tunai</option>
        <option value="2">Giro</option>
        <option value="3">Transfer</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Jatuh Tempo <span class='text-danger'>*</span></label>
      <div class="position-relative">
        <input type="number" class="form-control"
          value={jatuhTempo}
          onChange={(e) => setJatuhTempo(e.target.value)}
          min='0'
        />
        <span class='satuan'>Hari</span>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Alasan Penolakan</label>
      <textarea class="form-control"
        value={alasanPenolakan}
        onChange={(e) => setAlasanPenolakan(e.target.value)} />
    </div>

    <div class="mb-4">
      <label class="form-label">Foto Tempat Usaha</label>
      {(imagePreviewUrl || prevImage) ? $imagePreview : <p>Belum ada foto</p>}

      <div>
        <button type='button' class='btn btn-primary mb-3' onClick={openGalerry}>
          <span class="iconify fs-3 me-1" data-icon="clarity:image-gallery-solid"></span> Galeri
        </button>

        <button type='button' class='btn btn-secondary ms-3 mb-3' onClick={openCamera}>
          <span class="iconify fs-3 me-1" data-icon="charm:camera"></span>Kamera
        </button>
      </div>

      {isFromGalery && <Fragment>
        {/* {$imagePreview && $imagePreview} */}
        <input
          type="file"
          name="foto"
          id="file"
          accept="image/png, image/jpeg"
          onChange={handleImageChange} />
      </Fragment>}

      {isTakePhoto && dataUri
        ? <Fragment>
          <ImagePreview dataUri={dataUri} />
          <button onClick={takeAgain} class="d-block mx-auto mt-3 btn btn-warning">
            <span class="iconify fs-3 me-1" data-icon="ic:sharp-flip-camera-android"></span>Ulang
          </button>
        </Fragment>
        : isTakePhoto
          ? <Fragment>
            <Webcam
              audio={false}
              height={340}
              width={350}
              ref={webcamRef}
              screenshotFormat="image/png"
              videoConstraints={{
                ...videoConstraints,
                facingMode
              }}
            />

            <div class="d-flex justify-content-between">
              <button type='button' class='btn btn-warning' onClick={handleClick}>
                <span class="iconify fs-3 me-1" data-icon="ic:sharp-flip-camera-android"></span>Switch camera
              </button>
              <button type="button" class='btn btn-success' onClick={capture}>
                <span class="iconify fs-3 me-1" data-icon="charm:camera"></span>Capture
              </button>
            </div>
          </Fragment>
          : null
      }
    </div>

    <div class="d-flex justify-content-end">
      {alasanPenolakan ? <button class="btn btn-danger me-3" onClick={kirimCustomer} disabled={shouldDisabled}>
        Selesai dan Keluar
      </button> : <button class="btn btn-danger me-3" disabled={true}>
        Selesai dan Keluar
      </button>}

      <button class="btn btn-success" onClick={handleOrder} disabled={shouldDisabled}>
        <span class="iconify me-1" data-icon="carbon:ibm-watson-orders"></span>Order
      </button>
    </div>
  </form>
</div>
@endsection

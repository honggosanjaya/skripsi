import React, { Component, useState } from 'react';
import HeaderSales from './HeaderSales';


const TripSales = () => {
  const [namaCust, setNamaCust] = useState('');
  const [jenisCust, setJenisCust] = useState('');
  const [wilayah, setWilayah] = useState('');
  const [alamatUtama, setAlamatUtama] = useState('');
  const [alamatNomor, setAlamatNomor] = useState('');
  const [ketAlamat, setKetAlamat] = useState('');
  const [telepon, setTelepon] = useState('');
  const [durasiTrip, setDurasiTrip] = useState('');
  const [totalTrip, setTotalTrip] = useState('');
  const [file, setFile] = useState();
  const [prevImage, setPrevImage] = useState('');
  const [imagePreviewUrl, setImagePreviewUrl] = useState('');

  const handleImageChange = (e) => {
    e.preventDefault();
    let reader = new FileReader();
    let file = e.target.files[0];
    reader.onloadend = () => {
      setFile(file);
      setImagePreviewUrl(reader.result);
    };
    reader.readAsDataURL(file);
  };

  let $imagePreview = null;
  if (imagePreviewUrl) {
    $imagePreview = <img src={imagePreviewUrl} className="preview_tempatUsaha" />
  } else {
    // if(prevImage){
    //   let image = prevImage.replace('public', '');
    //   $imagePreview = <img src={image} className="preview_tempatUsaha" />
    // }
  }

  return (
    <main className="page_main">
      <HeaderSales title="Trip" />

      <div className="page_container pt-4">
        <form>
          <div className="mb-3">
            <label className="form-label">Nama Customer</label>
            <input type="text" className="form-control" value={namaCust} onChange={setNamaCust} />
          </div>
          <div className="mb-3">
            <label className="form-label">Jenis Customer</label>
            <input type="text" className="form-control" value={jenisCust} onChange={setJenisCust} />
          </div>
          <div className="mb-3">
            <label className="form-label">Wilayah</label>
            <input type="text" className="form-control" value={wilayah} onChange={setWilayah} />
          </div>
          <div className="mb-3">
            <label className="form-label">Alamat Utama</label>
            <input type="text" className="form-control" value={alamatUtama} onChange={setAlamatUtama} />
          </div>
          <div className="mb-3">
            <label className="form-label">Alamat Nomor</label>
            <input type="text" className="form-control" value={alamatNomor} onChange={setAlamatNomor} />
          </div>
          <div className="mb-3">
            <label className="form-label">Keterangan Alamat</label>
            <textarea className="form-control" value={ketAlamat} onChange={setKetAlamat}></textarea>
          </div>
          <div className="mb-3">
            <label className="form-label">Telepon</label>
            <input type="text" className="form-control" value={telepon} onChange={setTelepon} />
          </div>
          <div className="mb-3">
            <label className="form-label">Durasi Trip</label>
            <div className="position-relative">
              <input type="text" className="form-control" value={durasiTrip} onChange={setDurasiTrip} />
              <span className='satuan'>Hari</span>
            </div>
          </div>
          <div className="mb-3">
            <label className="form-label">Total Trip</label>
            <input type="text" className="form-control" value={totalTrip} readOnly disabled />
          </div>

          <div className="mb-5">
            <label className="form-label">Foto Tempat Usaha</label>
            {$imagePreview}
            <input
              type="file"
              name="file"
              id="file"
              accept="image/png, image/jpeg"
              onChange={handleImageChange} />
          </div>

          <div className="trip_aksi">
            <button type="submit" className="btn btn-danger me-3">Keluar</button>
            <button type="submit" className="btn btn-success">Order</button>
          </div>
        </form>
      </div>

    </main>
  );
}

export default TripSales;
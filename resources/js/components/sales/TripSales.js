import axios from 'axios';
import React, { Component, useState } from 'react';
import HeaderSales from './HeaderSales';


const TripSales = () => {
  const appUrl = process.env.MIX_APP_URL;
  const [namaCust, setNamaCust] = useState('');
  const [jenis, setJenis] = useState('1');
  const [wilayah, setWilayah] = useState('2');
  const [alamatUtama, setAlamatUtama] = useState('');
  const [alamatNomor, setAlamatNomor] = useState('');
  const [ketAlamat, setKetAlamat] = useState('');
  const [telepon, setTelepon] = useState('');
  const [durasiTrip, setDurasiTrip] = useState('');
  const [totalTrip, setTotalTrip] = useState('');
  const [file, setFile] = useState(null);
  const [prevImage, setPrevImage] = useState('');
  const [imagePreviewUrl, setImagePreviewUrl] = useState('');
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);

  const kirimCustomer = (e) => {
    e.preventDefault();
    let formData = new FormData();
    formData.append("foto", file);

    axios.all([
      axios.post(`${window.location.origin}/api/tripCustomer`, {
        nama: namaCust,
        id_jenis: jenis,
        id_wilayah: wilayah,
        alamat_utama: alamatUtama,
        alamat_nomor: alamatNomor,
        keterangan_alamat: ketAlamat,
        telepon: telepon,
        durasi_kunjungan: durasiTrip,
        counter_to_effective_call: totalTrip,
      }, {
        headers: {
          Accept: "application/json",
        },
      }),
      axios.post(`${window.location.origin}/api/tripCustomer/foto`,
        formData, {
        headers: {
          Accept: "application/json",
        }
      })
    ])
      .then(axios.spread((data1, data2) => {
        console.log(data1);
        console.log(data2);
      }))
      .catch((error) => {
        console.log(error.message);
      });
  }

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
        <form onSubmit={kirimCustomer}>
          <div className="mb-3">
            <label className="form-label">Nama Customer</label>
            <input type="text" className="form-control"
              value={namaCust || ''}
              onChange={(e) => setNamaCust(e.target.value)} />
          </div>
          <div className="mb-3">
            <label className="form-label">Alamat Utama</label>
            <input type="text" className="form-control"
              value={alamatUtama}
              onChange={(e) => setAlamatUtama(e.target.value)} />
          </div>
          <div className="mb-3">
            <label className="form-label">Alamat Nomor</label>
            <input type="text" className="form-control"
              value={alamatNomor}
              onChange={(e) => setAlamatNomor(e.target.value)} />
          </div>

          <div className="mb-3">
            <label className="form-label">Jenis Customer</label>
            <select
              value={jenis}
              onChange={(e) => setJenis(e.target.value)}>
              <option value="1">Satu</option>
              <option value="2">dua</option>
            </select>
          </div>
          <div className="mb-3">
            <label className="form-label">Wilayah</label>
            <select
              value={wilayah}
              onChange={(e) => setWilayah(e.target.value)}>
              <option value="1">Satu</option>
              <option value="2">dua</option>
            </select>
          </div>

          <div className="mb-3">
            <label className="form-label">Keterangan Alamat</label>
            <textarea className="form-control"
              value={ketAlamat}
              onChange={(e) => setKetAlamat(e.target.value)}></textarea>
          </div>
          <div className="mb-3">
            <label className="form-label">Telepon</label>
            <input type="text" className="form-control"
              value={telepon}
              onChange={(e) => setTelepon(e.target.value)} />
          </div>
          <div className="mb-3">
            <label className="form-label">Durasi Trip</label>
            <div className="position-relative">
              <input type="text" className="form-control"
                value={durasiTrip}
                onChange={(e) => setDurasiTrip(e.target.value)} />
              <span className='satuan'>Hari</span>
            </div>
          </div>
          <div className="mb-3">
            <label className="form-label">Total</label>
            <input type="text" className="form-control"
              value={totalTrip}
              onChange={(e) => setTotalTrip(e.target.value)} />
          </div>

          <div className="mb-5">
            <label className="form-label">Foto Tempat Usaha</label>
            {$imagePreview}
            <input
              type="file"
              name="foto"
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
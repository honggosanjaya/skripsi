import axios from 'axios';
import React, { Component, useEffect, useState, useContext } from 'react';
import HeaderSales from './HeaderSales';
import { render } from "react-dom";
import { useNavigate, useParams } from 'react-router-dom'
import { useHistory } from 'react-router-dom';


const TripSales = () => {
  const { id } = useParams();
  const history = useHistory();
  const [namaCust, setNamaCust] = useState('');
  const [jenis, setJenis] = useState('1');
  const [wilayah, setWilayah] = useState('2');
  const [alamatUtama, setAlamatUtama] = useState('');
  const [alamatNomor, setAlamatNomor] = useState('');
  const [ketAlamat, setKetAlamat] = useState('');
  const [telepon, setTelepon] = useState('');
  const [durasiTrip, setDurasiTrip] = useState('');
  const [email, setEmail] = useState('');
  const [totalTripEC, setTotalTripEC] = useState(0);
  const [trip, setTrip] = useState('trip');
  const [alasanPenolakan, setAlasanPenolakan] = useState('');
  const [jamMasuk, setJamMasuk] = useState(Date.now() / 1000);
  const [koordinat, setKoordinat] = useState('');
  const [file, setFile] = useState(null);
  const [prevImage, setPrevImage] = useState('');
  const [imagePreviewUrl, setImagePreviewUrl] = useState('');
  const [error, setError] = useState(null);
  const [success, setSuccess] = useState(null);
  const [emailInput, setEmailInput] = useState(false);
  const [districtArr, setDistrictArr] = useState([]);
  const [customerTypeArr, setCustomerTypeArr] = useState([]);
  const [showListCustomerType, setShowListCustomerType] = useState([]);
  const [showListDistrict, setShowListDistrict] = useState([]);

  const Swal = require('sweetalert2')
  let $imagePreview = null;

  const kirimCustomer = (e) => {
    e.preventDefault();
    let formData = new FormData();
    formData.append("foto", file);

    axios({
      method: "post",
      url: `${window.location.origin}/api/tripCustomer`,
      headers: {
        Accept: "application/json",
      },
      data: {
        id: id ?? null,
        nama: namaCust,
        id_jenis: jenis,
        id_wilayah: wilayah,
        alamat_utama: alamatUtama,
        email: email,
        alamat_nomor: alamatNomor,
        keterangan_alamat: ketAlamat,
        telepon: telepon,
        durasi_kunjungan: durasiTrip,
        counter_to_effective_call: totalTripEC,
        jam_masuk: jamMasuk,
        alasan_penolakan: alasanPenolakan,
        status: trip,
        koordinat: koordinat,
      }
    })
      .then(response => {
        // console.log(response.data.data);
        setCustomer(response.data.data);
        return response.data.data;
      })
      .then(dataSatu => axios.post(`${window.location.origin}/api/tripCustomer/foto/${dataSatu.id}`,
        formData, {
        headers: {
          Accept: "application/json",
        }
      }))
      .then(response => {
        Swal.fire({
          title: 'success',
          text: 'berhasil menyimpan data, ayo tetap semangat bekerja',
          icon: 'success',
          confirmButtonText: 'next trip'
        }).then((result) => {
          // this.props.history.push('/salesman/trip')
          window.location = "/salesman"
          console.log(result)
        })
      })
      .catch(error => {
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

  useEffect(() => {
    navigator.geolocation.getCurrentPosition(function (position) {
      setKoordinat(position.coords.latitude + '@' + position.coords.longitude)
    });
    if (id != null) {
      // console.log(id);
      axios.get(`${window.location.origin}/api/tripCustomer/${id}`).then(response => {
        console.log(response.data.data);
        setNamaCust(response.data.data.nama)
        setJenis(response.data.data.id_jenis)
        setWilayah(response.data.data.id_wilayah)
        setAlamatUtama(response.data.data.alamat_utama)
        setAlamatNomor(response.data.data.alamat_nomor)
        setEmail(response.data.data.email)
        setEmailInput(response.data.data.email != null ? true : false)
        setKetAlamat(response.data.data.keterangan_alamat)
        setTelepon(response.data.data.telepon)
        setDurasiTrip(response.data.data.durasi_kunjungan)
        setTotalTripEC(response.data.data.counter_to_effective_call)
        setImagePreviewUrl(response.data.data.image_url)

        return response.data.data;
      })
    }
    axios.get(`${window.location.origin}/api/dataFormTrip/`).then(response => {
      console.log(response.data);
      setDistrictArr(response.data.district)
      setCustomerTypeArr(response.data.customerType)

      return response.data.data;
    })
  }, [])

  useEffect(() => {
    setShowListCustomerType(customerTypeArr?.map((data, index) => {
      return (
        <option value={data.id} key={index}>{data.nama}</option>
      );
    }))
    console.log(districtArr);

    setShowListDistrict(districtArr?.map((data, index) => {
      return (
        <option value={data.id} key={index}>{data.nama}</option>
      );
    }))
  }, [customerTypeArr, districtArr])

  if (imagePreviewUrl) {
    $imagePreview = <img src={imagePreviewUrl} className="preview_tempatUsaha" />
  } else {
    // if(prevImage){
    //   let image = prevImage.replace('public', '');
    //   $imagePreview = <img src={image} className="preview_tempatUsaha" />
    // }
  }

  const handleOrder = () => {
    history.push(`/salesman/order/${id}`);
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
            <label className="form-label">Email Customer</label>
            <input type="email" className="form-control"
              value={email || ''}
              onChange={(e) => setEmail(e.target.value)}
              readOnly={emailInput} />
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
              onChange={(e) => setJenis(e.target.value)}
              className="form-select">
              {showListCustomerType}
            </select>
          </div>
          <div className="mb-3">
            <label className="form-label">Wilayah</label>
            <select className="form-select"
              value={wilayah}
              onChange={(e) => setWilayah(e.target.value)}>
              {showListDistrict}
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
              <input type="number" className="form-control"
                value={durasiTrip}
                onChange={(e) => setDurasiTrip(e.target.value)} />
              <span className='satuan'>Hari</span>
            </div>
          </div>
          <div className="mb-3">
            <label className="form-label">penolakan</label>
            <textarea className="form-control"
              value={alasanPenolakan}
              onChange={(e) => setAlasanPenolakan(e.target.value)} />
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
            <button type="submit" className="btn btn-success" onClick={handleOrder}>Order</button>
          </div>
        </form>
      </div>

    </main>
  );
}

export default TripSales;
import axios from 'axios';
import React, { useEffect, useState, useContext } from 'react';
import HeaderSales from './HeaderSales';
import { useParams } from 'react-router-dom'
import { useHistory } from 'react-router-dom';
import { UserContext } from '../../contexts/UserContext';
import AlertComponent from '../reuse/AlertComponent';
import urlAsset from '../../config';

const TripSales = () => {
  const { id } = useParams();
  const history = useHistory();
  const { dataUser } = useContext(UserContext);

  const [namaCust, setNamaCust] = useState('');
  const [email, setEmail] = useState('');
  const [alamatUtama, setAlamatUtama] = useState('');
  const [alamatNomor, setAlamatNomor] = useState('');
  const [jenis, setJenis] = useState('1');
  const [wilayah, setWilayah] = useState('2');
  const [ketAlamat, setKetAlamat] = useState('');
  const [telepon, setTelepon] = useState('');
  const [durasiTrip, setDurasiTrip] = useState(7);
  const [alasanPenolakan, setAlasanPenolakan] = useState('');
  const [file, setFile] = useState(null);
  const [prevImage, setPrevImage] = useState(null);
  const [imagePreviewUrl, setImagePreviewUrl] = useState('');

  const [error, setError] = useState(null);
  const [errorValidasi, setErrorValidasi] = useState([]);

  const [totalTripEC, setTotalTripEC] = useState(0);
  const [koordinat, setKoordinat] = useState('');
  const [emailInput, setEmailInput] = useState(false);
  const [districtArr, setDistrictArr] = useState([]);
  const [customerTypeArr, setCustomerTypeArr] = useState([]);
  const [showListCustomerType, setShowListCustomerType] = useState([]);
  const [showListDistrict, setShowListDistrict] = useState([]);
  const Swal = require('sweetalert2');
  const jamMasuk = Date.now() / 1000;

  useEffect(() => {
    navigator.geolocation.getCurrentPosition(function (position) {
      setKoordinat(position.coords.latitude + '@' + position.coords.longitude)
    });
    if (id != null) {
      axios.get(`${window.location.origin}/api/tripCustomer/${id}`).then(response => {
        console.log('cust', response.data.data)
        setNamaCust(response.data.data.nama);
        setJenis(response.data.data.id_jenis);
        setWilayah(response.data.data.id_wilayah);
        setAlamatUtama(response.data.data.alamat_utama);
        setAlamatNomor(response.data.data.alamat_nomor == null ? '' : response.data.data.alamat_nomor);
        setEmail(response.data.data.email == null ? '' : response.data.data.email);
        setEmailInput(response.data.data.email != null ? true : false);
        setKetAlamat(response.data.data.keterangan_alamat == null ? '' : response.data.data.keterangan_alamat);
        setTelepon(response.data.data.telepon == null ? '' : response.data.data.telepon);
        setDurasiTrip(response.data.data.durasi_kunjungan);
        setTotalTripEC(response.data.data.counter_to_effective_call);
        setPrevImage(response.data.data.foto);
      })
    }
    axios.get(`${window.location.origin}/api/dataFormTrip/`).then(response => {
      console.log(response.data);
      setDistrictArr(response.data.district)
      setCustomerTypeArr(response.data.customerType)
    })
  }, [])

  useEffect(() => {
    setShowListCustomerType(customerTypeArr?.map((data, index) => {
      return (
        <option value={data.id} key={index}>{data.nama}</option>
      );
    }))

    setShowListDistrict(districtArr?.map((data, index) => {
      return (
        <option value={data.id} key={index}>{data.nama}</option>
      );
    }))
  }, [customerTypeArr, districtArr]);

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

  let objData = {
    id: id ?? null,
    id_jenis: jenis,
    id_staff: dataUser.id_staff,
    id_wilayah: wilayah,
    nama: namaCust,
    email: email,
    alamat_utama: alamatUtama,
    alamat_nomor: alamatNomor,
    keterangan_alamat: ketAlamat,
    koordinat: koordinat,
    telepon: telepon,
    durasi_kunjungan: durasiTrip,
    counter_to_effective_call: totalTripEC,
    jam_masuk: jamMasuk,
    alasan_penolakan: alasanPenolakan,
  }

  const kirimCustomer = (e) => {
    e.preventDefault();
    let formData = new FormData();
    formData.append("foto", file);
    objData["status"] = "trip";
    axios({
      method: "post",
      url: `${window.location.origin}/api/tripCustomer`,
      headers: {
        Accept: "application/json",
      },
      data: objData
    })
      .then(response => {
        setError(null);
        if (response.data.status == 'success') {
          setErrorValidasi([]);
          return response.data.data;
        } else {
          setErrorValidasi(response.data.validate_err);
          throw Error("Error validasi");
        }
      })
      .then(dataCustomer => axios.post(`${window.location.origin}/api/tripCustomer/foto/${dataCustomer.id}`,
        formData, {
        headers: {
          Accept: "application/json",
        }
      }))
      .then((response) => {
        setError(null);
        Swal.fire({
          title: 'success',
          text: response.data.message,
          icon: 'success',
          confirmButtonText: 'Trip Selanjutnya'
        }).then((result) => {
          history.push('/salesman');
        })
      })
      .catch(error => {
        setError(error.message);
      });
  }

  const handleOrder = (e) => {
    e.preventDefault();
    let formData = new FormData();
    formData.append("foto", file);
    objData["status"] = "order";
    axios({
      method: "post",
      url: `${window.location.origin}/api/tripCustomer`,
      headers: {
        Accept: "application/json",
      },
      data: objData
    })
      .then(response => {
        setError(null);
        if (response.data.status == 'success') {
          setErrorValidasi([]);
          return response.data.data;
        }
        else {
          setErrorValidasi(response.data.validate_err);
          throw Error("Error validasi");
        }
      })
      .then(dataCustomer => axios.post(`${window.location.origin}/api/tripCustomer/foto/${dataCustomer.id}`,
        formData, {
        headers: {
          Accept: "application/json",
        }
      }))
      .then((dataCustomer) => {
        setError(null);
        history.push(`/salesman/order/${dataCustomer.data.data.id}`);
      })
      .catch(error => {
        setError(error.message);
      });
  }

  let $imagePreview = null;
  if (imagePreviewUrl) {
    $imagePreview = <img src={imagePreviewUrl} className="preview_tempatUsaha" />
  } else {
    if (prevImage) {
      let image = prevImage.replace('public', '');
      $imagePreview = <img src={`${urlAsset}/storage/customer/${image}`} className="preview_tempatUsaha" />
    }
  }

  return (
    <main className="page_main">
      <HeaderSales title="Trip" />
      <div className="page_container pt-4">
        {error && <AlertComponent errorMsg={error} />}
        <form>
          <div className={`${errorValidasi.nama ? '' : 'mb-3'}`}>
            <label className="form-label">Nama Customer</label>
            <input type="text" className="form-control"
              value={namaCust || ''}
              onChange={(e) => setNamaCust(e.target.value)} />
          </div>
          {errorValidasi.nama && <small className="text-danger mb-3">{errorValidasi.nama}</small>}

          <div className={`${errorValidasi.email ? '' : 'mb-3'}`}>
            <label className="form-label">Email Customer</label>
            <input type="email" className="form-control"
              value={email || ''}
              onChange={(e) => setEmail(e.target.value)}
              readOnly={emailInput} />
          </div>
          {errorValidasi.email && <small className="text-danger mb-3">{errorValidasi.email}</small>}

          <div className={`${errorValidasi.alamat_utama ? '' : 'mb-3'}`}>
            <label className="form-label">Alamat Utama</label>
            <input type="text" className="form-control"
              value={alamatUtama}
              onChange={(e) => setAlamatUtama(e.target.value)} />
          </div>
          {errorValidasi.alamat_utama && <small className="text-danger mb-3">{errorValidasi.alamat_utama}</small>}

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
                onChange={(e) => setDurasiTrip(e.target.value)}
                min='0'
              />
              <span className='satuan'>Hari</span>
            </div>
          </div>

          <div className="mb-3">
            <label className="form-label">Alasan Penolakan</label>
            <textarea className="form-control"
              value={alasanPenolakan}
              onChange={(e) => setAlasanPenolakan(e.target.value)} />
          </div>

          <div className="mb-5">
            <label className="form-label">Foto Tempat Usaha</label>
            {(imagePreviewUrl || prevImage) ? $imagePreview : <p>Belum ada foto</p>}
            <input
              type="file"
              name="foto"
              id="file"
              accept="image/png, image/jpeg"
              onChange={handleImageChange} />
          </div>

          <div className="trip_aksi">
            <button className="btn btn-danger me-3" onClick={kirimCustomer}>Keluar</button>
            <button className="btn btn-success" onClick={handleOrder}>
              <span className="iconify me-1" data-icon="carbon:ibm-watson-orders"></span>Order
            </button>
          </div>
        </form>
      </div>
    </main>
  );
}

export default TripSales;
import React, { useEffect, useState, useContext } from 'react';
import axios from 'axios';
import HeaderSales from './HeaderSales';
import { useParams, useHistory, Link } from 'react-router-dom'
import { UserContext } from '../../contexts/UserContext';
import AlertComponent from '../reuse/AlertComponent';
import urlAsset from '../../config';
import LoadingIndicator from '../reuse/LoadingIndicator';

const TripSales = () => {
  const { id } = useParams();
  const history = useHistory();
  const { dataUser } = useContext(UserContext);
  const [namaCust, setNamaCust] = useState('');
  const [email, setEmail] = useState('');
  const [alamatUtama, setAlamatUtama] = useState('');
  const [alamatNomor, setAlamatNomor] = useState('');
  const [jenis, setJenis] = useState('');
  const [wilayah, setWilayah] = useState('');
  const [ketAlamat, setKetAlamat] = useState('');
  const [telepon, setTelepon] = useState('');
  const [durasiTrip, setDurasiTrip] = useState(7);
  const [alasanPenolakan, setAlasanPenolakan] = useState('');
  const [file, setFile] = useState(null);
  const [prevImage, setPrevImage] = useState(null);
  const [imagePreviewUrl, setImagePreviewUrl] = useState('');
  const [jatuhTempo, setJatuhTempo] = useState(7);
  const [error, setError] = useState(null);
  const [errorValidasi, setErrorValidasi] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const [totalTripEC, setTotalTripEC] = useState(0);
  const [koordinat, setKoordinat] = useState('');
  const [emailInput, setEmailInput] = useState(false);
  const [districtArr, setDistrictArr] = useState([]);
  const [customerTypeArr, setCustomerTypeArr] = useState([]);
  const [showListCustomerType, setShowListCustomerType] = useState([]);
  const [showListDistrict, setShowListDistrict] = useState([]);
  const Swal = require('sweetalert2');
  const jamMasuk = Date.now() / 1000;
  const [shouldDisabled, setShouldDisabled] = useState(false);
  const [metodePembayaran, setMetodePembayaran] = useState('1');

  useEffect(() => {
    navigator.permissions.query({ name: 'geolocation' }).then((result) => {
      if (result.state === 'prompt') {
        let timerInterval;
        let seconds = 7;
        Swal.fire({
          title: 'Peringatan Izin Akses Lokasi Perangkat',
          html: 'Selanjutnya kami akan meminta akses lokasi anda, mohon untuk mengizinkannya. <br><br> Tunggu <b></b> detik untuk menutupnya',
          icon: 'info',
          allowOutsideClick: false,
          allowEscapeKey: false,
          confirmButtonText: 'Tutup',
          didOpen: () => {
            Swal.showLoading();
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval = setInterval(() => {
              if (seconds > 0) {
                seconds -= 1;
              }
              b.textContent = seconds;
            }, 1000);
            setTimeout(() => { Swal.hideLoading() }, 7000);
          },
        }).then((result) => {
          navigator.geolocation.getCurrentPosition(function (position) {
            setKoordinat(position.coords.latitude + '@' + position.coords.longitude)
          });
        })
      } else if (result.state === 'granted') {
        navigator.geolocation.getCurrentPosition(function (position) {
          setKoordinat(position.coords.latitude + '@' + position.coords.longitude)
        });
      } else if (result.state === 'denied') {
        let timerInterval2;
        let seconds2 = 4;
        Swal.fire({
          title: 'Tidak Ada Akses Lokasi Perangkat',
          html: 'Agar memudahkan kunjungan silahkan buka pengaturan browser anda dan ijinkan aplikasi mengakses lokasi. <br><br> Tunggu <b></b> detik untuk menutupnya',
          icon: 'info',
          allowOutsideClick: false,
          allowEscapeKey: false,
          confirmButtonText: 'Tutup',
          didOpen: () => {
            Swal.showLoading();
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval2 = setInterval(() => {
              if (seconds2 > 0) {
                seconds2 -= 1;
              }
              b.textContent = seconds2;
            }, 1000);
            setTimeout(() => { Swal.hideLoading() }, 4000);
          },
        })
      }
    });

    if (id != null) {
      setShouldDisabled(true);
      axios.get(`${window.location.origin}/api/tripCustomer/${id}`).then(response => {
        console.log('cust', response.data.data);
        setShouldDisabled(false);
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
        if (response.data.data.jatuh_tempo != null) {
          setJatuhTempo(response.data.data.jatuh_tempo);
        }
        if (response.data.data.metode_pembayaran != null) {
          setMetodePembayaran(response.data.data.metode_pembayaran);
        }
        setTotalTripEC(response.data.data.counter_to_effective_call);
        setPrevImage(response.data.data.foto);
      })
    }
    setShouldDisabled(true);
    axios.get(`${window.location.origin}/api/dataFormTrip/`).then(response => {
      console.log('custtype', response.data);
      setShouldDisabled(false);
      setDistrictArr(response.data.district);
      setCustomerTypeArr(response.data.customerType);
      setJenis(response.data.customerType[0].id);
      setWilayah(response.data.district[0].id);
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
    jatuh_tempo: jatuhTempo,
    metode_pembayaran: metodePembayaran
  }

  const kirimCustomer = (e) => {
    e.preventDefault();
    Swal.fire({
      title: 'Apakah anda yakin?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Keluar!'
    }).then((result) => {
      if (result.isConfirmed) {
        let formData = new FormData();
        formData.append("foto", file);
        objData["status_enum"] = "trip";
        setIsLoading(true);
        setShouldDisabled(true);
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
            setShouldDisabled(false);
            setIsLoading(false);
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
            setIsLoading(false);
            setShouldDisabled(false);
          });
      }
    })
  }

  const handleOrder = (e) => {
    e.preventDefault();
    Swal.fire({
      title: 'Apakah anda yakin?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Order!'
    }).then((result) => {
      if (result.isConfirmed) {
        setIsLoading(true);
        setShouldDisabled(true);
        let formData = new FormData();
        formData.append("foto", file);
        objData["status_enum"] = "order";
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
              // throw Error("Error validasi");
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
            setIsLoading(false);
            setShouldDisabled(false);
            history.push(`/salesman/order/${dataCustomer.data.data.id}`);
          })
          .catch(error => {
            setError(error.message);
            setIsLoading(false);
            setShouldDisabled(false);
          });
      }
    })
  }

  let $imagePreview = null;
  if (imagePreviewUrl) {
    $imagePreview = <img src={imagePreviewUrl} className="img-fluid img_prev" />
  } else {
    if (prevImage) {
      let image = prevImage.replace('public', '');
      $imagePreview = <img src={`${urlAsset}/storage/customer/${image}`} className="img-fluid img_prev" />
    }
  }

  const handletripretur = () => {
    history.push({
      pathname: `/lapangan/retur/${id}`,
      state: { isTrip: true }
    })
  }

  const goBack = () => {
    history.push('/salesman');
  }

  return (
    <main className="page_main">
      <HeaderSales title="Trip" toBack={goBack} />
      <div className="page_container py-4">
        {error && <AlertComponent errorMsg={error} />}
        {isLoading && <LoadingIndicator />}
        {id && <button className="btn btn-primary mb-3" onClick={handletripretur}>
          <span className="iconify fs-4 me-2" data-icon="material-symbols:change-circle-outline-rounded"></span>Retur
        </button>}
        <form>
          <div className={`${errorValidasi.nama ? '' : 'mb-3'}`}>
            <label className="form-label">Nama Customer <span className='text-danger'>*</span></label>
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
            <label className="form-label">Alamat Utama <span className='text-danger'>*</span></label>
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
            <label className="form-label">Jenis Customer <span className='text-danger'>*</span></label>
            <select
              value={jenis}
              onChange={(e) => setJenis(e.target.value)}
              className="form-select">
              {showListCustomerType}
            </select>
          </div>

          <div className="mb-3">
            <label className="form-label">Wilayah <span className='text-danger'>*</span></label>
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
            <label className="form-label">Durasi Trip <span className='text-danger'>*</span></label>
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
            <label className="form-label">Metode Pembayaran <span className='text-danger'>*</span></label>
            <select
              value={metodePembayaran}
              onChange={(e) => setMetodePembayaran(e.target.value)}
              className="form-select">
              <option value="1">Tunai</option>
              <option value="2">Giro</option>
              <option value="3">Transfer</option>
            </select>
          </div>

          <div className="mb-3">
            <label className="form-label">Jatuh Tempo <span className='text-danger'>*</span></label>
            <div className="position-relative">
              <input type="number" className="form-control"
                value={jatuhTempo}
                onChange={(e) => setJatuhTempo(e.target.value)}
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

          <div className="mb-4">
            <label className="form-label">Foto Tempat Usaha</label>
            {(imagePreviewUrl || prevImage) ? $imagePreview : <p>Belum ada foto</p>}
            <input
              type="file"
              name="foto"
              id="file"
              accept="image/png, image/jpeg"
              onChange={handleImageChange} />
          </div>

          <div className="d-flex justify-content-end">
            {alasanPenolakan ? <button className="btn btn-danger me-3" onClick={kirimCustomer} disabled={shouldDisabled}>
              Selesai dan Keluar
            </button> : <button className="btn btn-danger me-3" disabled={true}>
              Selesai dan Keluar
            </button>}

            <button className="btn btn-success" onClick={handleOrder} disabled={shouldDisabled}>
              <span className="iconify me-1" data-icon="carbon:ibm-watson-orders"></span>Order
            </button>
          </div>
        </form>
      </div>
    </main >
  );
}

export default TripSales;
import React, { useState, useContext, useEffect, Fragment } from 'react';
import { useHistory } from "react-router";
import axios from 'axios';
import HeaderSales from './HeaderSales';
import { UserContext } from '../../contexts/UserContext';
import ButtonGroup from 'react-bootstrap/ButtonGroup';
import ToggleButton from 'react-bootstrap/ToggleButton';
import LoadingIndicator from '../reuse/LoadingIndicator';
import urlAsset from '../../config';
import { convertPrice } from '../reuse/HelperFunction';
import Modal from 'react-bootstrap/Modal';

const Reimbursement = () => {
  const history = useHistory();
  const { dataUser } = useContext(UserContext);
  const [tabActive, setTabActive] = useState('history');
  const [jumlahUang, setJumlahUang] = useState('');
  const [keteranganPengajuan, setKeteranganPengajuan] = useState('');
  const [cashAccount, setCashAccount] = useState('1');
  const [file, setFile] = useState(null);
  const [imagePreviewUrl, setImagePreviewUrl] = useState('');
  const [showCashAccountOption, setShowCashAccountOption] = useState([]);
  const [listCashAccount, setListCashAccount] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const [historyReimbursement, setHistoryReimbursement] = useState([]);
  const [activeModal, setActiveModal] = useState(null);
  const [show, setShow] = useState(false);

  const Swal = require('sweetalert2');
  let $imagePreview = null;

  const radios = [
    { name: 'History', value: 'history' },
    { name: 'Submission', value: 'submission' },
  ];


  useEffect(() => {
    axios.get(`${window.location.origin}/api/cashAcountOption`).then(response => {
      console.log('cashaccount', response.data);
      setListCashAccount(response.data.cashaccount);
    })
  }, [])

  const getHistoryReimbursement = () => {
    if (dataUser.id_staff != undefined) {
      axios.get(`${window.location.origin}/api/historyReimbursement/${dataUser.id_staff}`).then(response => {
        console.log('reimbursement', response.data);
        setHistoryReimbursement(response.data.data);
      })
    }
  }

  useEffect(() => {
    getHistoryReimbursement();
  }, [dataUser])

  useEffect(() => {
    setShowCashAccountOption(listCashAccount?.map((data, index) => {
      return (
        <option value={data.id} key={index}>{data.nama}</option>
      );
    }))
  }, [listCashAccount])


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

  if (imagePreviewUrl) {
    $imagePreview = <img src={imagePreviewUrl} className="img-fluid img_prev" />
  }

  const clearAllInputField = () => {
    setJumlahUang('');
    setKeteranganPengajuan('');
    setCashAccount('1');
    setFile(null);
    setImagePreviewUrl('');
  }

  const handleSubmitReimbursement = (e) => {
    e.preventDefault();
    let formData = new FormData();
    formData.append("foto", file);

    Swal.fire({
      title: 'Apakah anda yakin?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'YA!'
    }).then((result) => {
      if (result.isConfirmed) {
        setIsLoading(true);
        axios({
          method: "post",
          url: `${window.location.origin}/api/ajukanReimbursement`,
          headers: {
            Accept: "application/json",
          },
          data: {
            'id_staff_pengaju': dataUser.id_staff,
            'jumlah_uang': jumlahUang,
            'keterangan_pengajuan': keteranganPengajuan,
            'id_cash_account': cashAccount,
          }
        })
          .then(response => {
            if (response.data.status == 'success') {
              console.log('reim', response.data.data);
              return response.data.data;
            }
          })
          .then(dataReimbursement => axios.post(`${window.location.origin}/api/ajukanReimbursement/foto/${dataReimbursement.id}`,
            formData, {
            headers: {
              Accept: "application/json",
            }
          }))
          .then(() => {
            setIsLoading(false);
            Swal.fire({
              title: 'success',
              text: 'berhasil membuat pengajuan',
              icon: 'success',
              confirmButtonText: 'OK!'
            }).then((result) => {
              if (result.isConfirmed) {
                clearAllInputField();
                getHistoryReimbursement();
                setTabActive('history');
              }
            })
          })
          .catch(error => {
            console.log(error.message);
            setIsLoading(false);
          });
      }
    })
  }

  const getDatePengajuan = (date) => {
    const newDate = date.substring(0, 10);
    const results = newDate.split('-');
    return `${results[2]}-${results[1]}-${results[0]}`;
  }

  const hideModal = () => {
    setActiveModal(null);
  }

  const clickHandler = (e, index) => {
    setActiveModal(index)
  }

  return (
    <main className="page_main">
      <HeaderSales title="Reimbursement" />
      {isLoading && <LoadingIndicator />}
      <div className="page_container pt-4">
        <div className="d-flex justify-content-center">
          <ButtonGroup>
            {radios.map((radio, idx) => (
              <ToggleButton
                key={idx}
                id={`radio-${idx}`}
                type="radio"
                variant={idx % 2 ? 'outline-success' : 'outline-primary'}
                name="radio"
                value={radio.value}
                checked={tabActive == radio.value}
                onChange={(e) => setTabActive(e.currentTarget.value)}
                className="ms-4 me-4 rounded">
                {radio.name}
              </ToggleButton>
            ))}
          </ButtonGroup>
        </div>

        {tabActive == 'history' &&
          <div className='mt-4'>
            <h1 className='fw-bold fs-5'>History Pengajuan</h1>
            <hr />
            {historyReimbursement && historyReimbursement.map((history, index) => (
              <div key={history.id}>
                <div id={history} className="card_reimbursement" onClick={(e) => clickHandler(e, index)}>
                  <div className="d-flex justify-content-between align-items-start">
                    <div>
                      <p className="fs-7 mb-0 fw-bold">Tanggal pengajuan:</p>
                      {history.created_at && <p className="fs-7 mb-0">{getDatePengajuan(history.created_at)}</p>}
                    </div>
                    <div className="badge bg-warning text-black fw-normal">
                      {history.status_enum == 0 ? 'Diajukan' :
                        history.status_enum == 1 ? 'Diproses' :
                          history.status_enum == 2 ? 'Dibayar' :
                            'Ditolak'
                      }
                    </div>
                  </div>
                  {history.jumlah_uang && <p className="fs-7 mb-0 fw-bold">Pengajuan sebesar {convertPrice(history.jumlah_uang)}</p>}
                </div>

                <Modal id={history} show={activeModal == index} onHide={hideModal}>
                  <Modal.Header closeButton>
                    <Modal.Title>Detail Pengajuan</Modal.Title>
                  </Modal.Header>
                  <Modal.Body>
                    {history.foto && <img src={`${urlAsset}/storage/reimbursement/${history.foto}`} className="img-fluid img_reimbursement mb-3" />}
                    <div className='info-2column'>
                      <span className='d-flex'>
                        <b>Status</b>
                        <div className='word_wrap'>
                          {history.status_enum == 0 ? 'Diajukan' :
                            history.status_enum == 1 ? 'Diproses' :
                              history.status_enum == 2 ? 'Dibayar' :
                                'Ditolak'
                          }
                        </div>
                      </span>

                      {history.status_enum != '0' && <span className='d-flex'>
                        <b>Dikonfirmasi Oleh</b>
                        {history.link_staff_pengonfirmasi && <div className='word_wrap'>{history.link_staff_pengonfirmasi.nama}</div>}
                      </span>}

                      <span className='d-flex'>
                        <b>Jumlah</b>
                        {history.jumlah_uang && <div className='word_wrap'>{convertPrice(history.jumlah_uang)}</div>}
                      </span>

                      <span className='d-flex'>
                        <b>Keterangan</b>
                        <div className='word_wrap'>{history.keterangan_pengajuan ?? null}</div>
                      </span>

                      <span className='d-flex'>
                        <b>Keperluan</b>
                        {history.link_cash_account && <div className='word_wrap'>{history.link_cash_account.nama}</div>}
                      </span>
                    </div>
                  </Modal.Body>
                  <Modal.Footer>

                  </Modal.Footer>
                </Modal>

              </div>
            ))}
          </div>
        }

        {tabActive == 'submission' &&
          <div className="mt-4">
            <h1 className='fw-bold fs-5'>Pengajuan</h1>
            <hr />
            <form onSubmit={handleSubmitReimbursement}>
              <div className="mb-3">
                <label className="form-label">Jenis Pengeluaran <span className='text-danger'>*</span></label>
                <select className="form-select"
                  value={cashAccount}
                  onChange={(e) => setCashAccount(e.target.value)}>
                  {showCashAccountOption}
                </select>
              </div>

              <div className="mb-3">
                <label className="form-label">Jumlah Pengeluaran <span className='text-danger'>*</span></label>
                <div className="input-group mb-3">
                  <span className="input-group-text">Rp</span>
                  <input type="number" className="form-control"
                    value={jumlahUang}
                    onChange={(e) => setJumlahUang(e.target.value)}
                  />
                </div>
              </div>

              <div className="mb-3">
                <label className="form-label">Keterangan <span className='text-danger'>*</span></label>
                <textarea className="form-control"
                  value={keteranganPengajuan}
                  onChange={(e) => setKeteranganPengajuan(e.target.value)}></textarea>
              </div>

              <label className="form-label d-block mt-4">Foto Bukti Pembayaran <span className='text-danger'>*</span></label>
              {$imagePreview && $imagePreview}
              <input
                type="file"
                name="foto"
                id="file"
                accept="image/png, image/jpeg"
                onChange={handleImageChange} />

              <button type="submit" className="btn btn-primary w-100 mt-4" disabled={isLoading}>Submit</button>
            </form>
          </div>
        }
      </div>
    </main >
  );
}

export default Reimbursement;
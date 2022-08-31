import React, { Fragment, useRef, useState, useEffect, useContext } from 'react';
import axios from 'axios';
import HeaderSales from './HeaderSales';
import ListCustomer from './ListCustomer';
import { splitCharacter } from '../reuse/HelperFunction';
import { Link } from 'react-router-dom';
import { AuthContext } from '../../contexts/AuthContext';
import { UserContext } from '../../contexts/UserContext';
import Modal from 'react-bootstrap/Modal';
import { useHistory } from "react-router";

let source;
const DashboardSales = () => {
  const { token, isAuth, isDefaultPassword, setIsDefaultPassword } = useContext(AuthContext);
  const { dataUser } = useContext(UserContext);
  const [namaCust, setNamaCust] = useState('');
  const [alamatUtama, setAlamatUtama] = useState('');
  const [listCustomer, setListCustomer] = useState([]);
  const [addButton, setAddButton] = useState('');
  const [dataShow, setDataShow] = useState('inactive');
  const [showModal, setShowModal] = useState(false);
  const [isOrder, setIsOrder] = useState();
  const _isMounted = useRef(true);
  const Swal = require('sweetalert2');
  const history = useHistory();
  const [shouldDisabled, setShouldDisabled] = useState(false);

  useEffect(() => {
    source = axios.CancelToken.source();
    return () => {
      _isMounted.current = false;
      if (source) {
        source.cancel("Cancelling in cleanup");
      }
    }
  }, []);

  useEffect(() => {
    if (isDefaultPassword) {
      Swal.fire({
        title: 'Anda Menggunakan Password Default',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ubah Password!'
      }).then((result) => {
        if (result.isConfirmed) {
          history.push('/changepassword');
        }
      })
    }
    setIsDefaultPassword(false);
  }, [])

  const cariCustomer = (e) => {
    e.preventDefault();
    source = axios.CancelToken.source();
    setShouldDisabled(true);
    axios({
      method: "post",
      url: `${window.location.origin}/api/cariCustomer`,
      cancelToken: source.token,
      headers: {
        Accept: "application/json",
        Authorization: "Bearer " + token,
      },
      data: {
        nama: namaCust,
        alamat_utama: alamatUtama,
      }
    })
      .then(response => {
        if (_isMounted.current) {
          setShouldDisabled(false);
          setListCustomer(response.data.data);
          setAddButton('active');
          setDataShow('inactive');
          return response.data.data;
        }
      })
      .catch(error => {
        if (_isMounted.current) {
          setShouldDisabled(false);
          setListCustomer([]);
          setDataShow('active');
          setAddButton('active');
          console.log(error.message);
        }
      });
  }

  const handleShowModal = (order) => {
    if (order) {
      setIsOrder(true);
    } else {
      setIsOrder(false);
    }
    setShowModal(true);
  }

  const handleCloseModal = () => setShowModal(false);



  return (
    <main className="page_main">
      {isAuth === 'true' && token !== null && dataUser.role == 'salesman' ?
        <Fragment>
          <HeaderSales isDashboard={true} />
          <div className="page_container pt-4">
            <div className="word d-flex justify-content-center">
              {splitCharacter("salesman")}
            </div>

            <div className="object-movement">
              <div className="salesman"><span className="iconify fs-2" data-icon="emojione:person-walking-light-skin-tone"></span></div>
            </div>

            <h1 className='fs-6 fw-bold'>Menu untuk Salesman</h1>
            <button className='btn btn-primary btn-lg w-100' onClick={() => handleShowModal(false)}>
              <span className="iconify fs-4 me-2" data-icon="bx:trip"></span> Trip
            </button>
            <button className='btn btn-success btn-lg w-100 mt-4' onClick={() => handleShowModal(true)}>
              <span className="iconify fs-4 me-2" data-icon="carbon:ibm-watson-orders"></span> Order
            </button>
            <Link to="/salesman/reimbursement" className='btn btn-purple btn-lg w-100 mt-4'>
              <span className="iconify fs-3 me-2" data-icon="mdi:cash-sync"></span> Reimbursement
            </Link>

            <Link to="/salesman/historyinvoice" className='btn btn-danger btn-lg w-100 mt-4'>
              <span className="iconify fs-3 me-2" data-icon="fa-solid:file-invoice-dollar"></span> Riwayat Invoice
            </Link>

            <Link to="/lapangan/penagihan" className='btn btn-info btn-lg w-100 mt-4 text-white'>
              <span className="iconify fs-3 me-2 text-white" data-icon="uil:bill"></span> Penagihan
            </Link>

            <Link to='/lapangan/jadwal' className='btn btn-primary btn-lg w-100 mt-3'>
              <span className="iconify me-2" data-icon="fa-solid:shipping-fast"></span>
              Pengiriman
            </Link>
          </div>

          <Modal show={showModal} onHide={handleCloseModal} centered={true}>
            <Modal.Header closeButton>
              <Modal.Title>Cari Customer</Modal.Title>
            </Modal.Header>
            <Modal.Body>
              <form onSubmit={cariCustomer}>
                <div className="mb-3">
                  <label className="form-label">Nama Customer</label>
                  <input type="text" value={namaCust || ''} onChange={(e) => setNamaCust(e.target.value)} className="form-control" />
                </div>
                <div className="mb-3">
                  <label className="form-label">Alamat Utama</label>
                  <input type="text" value={alamatUtama || ''} onChange={(e) => setAlamatUtama(e.target.value)} className="form-control" />
                </div>
                <div className="row">
                  <div className="col-5 offset-7">
                    <button type="submit" className="btn btn-primary w-100" disabled={shouldDisabled}><span className="iconify me-2" data-icon="fe:search"></span>Cari</button>
                  </div>
                </div>
              </form>
              <div className="box-list-customer mt-3">
                <small className={`text-center text-danger fw-bold ${dataShow == 'active' ? 'd-block' : 'd-none'}`}>Data Tidak Ditemukan</small>
                <ListCustomer listCustomer={listCustomer} isOrder={isOrder} />
              </div>

              <Link to="/salesman/trip"
                className={`btn btn-primary mt-2 ${addButton == 'active' ? 'd-block' : 'd-none'}`}>
                Masih belum menemukan? <br /> Silahkan tambah baru!
              </Link>
            </Modal.Body>
          </Modal>
        </Fragment>
        : ''}
    </main>
  );
}

export default DashboardSales;
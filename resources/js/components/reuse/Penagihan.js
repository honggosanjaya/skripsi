import React, { useState, useContext, useEffect, Fragment } from 'react';
import axios from 'axios';
import HeaderSales from '../sales/HeaderSales';
import { UserContext } from '../../contexts/UserContext';
import Modal from 'react-bootstrap/Modal';
import Button from 'react-bootstrap/Button';
import { convertPrice, convertTanggal } from './HelperFunction';
import AlertComponent from './AlertComponent';
import LoadingIndicator from './LoadingIndicator';

const Penagihan = () => {
  const { dataUser } = useContext(UserContext);
  const [dataTagihan, setDataTagihan] = useState([]);
  const [detailTagihan, setDetailTagihan] = useState(null);
  const [showModal, setShowModal] = useState(false);
  const [successMessage, setSuccessMessage] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [isNoData, setIsNoData] = useState(false);
  // var todayDate = new Date().toISOString().slice(0, 10);
  // const [tanggal, setTanggal] = useState(todayDate);

  const getLp3 = () => {
    axios({
      // method: "post",
      method: "get",
      url: `${window.location.origin}/api/lapangan/penagihan/${dataUser.id_staff}`,
      headers: {
        Accept: "application/json",
      },
      // data: {
      //   date: tanggal
      // },
    })
      .then(response => {
        console.log('hai', response.data.data);
        setDataTagihan(response.data.data);
        if (response.data.data.length == 0) {
          setIsNoData(true);
        }
        setIsLoading(false);
      })
      .catch(error => {
        setIsLoading(false);
        console.log(error.message);
      });
  }

  useEffect(() => {
    if (dataUser.id_staff != undefined) {
      setIsLoading(true);
      getLp3();
    }
  }, [dataUser])
  // }, [dataUser, tanggal])

  const handleClickLp3 = (idInvoice) => {
    setShowModal(true);
    setIsLoading(true);
    axios({
      method: "get",
      url: `${window.location.origin}/api/administrasi/detailpenagihan/${idInvoice}`,
      headers: {
        Accept: "application/json",
      },
    })
      .then(response => {
        setIsLoading(false);
        setDetailTagihan(response.data.data);
        console.log('dataku', response.data.data);
      })
      .catch(error => {
        setIsLoading(false);
        console.log(error.message);
      });
  }

  const handleCloseModal = () => {
    setShowModal(false);
  }

  const onSudahTagih = (idLp3) => {
    setIsLoading(true);
    axios({
      method: "get",
      url: `${window.location.origin}/api/lapangan/handlepenagihan/${idLp3}`,
      headers: {
        Accept: "application/json",
      },
    })
      .then(response => {
        getLp3();
        setIsLoading(false);
        setShowModal(false);
        setSuccessMessage(response.data.message);
        setTimeout(() => {
          setSuccessMessage('');
        }, 2000)
      })
      .catch(error => {
        setIsLoading(false);
        console.log(error.message);
      });
  }

  return (
    <main className="page_main">
      <HeaderSales title="LP3" />
      {successMessage && <AlertComponent successMsg={successMessage} />}
      {isLoading && <LoadingIndicator />}
      <div className="page_container pt-4">
        {/* <label>Tanggal</label>
        <div className="input-group">
          <input
            type='date'
            className="form-control"
            id="tanggalTrip"
            value={tanggal}
            onChange={(e) => setTanggal(e.target.value)}
          />
        </div> */}

        <div className="table-responsive mt-4">
          <table className="table">
            <thead>
              <tr>
                <th scope="col" className='text-center'>Nama Toko</th>
                <th scope="col" className='text-center'>Wilayah</th>
                <th scope="col" className='text-center'>Tanggal</th>
                <th scope="col" className='text-center'>Status</th>
              </tr>
            </thead>
            <tbody>
              {dataTagihan.map((data) => (
                <tr key={data.id} onClick={() => handleClickLp3(data.id_invoice)}>
                  <td>{data.link_invoice.link_order.link_customer.nama ?? null}</td>
                  <td>{data.link_invoice.link_order.link_customer.link_district.nama ?? null}</td>
                  {data.tanggal && <td>{convertTanggal(data.tanggal)}</td>}
                  {data.status_enum && <td>{data.status_enum == '1' ? <p className='text-success mb-0'>Sudah Tagih</p> : <p className='text-danger mb-0'>Belum Tagih</p>}</td>}
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {isNoData && <p className="mb-0 text-danger text-center">Tidak Ada Data</p>}

        {detailTagihan && <Modal show={showModal} onHide={handleCloseModal}>
          <Modal.Header closeButton>
            <Modal.Title>Detail LP3</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <div className='info-2column'>
              <span className='d-flex'>
                <b>No. Invoice</b>
                <p className='mb-0 word_wrap'>{detailTagihan.invoice.nomor_invoice ?? null}</p>
              </span>

              <span className='d-flex'>
                <b>Customer</b>
                <p className='mb-0 word_wrap'>{detailTagihan.customer.nama ?? null}</p>
              </span>

              <span className='d-flex'>
                <b>Telepon</b>
                <p className='mb-0 word_wrap'>{detailTagihan.customer.telepon ?? null}</p>
              </span>

              <span className='d-flex'>
                <b>Alamat</b>
                {detailTagihan.customer.koordinat && <a target="_blank" href={`https://www.google.com/maps/search/?api=1&query=${detailTagihan.customer.koordinat.replace("@", ",")}`}>
                  <p className='mb-0 word_wrap'>{detailTagihan.customer.full_alamat ?? null}</p></a>}
                {detailTagihan.customer.koordinat == null && <p className='mb-0 word_wrap'>{detailTagihan.customer.full_alamat ?? null}</p>}
              </span>

              <span className='d-flex'>
                <b>Total Penagihan</b>
                {detailTagihan.tagihan && <p className='mb-0 word_wrap'>{convertPrice(detailTagihan.tagihan)}</p>}
              </span>
            </div>
          </Modal.Body>

          <Modal.Footer>
            <Button variant="danger" onClick={handleCloseModal}>
              <span className="iconify fs-3 me-1" data-icon="carbon:close-outline"></span>Tutup
            </Button>
            {detailTagihan.lp3 !== null && <Button variant="success" onClick={() => onSudahTagih(detailTagihan.lp3.id)} disabled={isLoading}>
              <span className="iconify fs-3 me-1" data-icon="icon-park-outline:doc-success"></span>Sudah Ditagih
            </Button>}
          </Modal.Footer>
        </Modal>}
      </div>
    </main>
  );
}

export default Penagihan;
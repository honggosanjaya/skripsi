import React, { useState, useContext, useEffect, Fragment } from 'react';
import axios from 'axios';
import HeaderSales from '../sales/HeaderSales';
import { UserContext } from '../../contexts/UserContext';
import Modal from 'react-bootstrap/Modal';
import { convertPrice, convertTanggal } from './HelperFunction';

const Penagihan = () => {
  const { dataUser } = useContext(UserContext);
  const [dataTagihan, setDataTagihan] = useState([]);
  const [detailTagihan, setDetailTagihan] = useState(null);
  const [showModal, setShowModal] = useState(false);

  useEffect(() => {
    if (dataUser.id_staff != undefined) {
      axios({
        method: "get",
        url: `${window.location.origin}/api/lapangan/penagihan/${dataUser.id_staff}`,
        headers: {
          Accept: "application/json",
        },
      })
        .then(response => {
          setDataTagihan(response.data.data);
          console.log('dataku', response.data.data);
        })
        .catch(error => {
          console.log(error.message);
        });
    }
  }, [dataUser])

  const handleClickLp3 = (idInvoice) => {
    setShowModal(true);

    axios({
      method: "get",
      url: `${window.location.origin}/api/administrasi/detailpenagihan/${idInvoice}`,
      headers: {
        Accept: "application/json",
      },
    })
      .then(response => {
        setDetailTagihan(response.data.data);
        console.log('dataku', response.data.data);
      })
      .catch(error => {
        console.log(error.message);
      });
  }


  const handleCloseModal = () => {
    setShowModal(false);
  }

  return (
    <main className="page_main">
      <HeaderSales title="LP3" />

      <div className="page_container pt-4">
        <div className="table-responsive">
          <table className="table">
            <thead>
              <tr>
                <th scope="col" className='text-center'>Nama Toko</th>
                <th scope="col" className='text-center'>Wilayah</th>
                <th scope="col" className='text-center'>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              {dataTagihan.map((data) => (
                <tr key={data.id} onClick={() => handleClickLp3(data.id_invoice)}>
                  <td>{data.link_invoice.link_order.link_customer.nama}</td>
                  <td>{data.link_invoice.link_order.link_customer.link_district.nama}</td>
                  <td>{convertTanggal(data.tanggal)}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {detailTagihan && <Modal show={showModal} onHide={handleCloseModal}>
          <Modal.Header closeButton>
            <Modal.Title>Detail LP3</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <div className='info-2column'>
              <span className='d-flex'>
                <b>No. Invoice</b>
                <p className='mb-0 word_wrap'>{detailTagihan.invoice.nomor_invoice}</p>
              </span>

              <span className='d-flex'>
                <b>Customer</b>
                <p className='mb-0 word_wrap'>{detailTagihan.customer.nama}</p>
              </span>

              <span className='d-flex'>
                <b>Telepon</b>
                <p className='mb-0 word_wrap'>{detailTagihan.customer.telepon}</p>
              </span>

              <span className='d-flex'>
                <b>Alamat</b>
                {detailTagihan.customer.koordinat && <a target="_blank" href={`https://www.google.com/maps/search/?api=1&query=${detailTagihan.customer.koordinat.replace("@", ",")}`}>
                  <p className='mb-0 word_wrap'>{detailTagihan.customer.full_alamat}</p></a>}
                {detailTagihan.customer.koordinat == null && <p className='mb-0 word_wrap'>{detailTagihan.customer.full_alamat}</p>}
              </span>

              <span className='d-flex'>
                <b>Total Penagihan</b>
                <p className='mb-0 word_wrap'>{convertPrice(detailTagihan.tagihan)}</p>
              </span>
            </div>
          </Modal.Body>
        </Modal>}
      </div>
    </main>
  );
}

export default Penagihan;
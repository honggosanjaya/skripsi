import React, { useState, useContext, useEffect, Fragment } from 'react';
import axios from 'axios';
import HeaderSales from './HeaderSales';
import { UserContext } from '../../contexts/UserContext';
import { convertPrice } from '../reuse/HelperFunction';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';

const HistoryInvoice = () => {
  const { dataUser } = useContext(UserContext);
  const [dataInvoices, setDataInvoices] = useState([]);
  const [showModal, setShowModal] = useState(false);
  const [detailInvoice, setDetailInvoice] = useState([]);
  const [detailItem, setDetailItem] = useState([]);

  var currentMonth = new Date().getMonth() + 1;
  var currentYear = new Date().getFullYear();

  if (currentMonth < 10) {
    var startDate = `${currentYear}-0${currentMonth}-01`
  } else {
    var startDate = `${currentYear}-${currentMonth}-01`
  }

  var endDate = new Date().toISOString().slice(0, 10);

  const [dateStart, setDateStart] = useState(startDate);
  const [dateEnd, setDateEnd] = useState(endDate);

  useEffect(() => {
    if (dataUser) {
      axios({
        method: "post",
        url: `${window.location.origin}/api/historyinvoice`,
        headers: {
          Accept: "application/json",
        },
        data: {
          id_staff: dataUser.id_staff,
          dateStart: dateStart,
          dateEnd: dateEnd,
        },
      })
        .then(response => {
          // console.log('dataku', response.data.data);
          setDataInvoices(response.data.data);
        })
        .catch(error => {
          console.log(error.message);
        });
    }
  }, [dataUser, dateStart, dateEnd])

  const getTotalItem = (items, prop) => {
    return items.reduce(function (a, b) {
      return a + b[prop];
    }, 0);
  }



  const handleClickInvoice = (idInvoice) => {
    setShowModal(true);
    const filteredInvoice = dataInvoices.filter(x =>
      x.id == idInvoice
    );

    setDetailInvoice(filteredInvoice[0]);
    setDetailItem(filteredInvoice[0].link_order.link_order_item);
    // console.log('det', filteredInvoice[0]);
  }

  const handleCloseModal = () => {
    setShowModal(false);
  }

  useEffect(() => {
    console.log('det', dataInvoices);
  }, [dataInvoices])


  useEffect(() => {
    console.log('datainv', dataInvoices);
  }, [dataInvoices])


  return (
    <main className="page_main">
      <HeaderSales title="Riwayat Invoice" />

      <div className="page_container pt-4">
        <label>Tanggal Mulai</label>
        <div className="input-group">
          <input
            type='date'
            className="form-control"
            id="tanggalMulai"
            value={dateStart}
            onChange={(e) => setDateStart(e.target.value)}
          />
        </div>

        <label className='mt-3'>Tanggal Selesai</label>
        <div className="input-group">
          <input
            type='date'
            className="form-control"
            id="tanggalMulai"
            value={dateEnd}
            onChange={(e) => setDateEnd(e.target.value)}
          />
        </div>

        {dataInvoices &&
          <Fragment>
            <h6 className="my-4">Jumlah Invoice : {dataInvoices.length}</h6>
            <div className="table-responsive">
              <table className="table">
                <thead>
                  <tr>
                    <th scope="col" className='text-center'>Nama Customer</th>
                    <th scope="col" className='text-center'>Jumlah Invoice</th>
                    <th scope="col" className='text-center'>Total Barang</th>
                  </tr>
                </thead>
                <tbody>
                  {dataInvoices.map((data) => (
                    <tr key={data.id} onClick={() => handleClickInvoice(data.id)}>
                      <td className='align-middle'>{data.link_order.link_customer.nama}</td>
                      <td className='text-center align-middle'>{convertPrice(data.harga_total)}</td>
                      <td className='text-center align-middle'>{getTotalItem(data.link_order.link_order_item, "kuantitas")}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </Fragment>
        }

        {detailInvoice && <Modal show={showModal} onHide={handleCloseModal}>
          <Modal.Header closeButton>
            <Modal.Title>{detailInvoice.nomor_invoice}</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <p className="fw-bold">Barang yang Dipesan</p>
            <div className="table-responsive">
              <table className="table">
                <thead>
                  <tr>
                    <th scope="col" className='text-center'>Nama Barang</th>
                    <th scope="col" className='text-center'>Kuantitas</th>
                    <th scope="col" className='text-center'>Harga Satuan</th>
                  </tr>
                </thead>
                <tbody>
                  {detailItem.map((data) => (
                    <tr key={data.id}>
                      <td className='align-middle'>{data.link_item.nama}</td>
                      <td className='text-center align-middle'>{data.kuantitas}</td>
                      <td className='text-center align-middle'>{convertPrice(data.harga_satuan)}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </Modal.Body>
        </Modal>}
      </div>
    </main>
  );
}

export default HistoryInvoice;
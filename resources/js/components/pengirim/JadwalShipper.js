import React, { Component, useState, useEffect, useContext } from 'react';
import { splitCharacter } from '../reuse/HelperFunction';
import HeaderShipper from './HeaderShipper';
import Modal from 'react-bootstrap/Modal'
import { UserContext } from "../../contexts/UserContext";
import Button from 'react-bootstrap/Button'
import { useHistory } from 'react-router';

const ShippingShipper = () => {
  // const { token, isAuth, setErrorAuth } = useContext(AuthContext);
  // const history = useHistory();
  const { dataUser, loadingDataUser } = useContext(UserContext);
  const [listShipping, setListShipping] = useState([]);
  const [listDetailItem, setListDetailItem] = useState([]);
  const [show, setShow] = useState(false);
  
  const [detailShippingNama, setDetailShippingNama] = useState('');
  const [detailShippingTelepon, setDetailShippingTelepon] = useState('');
  const [detailShippingFullAlamat, setDetailShippingFullAlamat] = useState('');
  const [detailShippingKeteranganAlamat, setDetailShippingKeteranganAlamat] = useState('');
  const [detailShippingWaktuKeberangkatan, setDetailShippingWaktuKeberangkatan] = useState('');
  const [detailShippingEstimasiWaktuPengiriman, setDetailShippingEstimasiWaktuPengiriman] = useState('');
  const [detailShippingInvoice, setDetailShippingInvoice] = useState('');
  const [detailShippingHargaTotal, setDetailShippingHargaTotal] = useState('');
  

  const handleClose = () => setShow(false);
  const handleShow = (shippingid) => {
    axios.get(`${window.location.origin}/api/shipper/jadwalPengiriman/${shippingid}`).then(response => {
      console.log(response.data.data);
      setDetailShippingNama(response.data.data.link_customer.nama)
      setDetailShippingTelepon(response.data.data.link_customer.telepon)
      setDetailShippingFullAlamat(response.data.data.link_customer.full_alamat)
      setDetailShippingKeteranganAlamat(response.data.data.link_customer.keterangan_alamat)
      setDetailShippingWaktuKeberangkatan(response.data.data.link_order_track.waktu_berangkat)
      setDetailShippingEstimasiWaktuPengiriman(response.data.data.link_order_track.estimasi_waktu_pengiriman)
      setDetailShippingInvoice(response.data.data.link_invoice.nomor_invoice)
      setDetailShippingHargaTotal(response.data.data.link_invoice.harga_total)
      setListDetailItem(response.data.data.link_order_item)
        
      return response.data.data;
    })
      setShow(true)
  };

  

  useEffect(() => {
    axios.get(`${window.location.origin}/api/shipper/jadwalPengiriman?id_staff=${dataUser.id_staff}`).then(response => {
      setListShipping(response.data.data);
      console.log(response.data.data);
      return response.data.data;
    })
  }, [listShipping])

  const showListShipping = listShipping.map((data, index) => {
    return (
      <button className="mb-3 btn btn-primary d-block"  onClick={() => handleShow(data.id)} style={{width: '100%'}} key={`jadwal${index}`} >
        <div className="d-flex flex-column">
          <h1>{data.link_invoice.nomor_invoice??null}</h1>
          <div>
            {data.link_customer.nama??null}
          </div>
          <div>
            {data.link_customer.telepon??null}
          </div>
          <div>
            {data.link_customer.full_alamat??null}
          </div>
          <div>
            {data.link_customer.waktu_berangkat??null}
          </div>
        </div>
      </button>
    );
  });

  const showListDetailItem = listDetailItem.map((data, index) => {
    console.log(data)
    return (
      <div className='container' key={`item${index}`}>
        <div className='row'>
          <div className='col-3'>
            {index}
          </div>
          <div className='col-4'>
            {data.link_item.nama}
          </div>
          <div className='col-2'>
            {data.kuantitas}
          </div>
          <div className='col-2'>
            {data.link_item.satuan}
          </div>
        </div>
      </div>
    );
  });



  


  return (
    <main className="page_main shipper-css">
      <HeaderShipper isDashboard={true} />
      <div className="page_container pt-4">
        <div className="word d-flex justify-content-center">
          {splitCharacter("shipper")}

        </div>
        {showListShipping}

        <Modal show={show} onHide={handleClose}>
          <Modal.Header closeButton>
            <Modal.Title>{detailShippingInvoice}</Modal.Title>
          </Modal.Header>
          <Modal.Body>
          <div className='container'>
            <div className='row'>
              <div className='col-6'>Nama Customer</div>
              <div className='col-6'>{detailShippingNama}</div>
              <div className='col-6'>Telepon</div>
              <div className='col-6'>{detailShippingTelepon}</div>
              <div className='col-6'>Alamat</div>
              <div className='col-6'>{detailShippingFullAlamat}</div>
              <div className='col-6'>Keterangan Alamat</div>
              <div className='col-6'>{detailShippingKeteranganAlamat}</div>
              <div className='col-6'>Waktu Keberangkatan</div>
              <div className='col-6'>{detailShippingWaktuKeberangkatan}</div>
              <div className='col-6'>No Invoice</div>
              <div className='col-6'>{detailShippingInvoice}</div>
              <div className='col-6'>Total Pembayaran</div>
              <div className='col-6'>{detailShippingHargaTotal}</div>
              <div className="d-flex flex-column ">
              <div className='container' >
                <div className='row'>
                  <div className='col-3'>
                    No
                  </div>
                  <div className='col-4'>
                    Nama
                  </div>
                  <div className='col-2'>
                    Qty
                  </div>
                  <div className='col-2'>
                    Stn
                  </div>
                </div>
              </div>
                {showListDetailItem}
              </div>


            </div>
          </div>
          </Modal.Body>
          <Modal.Footer>
            <Button variant="secondary" onClick={handleClose}>
              Close
            </Button>
            <Button variant="primary" onClick={handleClose}>
              Save Changes
            </Button>
          </Modal.Footer>
        </Modal>
        
        
      </div>
    </main>
  );
}

export default ShippingShipper;
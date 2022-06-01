import React, { Component, useState, useEffect, useContext } from 'react';
import { splitCharacter } from '../reuse/HelperFunction';
import HeaderShipper from './HeaderShipper';
import Modal from 'react-bootstrap/Modal'
import { UserContext } from "../../contexts/UserContext";
import Button from 'react-bootstrap/Button'
import { useHistory } from 'react-router';
import AlertComponent from '../reuse/AlertComponent';

const ShippingShipper = () => {
  // const { token, isAuth, setErrorAuth } = useContext(AuthContext);
  // const history = useHistory();
  const { dataUser, loadingDataUser } = useContext(UserContext);
  const [listShipping, setListShipping] = useState([]);
  const [listDetailItem, setListDetailItem] = useState([]);
  const [show, setShow] = useState(false);
  const [showBuktiPengiriman, setShowBuktiPengiriman] = useState(false);
  const [detailShipping, setDetailShipping] = useState(null);

  const [file, setFile] = useState(null);
  const [imagePreviewUrl, setImagePreviewUrl] = useState('');
  let $imagePreview = null;
  const [successMessage, setSuccessMessage] = useState(null);

  useEffect(() => {
    axios.get(`${window.location.origin}/api/shipper/jadwalPengiriman?id_staff=${dataUser.id_staff}`)
      .then(response => {
        setListShipping(response.data.data);
      })
      .catch(error => {
        console.log(error.message);
      });
  }, [dataUser, successMessage])

  const handleClose = () => {
    setShow(false);
  }

  const handleCloseBuktiPengirimanModal = () => {
    setShowBuktiPengiriman(false);
    setShow(false);
  }

  const handleSubmitBuktiPengiriman = (e) => {
    e.preventDefault();
    let formData = new FormData();
    formData.append("foto", file);

    axios.post(`${window.location.origin}/api/pesanan/detail/${detailShipping.id}/dikirimkan`,
      formData, {
      headers: {
        Accept: "application/json",
      }
    })
      .then(response => {
        console.log(response.data);
        setShowBuktiPengiriman(false);
        setShow(false);
        setSuccessMessage(response.data.message);
        setTimeout(() => {
          setSuccessMessage(null);
        }, 3000);
      })
      .catch(error => {
        console.log(error.message);
      });
  }

  const handleShow = (shippingid) => {
    axios.get(`${window.location.origin}/api/shipper/jadwalPengiriman/${shippingid}`).then(response => {
      // console.log('jadwal', response.data.data);
      setDetailShipping(response.data.data);
      setListDetailItem(response.data.data.link_order_item);
    })
    setShow(true);
  };

  const showListShipping = listShipping.map((data, index) => {
    return (
      <button className="mb-3 btn btn-primary d-block" onClick={() => handleShow(data.id)} style={{ width: '100%' }} key={`jadwal${index}`} >
        <div className="d-flex flex-column">
          <h1>{data.link_invoice.nomor_invoice ?? null}</h1>
          <div>
            {data.link_customer.nama ?? null}
          </div>
          <div>
            {data.link_customer.telepon ?? null}
          </div>
          <div>
            {data.link_customer.full_alamat ?? null}
          </div>
          <div>
            {data.link_customer.waktu_berangkat ?? null}
          </div>
        </div>
      </button>
    );
  });

  const showListDetailItem = listDetailItem.map((data, index) => {
    // console.log(data)
    return (
      <div className='row' key={`item${index}`}>
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
    );
  });

  const handlePengirimanSampai = () => {
    setShowBuktiPengiriman(true);
    setShow(false);
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

  if (imagePreviewUrl) {
    $imagePreview = <img src={imagePreviewUrl} className="preview_tempatUsaha" />
  }

  return (
    <main className="page_main shipper-css">
      <HeaderShipper isDashboard={true} />
      <div className="page_container pt-4">
        {successMessage && <AlertComponent successMsg={successMessage} />}
        <div className="word d-flex justify-content-center">
          {splitCharacter("shipper")}
        </div>
        {showListShipping}

        {detailShipping &&
          <Modal show={show} onHide={handleClose}>
            <Modal.Header closeButton>
              <Modal.Title>{detailShipping.link_invoice.nomor_invoice}</Modal.Title>
            </Modal.Header>
            <Modal.Body>
              <div className='container'>
                <div className='row'>
                  <ul className="info-shipping">
                    <li><b>Nama Customer</b>{detailShipping.link_customer.nama}</li>
                    <li><b>Telepon</b>{detailShipping.link_customer.telepon}</li>
                    <li><b>Alamat</b>{detailShipping.link_customer.full_alamat}</li>
                    <li><b>Keterangan Alamat</b>{detailShipping.link_customer.keterangan_alamat}</li>
                    <li><b>Waktu Keberangkatan</b>{detailShipping.link_order_track.waktu_berangkat}</li>
                    <li><b>No Invoice</b>{detailShipping.link_invoice.nomor_invoice}</li>
                    <li><b>Total Pembayaran</b>{detailShipping.link_invoice.harga_total}</li>
                  </ul>

                  <div className="d-flex flex-column ">
                    <div className='row'>
                      <div className='col-3'>No</div>
                      <div className='col-4'>Nama</div>
                      <div className='col-2'>Qty</div>
                      <div className='col-2'>Stn</div>
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
              <Button variant="primary" onClick={handlePengirimanSampai}>
                Pengiriman Sampai
              </Button>
            </Modal.Footer>
          </Modal>}


        <Modal show={showBuktiPengiriman} onHide={handleCloseBuktiPengirimanModal}>
          <Modal.Header closeButton>
            <Modal.Title>Bukti Pengiriman</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <label className="form-label d-block">Foto Bukti Pengiriman</label>
            {$imagePreview && $imagePreview}
            <input
              type="file"
              name="foto"
              id="file"
              accept="image/png, image/jpeg"
              onChange={handleImageChange} />
          </Modal.Body>
          <Modal.Footer>
            <Button variant="secondary" onClick={handleCloseBuktiPengirimanModal}>
              Close
            </Button>
            <Button variant="primary" onClick={handleSubmitBuktiPengiriman}>
              Kirim
            </Button>
          </Modal.Footer>
        </Modal>
      </div>
    </main>
  );
}

export default ShippingShipper;
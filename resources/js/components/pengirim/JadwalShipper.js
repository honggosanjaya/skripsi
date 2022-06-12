import React, { useState, useEffect, useContext, Fragment } from 'react';
import HeaderShipper from './HeaderShipper';
import { UserContext } from "../../contexts/UserContext";
import { useHistory } from 'react-router';
import AlertComponent from '../reuse/AlertComponent';
import ButtonGroup from 'react-bootstrap/ButtonGroup';
import ToggleButton from 'react-bootstrap/ToggleButton';
import Modal from 'react-bootstrap/Modal';
import Button from 'react-bootstrap/Button';
import LoadingIndicator from '../reuse/LoadingIndicator';

import ListShipping from './ListShipping';
import DetailShipping from './DetailShipping';
import { ReturContext } from '../../contexts/ReturContext';

const ShippingShipper = () => {
  // const { token, isAuth, setErrorAuth } = useContext(AuthContext);
  const { setIdInvoice } = useContext(ReturContext);
  const history = useHistory();
  const { dataUser, loadingDataUser } = useContext(UserContext);
  const [listShipping, setListShipping] = useState([]);
  const [listDetailItem, setListDetailItem] = useState([]);
  const [show, setShow] = useState(false);
  const [statusShipping, setStatusShipping] = useState(22);
  const [showBuktiPengiriman, setShowBuktiPengiriman] = useState(false);
  const [detailShipping, setDetailShipping] = useState(null);
  const [file, setFile] = useState(null);
  const [imagePreviewUrl, setImagePreviewUrl] = useState('');
  const [successMessage, setSuccessMessage] = useState(null);

  const [isLoading, setIsLoading] = useState(false);

  const radios = [
    { name: 'Perlu Dikirim', value: 22 },
    { name: 'Sudah Sampai', value: 23 },
  ];

  let $imagePreview = null;

  useEffect(() => {
    setIsLoading(true);
    axios.get(`${window.location.origin}/api/shipper/jadwalPengiriman?id_staff=${dataUser.id_staff}`)
      .then(response => {
        setIsLoading(false);
        console.log('jadwal pengiriman', response.data.data);
        setListShipping(response.data.data);
      })
  }, [dataUser, successMessage])

  const handleClose = () => setShow(false);

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
    setIsLoading(true);
    axios.get(`${window.location.origin}/api/shipper/jadwalPengiriman/${shippingid}`)
      .then(response => {
        console.log('show', response.data.data);
        setIdInvoice(response.data.data.link_invoice.id);
        setDetailShipping(response.data.data);
        setListDetailItem(response.data.data.link_order_item);
        setIsLoading(false);
      })
    setShow(true);
  };

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

  const handlePengajuanRetur = (idCust) => {
    history.push(`/shipper/retur/${idCust}`);
  }

  return (
    <main className="page_main shipper-css">
      <HeaderShipper title="Jadwal Pengiriman" />
      {isLoading && <LoadingIndicator />}
      <div className="page_container pt-4">
        {successMessage && <AlertComponent successMsg={successMessage} />}
        <div className="d-flex flex-column justify-content-center">
          <h1 className="fs-6 fw-bold mb-0">Data Pengiriman</h1>
          <ButtonGroup className='mt-2 mb-4'>
            {radios.map((radio, idx) => (
              <ToggleButton
                key={idx}
                id={`radio-${idx}`}
                type="radio"
                variant={idx % 2 ? 'outline-success' : 'outline-warning'}
                name="radio"
                value={radio.value}
                checked={statusShipping == radio.value}
                onChange={(e) => setStatusShipping(e.currentTarget.value)}>
                {radio.name}
              </ToggleButton>
            ))}
          </ButtonGroup>
        </div>

        <ListShipping listShipping={listShipping} statusShipping={statusShipping} handleShow={handleShow} />

        <DetailShipping detailShipping={detailShipping}
          isLoading={isLoading} show={show} handleClose={handleClose}
          handlePengirimanSampai={handlePengirimanSampai} handlePengajuanRetur={handlePengajuanRetur}
          listDetailItem={listDetailItem} />

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
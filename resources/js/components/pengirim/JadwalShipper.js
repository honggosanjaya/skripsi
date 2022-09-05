import React, { useState, useEffect, useContext, useRef, Fragment } from 'react';
import Camera from 'react-html5-camera-photo';
import 'react-html5-camera-photo/build/css/index.css';
import axios from 'axios';
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
import { AuthContext } from '../../contexts/AuthContext';
import ImagePreview from '../reuse/ImagePreview';
import { dataURLtoFile } from "../reuse/HelperFunction";

let source;
const ShippingShipper = () => {
  const { token } = useContext(AuthContext);
  const { setIdInvoice } = useContext(ReturContext);
  const history = useHistory();
  const { dataUser } = useContext(UserContext);
  const [listShipping, setListShipping] = useState([]);
  const [listDetailItem, setListDetailItem] = useState([]);
  const [show, setShow] = useState(false);
  const [statusShipping, setStatusShipping] = useState('3');
  const [showBuktiPengiriman, setShowBuktiPengiriman] = useState(false);
  const [detailShipping, setDetailShipping] = useState(null);
  const [file, setFile] = useState(null);
  const [imagePreviewUrl, setImagePreviewUrl] = useState('');
  const [successMessage, setSuccessMessage] = useState(null);
  const [isLoading, setIsLoading] = useState(false);
  const _isMounted = useRef(true);
  const [dataUri, setDataUri] = useState('');
  const [keyword, setKeyword] = useState(null);
  const [isTakePhoto, setIsTakePhoto] = useState(false);
  const [isFromGalery, setISFromGalery] = useState(false);
  const [perluKirim, setPerluKirim] = useState(null);
  const [sudahSampai, setSudahSampai] = useState(null);

  const radios = [
    { name: 'Perlu Dikirim', value: '3' },
    { name: 'Sudah Sampai', value: '4' },
  ];
  let $imagePreview = null;

  const goBack = (role) => {
    if (role == 'salesman') {
      history.push('/salesman');
    } else if (role == 'shipper') {
      history.push('/shipper');
    }
  }

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
    let unmounted = false;
    let source = axios.CancelToken.source();
    setIsLoading(true);
    // console.log('datauser', dataUser);
    if (dataUser.id_staff) {
      axios({
        method: "get",
        url: `${window.location.origin}/api/shipper/jadwalPengiriman?id_staff=${dataUser.id_staff}`,
        cancelToken: source.token,
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token,
        },
      })
        .then(response => {
          if (!unmounted) {
            setIsLoading(false);
            console.log('jadwal pengiriman', response.data.data);
            setListShipping(response.data.data.data);
            setPerluKirim(response.data.data.perludikirim);
            setSudahSampai(response.data.data.sudahsampai);
          }
        })
    }
    return function () {
      unmounted = true;
      source.cancel("Cancelling in cleanup");
    };
  }, [dataUser, successMessage])

  const handleTakePhotoAnimationDone = (dataUri) => {
    setDataUri(dataUri);
  }

  const handleClose = () => setShow(false);

  const handleCloseBuktiPengirimanModal = () => {
    setShowBuktiPengiriman(false);
    setShow(false);
    setFile(null);
    setImagePreviewUrl('');
    setISFromGalery(false);
    setIsTakePhoto(false);
  }

  const handleSubmitBuktiPengiriman = (e) => {
    e.preventDefault();
    setIsLoading(true);
    source = axios.CancelToken.source();
    let formData = new FormData();

    if (isFromGalery) {
      formData.append("foto", file);
    } else if (isTakePhoto) {
      let convertedFile = dataURLtoFile(dataUri, `${Math.random(10)}.png`);
      formData.append("foto", convertedFile);
    }

    axios({
      method: "post",
      url: `${window.location.origin}/api/pesanan/detail/${detailShipping.id}/dikirimkan`,
      data: formData,
      cancelToken: source.token,
      headers: {
        Accept: "application/json",
        Authorization: "Bearer " + token,
      },
    })
      .then(response => {
        if (_isMounted.current) {
          setIsLoading(false);
          setShowBuktiPengiriman(false);
          setShow(false);
          setSuccessMessage(response.data.message);
          setFile(null);
          setImagePreviewUrl('');
          setTimeout(() => {
            setSuccessMessage(null);
          }, 3000);
        }
      })
      .catch(error => {
        if (_isMounted.current) {
          setIsLoading(false);
          setFile(null);
          setImagePreviewUrl('');
          console.log(error.message);
        }
      });
  }

  const handleShow = (shippingid) => {
    let unmounted = false;
    let source = axios.CancelToken.source();
    setIsLoading(true);
    axios({
      method: "get",
      url: `${window.location.origin}/api/shipper/jadwalPengiriman/${shippingid}`,
      cancelToken: source.token,
      headers: {
        Accept: "application/json",
        Authorization: "Bearer " + token,
      },
    }).then(response => {
      if (!unmounted) {
        console.log('show', response.data.data);
        setIdInvoice(response.data.data.link_invoice.id);
        setDetailShipping(response.data.data);
        setListDetailItem(response.data.data.link_order_item);
        setIsLoading(false);
      }
    })
    setShow(true);
    return function () {
      unmounted = true;
      source.cancel("Cancelling in cleanup");
    };
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
    $imagePreview = <img src={imagePreviewUrl} className="img-fluid img_prev" />
  }

  const handlePengajuanRetur = (idCust) => {
    history.push(`/lapangan/retur/${idCust}`);

    // history.push({
    //   pathname: `/lapangan/retur/${idCust}`,
    //   state: { isTrip: false }
    // })
  }

  const cariShipping = (e) => {
    e.preventDefault();
    setIsLoading(true);
    axios({
      method: "get",
      url: `${window.location.origin}/api/shipper/jadwalPengiriman?id_staff=${dataUser.id_staff}&nama_customer=${keyword}`,
      headers: {
        Accept: "application/json",
        Authorization: "Bearer " + token,
      },
    })
      .then(response => {
        setIsLoading(false);
        console.log('jadwal pengiriman yang dicari', response.data.data);
        setListShipping(response.data.data.data);
      })
  }

  const openGalerry = () => {
    setIsTakePhoto(false);
    setISFromGalery(true);
  }

  const openCamera = () => {
    setISFromGalery(false);
    setIsTakePhoto(true);
  }

  const takeAgain = () => {
    setISFromGalery(false);
    setIsTakePhoto(true);
    setDataUri('');
  }

  return (
    <main className="page_main shipper-css">
      {dataUser.role != null &&
        <HeaderShipper title="Jadwal Pengiriman" toBack={() => goBack(dataUser.role)} />}
      {isLoading && <LoadingIndicator />}
      <div className="page_container pt-4">
        {successMessage && <AlertComponent successMsg={successMessage} />}

        <Fragment>
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
                  onChange={(e) => setStatusShipping(e.currentTarget.value)}
                  className="ms-1 me-1">
                  {radio.name}
                </ToggleButton>
              ))}
            </ButtonGroup>
          </div>

          <ListShipping listShipping={listShipping}
            statusShipping={statusShipping} handleShow={handleShow}
            keyword={keyword} setKeyword={setKeyword} cariShipping={cariShipping}
            perluKirim={perluKirim} sudahSampai={sudahSampai}
          />

          <DetailShipping detailShipping={detailShipping}
            isLoading={isLoading} show={show} handleClose={handleClose}
            handlePengirimanSampai={handlePengirimanSampai} handlePengajuanRetur={handlePengajuanRetur}
            listDetailItem={listDetailItem} />
        </Fragment>

        <Modal show={showBuktiPengiriman} onHide={handleCloseBuktiPengirimanModal}>
          <Modal.Header closeButton>
            <Modal.Title>Bukti Pengiriman</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <Button variant="primary" onClick={openGalerry}>
              <span className="iconify fs-3 me-1" data-icon="clarity:image-gallery-solid"></span> Galeri
            </Button>

            <Button variant="secondary" onClick={openCamera} className="ms-3">
              <span className="iconify fs-3 me-1" data-icon="charm:camera"></span>Kamera
            </Button>

            {isFromGalery && <Fragment>
              <label className="form-label d-block mt-4">Foto Bukti Pengiriman <span className='text-danger'>*</span></label>
              {$imagePreview && $imagePreview}
              <input
                type="file"
                name="foto"
                id="file"
                accept="image/png, image/jpeg"
                onChange={handleImageChange} />
            </Fragment>}


            {isTakePhoto && dataUri
              ? <Fragment>
                <ImagePreview dataUri={dataUri} />
                <Button variant="warning" onClick={takeAgain} className="d-block mx-auto mt-3">
                  <span className="iconify fs-3 me-1" data-icon="ic:sharp-flip-camera-android"></span>Ulang
                </Button>
              </Fragment>
              : isTakePhoto
                ? <Camera onTakePhotoAnimationDone={handleTakePhotoAnimationDone} />
                : null
            }

          </Modal.Body>
          <Modal.Footer>
            {isFromGalery && <Button variant="success" onClick={handleSubmitBuktiPengiriman} disabled={imagePreviewUrl == '' ? true : false}>
              <span className="iconify fs-3 me-1" data-icon="material-symbols:download-done"></span> Kirim
            </Button>}
            {isTakePhoto && <Button variant="success" onClick={handleSubmitBuktiPengiriman} disabled={dataUri == '' ? true : false}>
              <span className="iconify fs-3 me-1" data-icon="material-symbols:download-done"></span>Kirim
            </Button>}
          </Modal.Footer>
        </Modal>
      </div>
    </main>
  );
}

export default ShippingShipper;
import React, { Fragment, useRef, useState, useEffect, useContext } from 'react';
import HeaderSales from './HeaderSales';
import { useParams, useHistory, Link } from 'react-router-dom'
import axios from 'axios';
import Carousel from 'react-bootstrap/Carousel';
import urlAsset from '../../config';
import { convertPrice } from "../reuse/HelperFunction";
import LoadingIndicator from '../reuse/LoadingIndicator';
import Modal from 'react-bootstrap/Modal';

const CatalogItem = () => {
  const { idCust } = useParams();
  const [listItem, setListItem] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const [diskonPertama, setDiskonPertama] = useState(0);
  const [diskonKedua, setDiskonKedua] = useState(0);
  const [diskonKetiga, setDiskonKetiga] = useState(0);
  const [diskonSales, setDiskonSales] = useState([]);
  const [orderRealTime, setOrderRealTime] = useState([]);
  const [show, setShow] = useState(false);
  const [detailItem, setDetailItem] = useState(null);

  const getCatalogItem = () => {
    setIsLoading(true);
    axios({
      method: "post",
      url: `${window.location.origin}/api/salesman/getProductCatalog`,
      headers: {
        Accept: "application/json",
      },
      data: {
        id_customer: idCust,
        diskon_sales: diskonSales
      },
    })
      .then(response => {
        console.log('katalog', response.data);
        setIsLoading(false);
        setListItem(response.data.data);
        setOrderRealTime(response.data.orderRealTime);
      })
      .catch(error => {
        console.log(error.message);
        setIsLoading(false);
      });
  }

  useEffect(() => {
    getCatalogItem();
  }, [])

  useEffect(() => {
    setDiskonSales([diskonPertama, diskonKedua, diskonKetiga]);
    // console.log('diskonSales', diskonSales);
  }, [diskonPertama, diskonKedua, diskonKetiga])

  const handleDiskonSalesClicked = () => {
    getCatalogItem();
  }

  const handleClose = () => setShow(false);

  const handleDetailItem = (idItem) => {
    let obj = listItem.find(o => o.id == idItem);
    setDetailItem(obj);
    console.log(obj);
    setShow(true);
  }

  return (
    <main className="page_main">
      <HeaderSales title="Katalog" />
      {isLoading && <LoadingIndicator />}
      <div className="page_container py-4 px-3">

        <div className="add-diskon">
          <div className="mb-3">
            <label className="form-label">Diskon 1</label>
            <input type="number" min="0" max="0" value={diskonPertama || ''} onChange={(e) => setDiskonPertama(e.target.value)} className="form-control" />
          </div>
          <div className="mb-3">
            <label className="form-label">Diskon 2</label>
            <input type="number" min="0" max="0" value={diskonKedua || ''} onChange={(e) => setDiskonKedua(e.target.value)} className="form-control" />
          </div>
          <div className="mb-3">
            <label className="form-label">Diskon 3</label>
            <input type="number" min="0" max="0" value={diskonKetiga || ''} onChange={(e) => setDiskonKetiga(e.target.value)} className="form-control" />
          </div>
          <div className="row justify-content-end">
            <div className="col d-flex justify-content-end">
              <button className='btn btn-warning' onClick={handleDiskonSalesClicked}>OK</button>
            </div>
          </div>
        </div>

        {!isLoading && listItem.length > 0 && listItem.map((produk) => (
          <div className="cart_item" key={produk.id}>
            <div className="d-flex">
              {produk.gambar.length > 0 ?
                <Carousel variant="dark" slide={false}>
                  {produk.gambar.map((galery, index) => (
                    <Carousel.Item key={index}>
                      <img src={`${urlAsset}/storage/item/${galery.image}`} className="item_catalogimage" />
                    </Carousel.Item>
                  ))}
                </Carousel> :
                <img src={`${urlAsset}/images/default_produk.png`} className="item_catalogimage" />
              }
            </div>

            <div className="detail_item" onClick={() => handleDetailItem(produk.id)}>
              <h1 className="mb-0 fs-6 fw-bold">{produk.nama}</h1>
              {produk.harga_satuan > produk.harga_diskon_sales ?
                <Fragment>
                  <p className="mb-0 fs-7 text-decoration-line-through">{convertPrice(produk.harga_satuan)} / {produk.satuan}</p>
                  {produk.harga_diskon_sales && <p className="mb-0 fs-7">
                    <b className="text-danger">
                      {convertPrice(produk.harga_diskon_sales)} / {produk.satuan}
                    </b>
                  </p>}
                </Fragment> :
                <p className="mb-0 fs-7">{convertPrice(produk.harga_satuan)} / {produk.satuan}</p>
              }
            </div>
          </div>
        ))}

        {detailItem && <Modal show={show} onHide={handleClose}>
          <Modal.Header closeButton>
            <Modal.Title>Detail Item</Modal.Title>
          </Modal.Header>
          <Modal.Body>

            <div className="cart_item">
              <div className="d-flex align-items-baseline">
                {detailItem.gambar.length > 0 ?
                  <Carousel variant="dark" slide={false}>
                    {detailItem.gambar.map((galery, index) => (
                      <Carousel.Item key={index}>
                        <img src={`${urlAsset}/storage/item/${galery.image}`} className="item_catalogimage" />
                      </Carousel.Item>
                    ))}
                  </Carousel> :
                  <img src={`${urlAsset}/images/default_produk.png`} className="item_catalogimage" />
                }
              </div>

              <div className="detail_item">
                <h1 className="mb-0 fs-6 fw-bold">{detailItem.nama}</h1>
                <p className="mb-0 fs-7">{convertPrice(detailItem.harga_satuan)} / {detailItem.satuan}</p>
                <p className='mb-0 mt-3 fw-bold'>Stok: </p>
                <table className='table table-bordered border-secondary mb-0'>
                  <thead>
                    <tr>
                      <th scope="col">R.Time</th>
                      <th scope="col">Today</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{detailItem.stok - (orderRealTime[detailItem.id] ?? 0)} {detailItem.satuan}</td>
                      <td>{detailItem.stok} {detailItem.satuan}</td>
                    </tr>
                  </tbody>
                </table>
                <p className='mb-0 mt-3 fw-bold'>Deskripsi: </p>
                <p className="mb-0">{detailItem.deskripsi ?? null}</p>
              </div>
            </div>

          </Modal.Body>
        </Modal>}

      </div>
    </main >
  );
}

export default CatalogItem;
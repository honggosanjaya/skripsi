import React, { Fragment, useRef, useState, useEffect, useContext } from 'react';
import HeaderSales from './HeaderSales';
import { useParams, useHistory, Link } from 'react-router-dom';
import { KeranjangSalesContext } from '../../contexts/KeranjangSalesContext';
import axios from 'axios';
import Carousel from 'react-bootstrap/Carousel';
import LoadingIndicator from '../reuse/LoadingIndicator';
import urlAsset from '../../config';
import { convertPrice } from "../reuse/HelperFunction";

const DetailCatalogItem = () => {
  const { tipeHarga, idItem } = useParams();
  // const { idCustomer } = useContext(KeranjangSalesContext);
  const history = useHistory();
  const [isLoading, setIsLoading] = useState(false);
  const [item, setItem] = useState(null);
  const [relatedItem, setRelatedItem] = useState(null);
  const [categoryItem, setCategoryItem] = useState(null);
  const [newItem, setnewItem] = useState(null);
  const [diskonPertama, setDiskonPertama] = useState(0);
  const [diskonKedua, setDiskonKedua] = useState(0);
  const [diskonKetiga, setDiskonKetiga] = useState(0);
  const [diskonSales, setDiskonSales] = useState([]);
  const [isShowCalculator, setIsShowCalculator] = useState(false);

  const getDetailCatalogItem = () => {
    setIsLoading(true);
    axios({
      method: "post",
      url: `${window.location.origin}/api/salesman/getDetailProductCatalog`,
      headers: {
        Accept: "application/json",
      },
      data: {
        id_item: idItem,
        tipe_harga: tipeHarga,
        diskon_sales: diskonSales
      },
    })
      .then(response => {
        console.log('data', response.data.data);
        console.log('item', response.data.data.item);
        setItem(response.data.data.item[0]);
        setRelatedItem(response.data.data.related_item);
        setCategoryItem(response.data.data.category_item);
        setnewItem(response.data.data.new_item);
        setIsLoading(false);
      })
      .catch(error => {
        console.log(error.message);
        setIsLoading(false);
      });
  }

  useEffect(() => {
    if (idItem) {
      getDetailCatalogItem();
    }
  }, [idItem]);

  useEffect(() => {
    setDiskonSales([diskonPertama, diskonKedua, diskonKetiga]);
  }, [diskonPertama, diskonKedua, diskonKetiga]);

  const handleDiskonSalesClicked = () => {
    getDetailCatalogItem();
  }

  const handleToggleShowCalculator = () => {
    if (isShowCalculator == true) {
      setIsShowCalculator(false);
    } else {
      setIsShowCalculator(true);
    }
  }

  const handleClickProduct = (idItem) => {
    history.push(`/salesman/detailcatalog/${tipeHarga}/${idItem}`);
  }

  return (
    <main className="page_main">
      <HeaderSales title="Katalog" />
      {isLoading && <LoadingIndicator />}
      <div className="page_container py-4 px-3 detail-katalog">
        {item && <Fragment>
          <div className="detail-product-info">
            {item.link_galery_item && item.link_galery_item.length > 0 ?
              <Carousel variant="dark" slide={false}>
                {item.link_galery_item.map((galery, index) => (
                  <Carousel.Item key={index}>
                    <img src={`${urlAsset}/storage/item/${galery.image}`} className="img-fluid" />
                  </Carousel.Item>
                ))}
              </Carousel> :
              <img src={`${urlAsset}/images/default_produk.png`} className="img-fluid carousel-placeholder" />}


            <h1 className="fs-5 mt-2">
              <b>
                {item.harga_satuan > item.harga_diskon_sales ?
                  <Fragment>
                    <h1 className="fs-5">
                      <b className="text-decoration-line-through">{convertPrice(item.harga_satuan)}</b>
                      {<button className="btn btn-sm btn-primary ms-2" onClick={handleToggleShowCalculator}>
                        <span className="iconify fs-4" data-icon="bxs:calculator"></span>
                      </button>}
                    </h1>
                    {item.harga_diskon_sales && <h1 className="fs-5">
                      <b>{convertPrice(item.harga_diskon_sales)}</b>
                    </h1>}
                  </Fragment> :
                  <h1 className="fs-5">
                    <b>{convertPrice(item.harga_satuan)}</b>
                    {<button className="btn btn-sm btn-primary ms-2" onClick={handleToggleShowCalculator}>
                      <span className="iconify fs-4" data-icon="bxs:calculator"></span>
                    </button>}
                  </h1>}
              </b>
            </h1>

            {isShowCalculator &&
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
              </div>}

            <h2 className="fs-6 text-uppercase">{item.nama}</h2>
            <span>Stok {item.stok}</span>
          </div>
          <hr />
          {item.deskripsi && <div>
            <h1 className="fs-5"><b>Deskripsi Produk</b></h1>
            <h2 className="fs-6">{item.deskripsi}</h2>
            <hr />
          </div>}
        </Fragment>}


        {relatedItem && relatedItem.length > 0 &&
          <Fragment>
            <div className="related-product">
              <h1 className="fs-5"><b>Related Produk</b></h1>
              <div className="horizontal-scroll-wrapper">
                {relatedItem.map((item) => (
                  <div className="card-product" key={item.id} onClick={() => handleClickProduct(item.id)}>
                    {item.gambar ? <img src={`${urlAsset}/storage/item/${item.gambar}`} className="img-fluid" />
                      : <img src={`${urlAsset}/images/default_produk.png`} className="img-fluid" />
                    }
                    <div className="card-product--text">
                      <h3 className="fs-6 fw-normal text-capitalize">{item.nama}</h3>
                      <h1 className="fs-6"><b>
                        {tipeHarga == 3 && item.harga3_satuan ? convertPrice(item.harga3_satuan) :
                          tipeHarga == 2 && item.harga2_satuan ? convertPrice(item.harga2_satuan) :
                            item.harga1_satuan && convertPrice(item.harga1_satuan)}
                      </b></h1>
                    </div>
                  </div>
                ))}
              </div>
            </div>
            <hr />
          </Fragment>}

        {categoryItem && categoryItem.length > 0 &&
          <Fragment>
            <div className="related-product">
              <h1 className="fs-5"><b>Produk Dengan Category Serupa</b></h1>
              <div className="horizontal-scroll-wrapper">
                {categoryItem.map((item) => (
                  <div className="card-product" key={item.id} onClick={() => handleClickProduct(item.id)}>
                    {item.gambar ? <img src={`${urlAsset}/storage/item/${item.gambar}`} className="img-fluid" />
                      : <img src={`${urlAsset}/images/default_produk.png`} className="img-fluid" />
                    }
                    <div className="card-product--text">
                      <h3 className="fs-6 fw-normal text-capitalize">{item.nama}</h3>
                      <h1 className="fs-6"><b>
                        {tipeHarga == 3 && item.harga3_satuan ? convertPrice(item.harga3_satuan) :
                          tipeHarga == 2 && item.harga2_satuan ? convertPrice(item.harga2_satuan) :
                            item.harga1_satuan && convertPrice(item.harga1_satuan)}
                      </b></h1>
                    </div>
                  </div>
                ))}
              </div>
            </div>
            <hr />
          </Fragment>}

        {newItem && newItem.length > 0 &&
          <div className="related-product">
            <h1 className="fs-5"><b>Produk Terbaru</b></h1>
            <div className="horizontal-scroll-wrapper">
              {newItem.map((item) => (
                <div className="card-product" key={item.id} onClick={() => handleClickProduct(item.id)}>
                  {item.gambar ? <img src={`${urlAsset}/storage/item/${item.gambar}`} className="img-fluid" />
                    : <img src={`${urlAsset}/images/default_produk.png`} className="img-fluid" />
                  }
                  <div className="card-product--text">
                    <h3 className="fs-6 fw-normal text-capitalize">{item.nama}</h3>
                    <h1 className="fs-6"><b>
                      {tipeHarga == 3 && item.harga3_satuan ? convertPrice(item.harga3_satuan) :
                        tipeHarga == 2 && item.harga2_satuan ? convertPrice(item.harga2_satuan) :
                          item.harga1_satuan && convertPrice(item.harga1_satuan)}
                    </b></h1>
                  </div>
                </div>
              ))}
            </div>
          </div>}
      </div>
    </main>
  );
}

export default DetailCatalogItem;
import React, { Fragment, useRef, useState, useEffect, useContext } from 'react';
import HeaderSales from './HeaderSales';
import { useParams, useHistory, Link } from 'react-router-dom';
import { KeranjangSalesContext } from '../../contexts/KeranjangSalesContext';
import axios from 'axios';
import Carousel from 'react-bootstrap/Carousel';
import urlAsset from '../../config';
import { convertPrice } from "../reuse/HelperFunction";
import LoadingIndicator from '../reuse/LoadingIndicator';

const CatalogItem = () => {
  const { idCust } = useParams();
  const { setIdCustomer } = useContext(KeranjangSalesContext);
  const history = useHistory();
  const [listItem, setListItem] = useState([]);
  const [filteredListItem, setFilteredListItem] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const [tipeHarga, setTipeHarga] = useState(null);

  useEffect(() => {
    setIsLoading(true);
    axios({
      method: "post",
      url: `${window.location.origin}/api/salesman/getProductCatalog`,
      headers: {
        Accept: "application/json",
      },
      data: {
        id_customer: idCust
      },
    })
      .then(response => {
        console.log('katalog', response.data);
        setIsLoading(false);
        setListItem(response.data.data);
        setFilteredListItem(response.data.data);
        setTipeHarga(response.data.tipe_harga);
      })
      .catch(error => {
        console.log(error.message);
        setIsLoading(false);
      });

    if (idCust != null) {
      setIdCustomer(idCust);
    }
  }, [])

  const handleDetailItem = (idItem) => {
    history.push(`/salesman/detailcatalog/${tipeHarga}/${idItem}`);
  }

  const onSearchHandler = (val) => {
    let keyword = val.toLowerCase();
    const filteredItems = listItem.filter((item) => {
      return item.nama.toLowerCase().includes(keyword)
    });
    setFilteredListItem(filteredItems);
  }

  return (
    <main className="page_main">
      <HeaderSales title="Katalog" />
      {isLoading && <LoadingIndicator />}
      <div className="page_container py-4 px-3">

        <input type="text" className="form-control"
          placeholder="Pencarian..."
          onChange={(e) => onSearchHandler(e.target.value)} />

        {!isLoading && filteredListItem.length > 0 && filteredListItem.map((produk) => (
          <div className="cart_item" key={produk.id}>
            <div className="d-flex">
              {produk.gambar.length > 1 &&
                <Carousel variant="dark" slide={false}>
                  {produk.gambar.map((galery, index) => (
                    <Carousel.Item key={index}>
                      <img src={`${urlAsset}/storage/item/${galery.image}`} className="item_catalogimage" />
                    </Carousel.Item>
                  ))}
                </Carousel>}
              {produk.gambar.length == 1 &&
                produk.gambar.map((galery, index) => (
                  <img src={`${urlAsset}/storage/item/${galery.image}`} className="item_catalogimage" key={index} />
                ))}
              {produk.gambar.length == 0 && <img src={`${urlAsset}/images/default_produk.png`} className="item_catalogimage" />}
            </div>

            <div className="detail_item" onClick={() => handleDetailItem(produk.id)}>
              <h1 className="mb-0 fs-6 fw-bold">{produk.nama}</h1>
              <p className="mb-0 fs-7">{convertPrice(produk.harga_satuan)} / {produk.satuan}</p>
            </div>
          </div>
        ))}
      </div>
    </main>
  );
}

export default CatalogItem;
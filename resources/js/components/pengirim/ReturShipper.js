import React, { useContext, useState, useEffect, useRef, Fragment } from 'react';
import axios from 'axios';
import { useHistory, useParams } from 'react-router-dom';
import HeaderShipper from './HeaderShipper';
import { convertPrice } from "../reuse/HelperFunction";
import ReturDB from '../reuse/ReturDb';
import urlAsset from '../../config';
import { UserContext } from '../../contexts/UserContext';
import LoadingIndicator from '../reuse/LoadingIndicator';
import { ReturContext } from '../../contexts/ReturContext';
import { AuthContext } from '../../contexts/AuthContext';

let source;
const ReturShipper = () => {
  const { idCust } = useParams();
  const { token } = useContext(AuthContext);
  const { idInvoice } = useContext(ReturContext);
  const [historyItems, setHistoryItems] = useState([]);
  const [cartItems, setCartItems] = useState([]);
  const { dataUser } = useContext(UserContext);
  const [newHistoryItems, setNewHistoryItems] = useState(historyItems);
  const [isShowProduct, setIsShowProduct] = useState(true);
  const [isLoading, setIsLoading] = useState(false);
  const [customerDiskon, setCustomerDiskon] = useState(null);
  const Swal = require('sweetalert2');
  const redirect = useHistory();
  const _isMounted = useRef(true);

  useEffect(() => {
    source = axios.CancelToken.source();
    return () => {
      _isMounted.current = false;
      if (source) {
        source.cancel("Cancelling in cleanup");
      }
    }
  }, []);

  const getAllProduks = () => {
    const cartItems = ReturDB.getAllProduks();
    cartItems.then((response) => {
      setCartItems(response);
    })
  }

  useEffect(() => {
    getAllProduks();
    let unmounted = false;
    let source = axios.CancelToken.source();
    axios({
      method: "get",
      url: `${window.location.origin}/api/salesman/historyitems/${idCust}`,
      cancelToken: source.token,
      headers: {
        Accept: "application/json",
      },
    })
      .then((response) => {
        if (!unmounted) {
          console.log('history yang dipesan', response);
          setHistoryItems(response.data.data.history);
          setCustomerDiskon(response.data.data.customer.link_customer_type.diskon);
        }
      })
      .catch((error) => {
        if (!unmounted) {
          console.log(error.message);
        }
      });

    return function () {
      unmounted = true;
      source.cancel("Cancelling in cleanup");
    };
  }, []);

  useEffect(() => {
    setNewHistoryItems(historyItems);
  }, [historyItems]);

  const checkifexist = (item) => {
    const exist = cartItems.find((x) => x.id === item.id);
    if (exist) {
      if (isNaN(exist.kuantitas)) return 0
      else return exist.kuantitas;
    }
    else return 0;
  }

  const handleKurangJumlah = (item, alasan, harga) => {
    const itemincart = cartItems.find((x) => x.id === item.id);
    if ((alasan == undefined || alasan == '') && itemincart.kuantitas > 1) {
      const exist = newHistoryItems.find((x) => x.id_item === item.id);
      if (exist) {
        setNewHistoryItems(
          newHistoryItems.map((x) => {
            if (x.id_item === item.id) return { ...exist, error: 'Alasan retur tidak boleh kosong' }
            else return x
          }));
      }
    } else {
      const exist = cartItems.find((x) => x.id === item.id);
      if (exist && exist.kuantitas > 1) {
        const produk = {
          id: item.id,
          nama: item.nama,
          id_customer: parseInt(idCust),
          kuantitas: exist.kuantitas - 1,
          harga_satuan: harga,
          gambar: item.gambar,
          alasan: alasan
        };
        ReturDB.updateProduk(produk);
        getAllProduks();
      }

      if (exist && exist.kuantitas == 1) {
        let setuju = confirm(`apakah anda yakin ingin membatalkan retur produk ${item.nama} ?`);
        if (setuju) {
          ReturDB.deleteProduk(item.id);
          getAllProduks();
        }
      }
    }
  }

  const handleTambahJumlah = (item, alasan, harga) => {
    if (alasan == undefined || alasan == '') {
      const exist = newHistoryItems.find((x) => x.id_item === item.id);
      if (exist) {
        setNewHistoryItems(
          newHistoryItems.map((x) => {
            if (x.id_item === item.id) return { ...exist, error: 'Alasan retur tidak boleh kosong' }
            else return x
          }));
      }
    } else {
      const exist = cartItems.find((x) => x.id === item.id);
      if (exist) {
        const produk = {
          id: item.id,
          nama: item.nama,
          id_customer: parseInt(idCust),
          kuantitas: exist.kuantitas + 1,
          harga_satuan: harga,
          gambar: item.gambar,
          alasan: alasan
        };
        ReturDB.updateProduk(produk);
        getAllProduks();
      }
      else {
        const produk = {
          id: item.id,
          nama: item.nama,
          id_customer: parseInt(idCust),
          kuantitas: 1,
          harga_satuan: harga,
          gambar: item.gambar,
          alasan: alasan
        };
        ReturDB.putProduk(produk);
        getAllProduks();
      }
    }
  }

  const handleValueChange = (item, newVal, alasan, harga) => {
    if (alasan == undefined || alasan == '') {
      const exist = newHistoryItems.find((x) => x.id_item === item.id);
      if (exist) {
        setNewHistoryItems(
          newHistoryItems.map((x) => {
            if (x.id_item === item.id) return { ...exist, error: 'Alasan retur tidak boleh kosong' }
            else return x
          }));
      }
    } else {
      const exist = cartItems.find((x) => x.id === item.id);
      if (exist) {
        if (isNaN(newVal) == false) {
          const produk = {
            id: item.id,
            nama: item.nama,
            id_customer: parseInt(idCust),
            kuantitas: isNaN(parseInt(newVal)) ? 0 : parseInt(newVal),
            harga_satuan: harga,
            gambar: item.gambar,
            alasan: alasan
          };
          ReturDB.updateProduk(produk);
          getAllProduks();
        }
      } else {
        if (isNaN(newVal) == false) {
          const produk = {
            id: item.id,
            nama: item.nama,
            id_customer: parseInt(idCust),
            kuantitas: isNaN(parseInt(newVal)) ? 0 : parseInt(newVal),
            harga_satuan: harga,
            gambar: item.gambar,
            alasan: alasan
          };
          ReturDB.putProduk(produk);
          getAllProduks();
        }
      }
    }
  }

  const handleAlasanChange = (item, alasan, harga) => {
    const exist = newHistoryItems.find((x) => x.id_item === item.id_item);
    if (exist) {
      setNewHistoryItems(
        newHistoryItems.map((x) => {
          if (x.id_item === item.id_item) return { ...exist, alasan: alasan }
          else return x
        }));
    }
  }

  const hapusSemuaProduk = () => {
    cartItems.map((item) => {
      ReturDB.deleteProduk(item.id);
      getAllProduks();
    })
  }

  const goBack = () => {
    hapusSemuaProduk();
    redirect.push('/shipper/jadwal');
  }

  const handlePengajuanRetur = () => {
    setIsLoading(true);
    source = axios.CancelToken.source();
    axios({
      method: "post",
      url: `${window.location.origin}/api/shipper/retur`,
      headers: {
        Accept: "application/json",
        Authorization: "Bearer " + token,
      },
      data: {
        cartItems: cartItems,
        id_staff_pengaju: dataUser.id_staff,
        id_customer: idCust,
        id_invoice: idInvoice
      },
      cancelToken: source.token,
    })
      .then(response => {
        if (_isMounted.current) {
          setIsLoading(false);
          hapusSemuaProduk();
          Swal.fire({
            title: 'Success',
            text: response.data.message,
            showDenyButton: false,
            showCancelButton: false,
            confirmButtonText: 'OK',
            icon: 'success',
          }).then((result) => {
            if (result.isConfirmed) {
              redirect.push('/shipper/jadwal');
            }
          })
        }
      })
      .catch(error => {
        if (_isMounted.current) {
          setIsLoading(false);
          Swal.fire({
            title: 'Ups ada yang salah!',
            text: error.message,
            icon: 'error',
          })
        }
      });
  }

  return (
    <main className='page_main'>
      <HeaderShipper title="Retur" toBack={goBack} />
      <div className="page_container pt-4">
        {isLoading && <LoadingIndicator />}
        <button className="btn btn-primary" onClick={() => setIsShowProduct(!isShowProduct)}>{isShowProduct ? 'Sembunyikan' : 'Lihat Produk'}</button>
        {isShowProduct &&
          <div className="retur-product_wrapper my-3 border">
            {newHistoryItems.map((item) => (
              <div className="list_history-item p-3" key={item.id}>
                <div className="d-flex align-items-center">
                  {item.link_item.gambar ?
                    <img src={`${urlAsset}/storage/item/${item.link_item.gambar}`} className="item_image me-3" />
                    : <img src={`${urlAsset}/images/default_produk.png`} className="item_image me-3" />}
                  <div>
                    <h2 className='fs-6 text-capitalize fw-bold'>{item.link_item.nama}</h2>
                    <p className='mb-0'>{convertPrice(item.link_item.harga_satuan - item.link_item.harga_satuan * customerDiskon / 100)}</p>
                  </div>
                </div>

                <div className="row mt-2 align-items-center">
                  <div className="col-5">
                    <label className="form-label">Jumlah Retur</label>
                  </div>
                  <div className="col-7 d-flex justify-content-around">
                    <button className="btn btn-primary btn_qty" onClick={() => handleKurangJumlah(item.link_item, item.alasan, item.link_item.harga_satuan - item.link_item.harga_satuan * customerDiskon / 100)}> - </button>
                    <input type="number" className="form-control mx-2"
                      value={checkifexist(item.link_item)}
                      onChange={(e) => handleValueChange(item.link_item, e.target.value, item.alasan, item.link_item.harga_satuan - item.link_item.harga_satuan * customerDiskon / 100)} />
                    <button className="btn btn-primary btn_qty" onClick={() => handleTambahJumlah(item.link_item, item.alasan, item.link_item.harga_satuan - item.link_item.harga_satuan * customerDiskon / 100)}> + </button>
                  </div>
                </div>

                <label className="form-label">Alasan Retur</label>
                <input type="text" className="form-control"
                  value={item.alasan ? item.alasan : ''}
                  onChange={(e) => handleAlasanChange(item, e.target.value)}
                />
                {item.error && !item.alasan && <small className="text-danger mb-0">{item.error}</small>}
              </div>
            ))}
          </div>}

        {cartItems.length > 0 && <div className="mt-3">
          <h1 className='fs-5 fw-bold mt-4'>Rincian Retur</h1>
          {cartItems.map((item) => (
            <div className="info-product_retur mt-2" key={item.id}>
              <div className="d-flex align-items-center border-bottom">
                {item.gambar ? <img src={`${urlAsset}/images/${item.gambar}`} className="img-fluid item_image me-3" />
                  : <img src={`${urlAsset}/images/default_produk.png`} className="img-fluid item_image me-3" />}
                <div>
                  <h2 className='fs-6 mb-0 fw-bold'>{item.nama}</h2>
                  <h5 className='mb-0'>{item.kuantitas} barang</h5>
                </div>
              </div>
              <span className='title'>Harga Satuan</span><span className='desc'>{item.harga_satuan}</span>
              <span className='title'>Alasan</span><span className='desc'>{item.alasan}</span>
            </div>
          ))}
          {cartItems.length > 0 &&
            <Fragment>
              <div className="row justify-content-end mt-4">
                <div className="col d-flex justify-content-end">
                  <button className="btn btn-success mt-3" onClick={handlePengajuanRetur}><span className="iconify fs-3 me-1" data-icon="ic:baseline-assignment-return"></span>Ajukan Retur</button>
                </div>
              </div>
            </Fragment>
          }
        </div>}
      </div>
    </main>
  );
}

export default ReturShipper;
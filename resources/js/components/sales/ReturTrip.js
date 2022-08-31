import React, { useEffect, useState, useContext } from 'react';
import axios from 'axios';
import HeaderSales from './HeaderSales';
import { useHistory, useParams } from 'react-router-dom';
import urlAsset from '../../config';
import ReturDB from '../reuse/ReturDb';
import { convertPrice } from "../reuse/HelperFunction";
import { AuthContext } from '../../contexts/AuthContext';
import { UserContext } from '../../contexts/UserContext';

const ReturTrip = () => {
  const { id } = useParams();
  const redirect = useHistory();
  const { token } = useContext(AuthContext);
  const { dataUser } = useContext(UserContext);
  const [historyItems, setHistoryItems] = useState([]);
  const [latestOrderItems, setLatestOrderItems] = useState({});
  const [newHistoryItems, setNewHistoryItems] = useState(historyItems);
  const [cartItems, setCartItems] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const Swal = require('sweetalert2');

  const getAllProduks = () => {
    const cartItems = ReturDB.getAllProduks();
    cartItems.then((response) => {
      setCartItems(response);
    })
  }

  useEffect(() => {
    getAllProduks();

    axios({
      method: "get",
      url: `${window.location.origin}/api/salesman/historyitems/${idCust}`,
      cancelToken: source.token,
      headers: {
        Accept: "application/json",
      },
    })
      .then((response) => {
        console.log('lala', response.data.data);
        setHistoryItems(response.data.data.history);
        setLatestOrderItems(response.data.data.latestOrderItems);
      })
      .catch((error) => {
        console.log(error.message);
      });
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
    redirect.push('/lapangan/jadwal');
  }


  const handlePengajuanTripRetur = () => {
    setIsLoading(true);
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
        id_invoice: null
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
              redirect.push('/lapangan/jadwal');
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
    <main className="page_main">
      <HeaderSales title="Retur" />
      <div className="page_container pt-4">
        {isLoading && <LoadingIndicator />}

        <div className="retur-product_wrapper my-3 border">
          {Object.keys(latestOrderItems).length > 0 && newHistoryItems.map((item) => (
            <div className="list_history-item p-3" key={item.id_item}>
              <div className="d-flex align-items-center">
                {item.link_item.gambar ?
                  <img src={`${urlAsset}/storage/item/${item.link_item.gambar}`} className="item_image me-3" />
                  : <img src={`${urlAsset}/images/default_produk.png`} className="item_image me-3" />}
                <div>
                  <h2 className='fs-6 text-capitalize fw-bold'>{item.link_item.nama}</h2>
                  <p className="mb-0">
                    {convertPrice(latestOrderItems[item.id_item][0].harga_satuan)}
                  </p>
                </div>
              </div>

              <div className="row mt-2 align-items-center">
                <div className="col-5">
                  <label className="form-label">Jumlah Retur</label>
                </div>
                <div className="col-7 d-flex justify-content-around">
                  <button className="btn btn-primary btn_qty"
                    onClick={() => handleKurangJumlah(item.link_item, item.alasan, latestOrderItems[item.id_item][0].harga_satuan)}> - </button>
                  <input type="number" className="form-control mx-2"
                    value={checkifexist(item.link_item)}
                    onChange={(e) => handleValueChange(item.link_item, e.target.value, item.alasan, latestOrderItems[item.id_item][0].harga_satuan)} />
                  <button className="btn btn-primary btn_qty" onClick={() => handleTambahJumlah(item.link_item, item.alasan, latestOrderItems[item.id_item][0].harga_satuan)}> + </button>
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
        </div>

        {cartItems.length > 0 && <div className="mt-3">
          <h1 className='fs-5 fw-bold mt-4'>Rincian Retur</h1>
          {cartItems.map((item) => (
            <div className="info-product_retur mt-2" key={item.id}>
              <div className="d-flex align-items-center border-bottom">
                {item.gambar ? <img src={`${urlAsset}/storage/item/${item.gambar}`} className="img-fluid item_image me-3" />
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
                  <button className="btn btn-success mt-3" onClick={handlePengajuanTripRetur} disabled={isLoading}>
                    <span className="iconify fs-3 me-1" data-icon="ic:baseline-assignment-return"></span>Ajukan Retur
                  </button>
                </div>
              </div>
            </Fragment>
          }
        </div>}
      </div>
    </main>
  );
}

export default ReturTrip;
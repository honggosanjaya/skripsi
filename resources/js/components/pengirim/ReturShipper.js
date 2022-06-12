import React, { useContext, useState, useEffect, Fragment } from 'react';
import { useHistory, useParams } from 'react-router-dom';
import HeaderShipper from './HeaderShipper';
import { convertPrice } from "../reuse/HelperFunction";
import ReturDB from '../reuse/ReturDb';
import urlAsset from '../../config';
import { UserContext } from '../../contexts/UserContext';
import LoadingIndicator from '../reuse/LoadingIndicator';
import { ReturContext } from '../../contexts/ReturContext';

const ReturShipper = () => {
  const { idCust } = useParams();
  const { idInvoice } = useContext(ReturContext);
  const [historyItems, setHistoryItems] = useState([]);
  const [cartItems, setCartItems] = useState([]);
  const { dataUser } = useContext(UserContext);
  const [newHistoryItems, setNewHistoryItems] = useState(historyItems);
  const [isShowProduct, setIsShowProduct] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const Swal = require('sweetalert2');
  const redirect = useHistory();

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
      headers: {
        Accept: "application/json",
      },
    })
      .then((response) => {
        console.log('history yang dipesan', response.data.data);
        setHistoryItems(response.data.data);
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

  const handleKurangJumlah = (item, alasan) => {
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
          harga_satuan: item.harga_satuan,
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

  const handleTambahJumlah = (item, alasan) => {
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
          harga_satuan: item.harga_satuan,
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
          harga_satuan: item.harga_satuan,
          gambar: item.gambar,
          alasan: alasan
        };
        ReturDB.putProduk(produk);
        getAllProduks();
      }
    }
  }

  const handleValueChange = (item, newVal, alasan) => {
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
            harga_satuan: item.harga_satuan,
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
            harga_satuan: item.harga_satuan,
            gambar: item.gambar,
            alasan: alasan
          };
          ReturDB.putProduk(produk);
          getAllProduks();
        }
      }
    }
  }

  const handleAlasanChange = (item, alasan) => {
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

  const handleToggleShowProduct = () => {
    setIsShowProduct(!isShowProduct);
  }

  const handlePengajuanRetur = () => {
    setIsLoading(true);
    axios({
      method: "post",
      url: `${window.location.origin}/api/shipper/retur`,
      headers: {
        Accept: "application/json",
      },
      data: {
        cartItems: cartItems,
        id_staff_pengaju: dataUser.id_staff,
        id_customer: idCust,
        id_invoice: idInvoice
      }
    })
      .then(response => {
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
      })
      .catch(error => {
        setIsLoading(false);
        Swal.fire({
          title: 'Ups ada yang salah!',
          text: error.message,
          icon: 'error',
        })
      });
  }

  return (
    <main className='page_main'>
      <HeaderShipper title="Retur" />
      <div className="page_container pt-4">
        {isLoading && <LoadingIndicator />}
        <button className="btn btn-primary" onClick={handleToggleShowProduct}>{isShowProduct ? 'Sembunyikan' : 'Lihat Produk'}</button>
        {isShowProduct &&
          <div className="retur-product_wrapper my-3 border">
            {newHistoryItems.map((item) => (
              <div className="list_history-item p-3" key={item.id}>
                <div className="d-flex align-items-center">
                  {item.link_item.gambar ?
                    <img src={`${urlAsset}/images/${item.link_item.gambar}`} className="item_image me-3" />
                    : <img src={`${urlAsset}/images/default_produk.png`} className="item_image me-3" />}
                  <div>
                    <h2 className='fs-6 text-capitalize fw-bold'>{item.link_item.nama}</h2>
                    <p className='mb-0'>{convertPrice(item.link_item.harga_satuan)}</p>
                  </div>
                </div>

                <div className="row mt-2 align-items-center">
                  <div className="col-5">
                    <label className="form-label">Jumlah Retur</label>
                  </div>
                  <div className="col-7 d-flex justify-content-around">
                    <button className="btn btn-primary btn_qty" onClick={() => handleKurangJumlah(item.link_item, item.alasan)}> - </button>
                    <input type="number" className="form-control mx-2"
                      value={checkifexist(item.link_item)}
                      onChange={(e) => handleValueChange(item.link_item, e.target.value, item.alasan)} />
                    <button className="btn btn-primary btn_qty" onClick={() => handleTambahJumlah(item.link_item, item.alasan)}> + </button>
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
          <h1 className='fs-5 fw-bold'>Rincian Retur</h1>
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
          {cartItems.length > 0 && <button className="btn btn-warning mt-3 float-end" onClick={handlePengajuanRetur}>Ajukan Retur</button>}
        </div>}
      </div>
    </main>
  );
}

export default ReturShipper;
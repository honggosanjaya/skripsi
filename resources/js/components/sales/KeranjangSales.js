import React, { useContext, useEffect, useState, createContext, Fragment } from 'react';
import { useParams } from 'react-router-dom';
import KeranjangDB from '../reuse/KeranjangDB';
import { Link, useHistory } from "react-router-dom";
import { convertPrice } from "../reuse/HelperFunction";
import { KeranjangSalesContext } from '../../contexts/KeranjangSalesContext';
import AlertComponent from '../reuse/AlertComponent';
import { AuthContext } from '../../contexts/AuthContext';
import { UserContext } from "../../contexts/UserContext";
import Table from 'react-bootstrap/Table';
import LoadingIndicator from '../reuse/LoadingIndicator';
import urlAsset from '../../config';

const KeranjangSales = ({ location }) => {
  const { dataUser, loadingDataUser } = useContext(UserContext);
  const history = useHistory();
  const { idCust } = useParams();
  const { token } = useContext(AuthContext);
  const { produks, setProduks, getAllProduks } = useContext(KeranjangSalesContext);
  const [errorMessage, setErrorMessage] = useState(null);
  const [successMessage, setSuccessMessage] = useState(null);
  const [isLoading, setIsLoading] = useState(false);

  const [estimasiWaktuPengiriman, setEstimasiWaktuPengiriman] = useState(null);
  const [keteranganOrderItem, setKeteranganOrderItem] = useState(null);
  const [idCustomer, setIdCustomer] = useState(null);
  const [limitPembelian, setLimitPembelian] = useState(0);
  const [dataCustType, setDataCustType] = useState({});
  const [pilihanRetur, setPilihanRetur] = useState([]);
  const [tipeRetur, setTipeRetur] = useState("1");

  const [kodeEvent, setKodeEvent] = useState('');
  const [errorKodeEvent, setErrorKodeEvent] = useState(null);
  const [hargaPromo, setHargaPromo] = useState(0);

  const { state: idTrip } = location;

  let totalHarga = 0;

  const goback = () => {
    history.push({
      pathname: `/salesman/order/${idCust}`,
      state: idTrip // your data array of objects
    })
  }

  useEffect(() => {
    getAllProduks();
    axios({
      method: "get",
      url: `${window.location.origin}/api/tipeRetur`,
      headers: {
        Accept: "application/json",
      },
    })
      .then(response => {
        console.log('type retur', response.data.data);
        setPilihanRetur(response.data.data);
      })
      .catch(error => {
        setErrorMessage(error.message);
      });


    console.log(idTrip);
  }, [])



  useEffect(() => {
    if (idCustomer) {
      axios({
        method: "get",
        url: `${window.location.origin}/api/tripCustomer/${idCustomer}`,
        headers: {
          Accept: "application/json",
        },
      })
        .then(response => {
          setLimitPembelian(response.data.data.limit_pembelian);
          setDataCustType(response.data.data.link_customer_type);
        })
        .catch(error => {
          setErrorMessage(error.message);
        });
    }
  }, [idCustomer])

  useEffect(() => {
    produks.map((produk) => {
      setIdCustomer(produk.customer);
    })
  }, [produks]);

  const handleKodeEvent = (e) => {
    e.preventDefault();
    setIsLoading(true);
    axios({
      method: "get",
      url: `${window.location.origin}/api/kodeEvent/${kodeEvent}`,
      headers: {
        Accept: "application/json",
      },
    })
      .then(response => {
        console.log('handle kodeevent', response.data);

        if (response.data.status === 'success') {
          setIsLoading(false);
          setErrorKodeEvent(null);

          if (response.data.data.min_pembelian !== null && totalHarga > response.data.data.min_pembelian) {
            if (response.data.data.potongan) {
              setHargaPromo(response.data.data.potongan);
            } else {
              setHargaPromo((totalHarga * (response.data.data.diskon / 100)));
            }
          } else if (totalHarga > response.data.data.min_pembelian) {
            if (response.data.data.potongan) {
              setHargaPromo(response.data.data.potongan);
            } else {
              setHargaPromo((totalHarga * (response.data.data.diskon / 100)));
            }
          } else {
            setErrorKodeEvent('Anda tidak mencapai minimal pembelian')
          }
        } else {
          throw Error(response.data.message);
        }
      })
      .catch(error => {
        setIsLoading(false);
        setErrorKodeEvent(error.message);
      });
  }

  const hapusSemuaProduk = () => {
    produks.map((produk) => {
      KeranjangDB.deleteProduk(produk.id);
      getAllProduks();
    })
    setKodeEvent('');
    setErrorKodeEvent(null);
    setHargaPromo(null);
  }

  const handlePilihProdukChange = (item) => {
    const exist = produks.find((x) => x.id === item.id);
    if (exist) {
      setProduks(
        produks.map((x) => {
          if (x.id === item.id) return { ...exist, isSelected: !x.isSelected }
          else return x
        })
      );
    } else {
      setProduks([...produks, { ...item, isSelected: false }]);
    }
  }

  const tambahJumlahProduk = (item, orderid) => {
    const produk = {
      id: item.id,
      orderId: orderid,
      customer: parseInt(idCust),
      harga: item.harga,
      jumlah: item.jumlah + 1,
    };
    KeranjangDB.updateProduk(produk);
    getAllProduks();
    setKodeEvent('');
    setErrorKodeEvent(null);
    setHargaPromo(null);
  }

  const kurangJumlahProduk = (item, orderid) => {
    const produk = {
      id: item.id,
      orderId: orderid,
      customer: parseInt(idCust),
      harga: item.harga,
      jumlah: item.jumlah - 1,
    };
    KeranjangDB.updateProduk(produk);
    getAllProduks();
    setKodeEvent('');
    setErrorKodeEvent(null);
    setHargaPromo(null);
  }

  const hapusProduk = (item) => {
    KeranjangDB.deleteProduk(item.id);
    getAllProduks();
    setKodeEvent('');
    setErrorKodeEvent(null);
    setHargaPromo(null);
  }

  const checkout = (e) => {
    e.preventDefault();
    setIsLoading(true);
    if (estimasiWaktuPengiriman) {
      axios({
        method: "post",
        url: `${window.location.origin}/api/salesman/buatOrder`,
        headers: {
          Accept: "application/json",
        },
        data: {
          keranjang: produks,
          idStaf: dataUser.id_staff,
          estimasiWaktuPengiriman: estimasiWaktuPengiriman,
          keterangan: keteranganOrderItem,
          kodeEvent: kodeEvent,
          totalHarga: (totalHarga - (totalHarga * (dataCustType.diskon ?? 0) / 100) - hargaPromo),
          idTrip: idTrip,
          tipeRetur: parseInt(tipeRetur)
        }
      })
        .then(response => {
          console.log('chekout', response);
          if (response.data.status === 'success') {
            hapusSemuaProduk();
            setIsLoading(false);
            setSuccessMessage(response.data.success_message);
            axios({
              method: "get",
              url: `${window.location.origin}/api/keluarToko/${idTrip}`,
              headers: {
                Accept: "application/json",
              },
            })
              .then(response => {
                console.log(response.message);
                history.push('/salesman');
              })
          } else {
            throw Error(response.data.error_message);
          }
        })
        .catch(error => {
          console.log(error.message);
          setIsLoading(false);
          setErrorMessage(error.message);
        });
    }
  }

  return (
    <main className="page_main">
      <header className='header_mobile'>
        <div className='d-flex align-items-center'>
          <button className='btn' onClick={goback}>
            <span className="iconify" data-icon="eva:arrow-back-fill"></span>
          </button>
          <h1 className='page_title'>Keranjang</h1>
        </div>
      </header>

      <div className="page_container pt-4">
        {isLoading && loadingDataUser && <LoadingIndicator />}
        {successMessage && <AlertComponent successMsg={successMessage} />}
        {errorMessage && <AlertComponent errorMsg={errorMessage} />}
        {limitPembelian != 0 && <div>Limit pembelian: {limitPembelian ? limitPembelian : "Tak terbatas"} </div>}

        {produks.length === 0 && <p className='text-danger text-center'>Keranjang Kosong</p>}
        {produks.length !== 0 && <button className='btn btn-danger' onClick={hapusSemuaProduk}>Hapus Semua</button>}

        {produks.length > 0 && produks.map((produk) => (
          <div className="cart_item mb-3" key={produk.id}>
            <div className="d-flex">
              <div className="form-check pl-0">
                <label className="customCheckbox_wrapper">
                  <input type="checkbox" className="form-check-input custom_checkbox"
                    checked={produk.isSelected || false}
                    onChange={() => { handlePilihProdukChange(produk) }}
                  />
                  <span className="custom_checkbox"></span>
                </label>
              </div>

              {produk.gambar ? <img src={`${urlAsset}/storage/item/${produk.gambar}`} className="item_image" />
                : <img src={`${urlAsset}/images/default_produk.png`} className="item_image" />}
            </div>

            <div className={produk.isSelected ? "grid_item" : ""}>
              <div className="detail_item pl-3">
                <h5 className={`${produk.isSelected ? 'elipsis' : ''}`}>{produk.nama}</h5>

                <div className="d-flex flex-row mt-3 mb-2">
                  <button className="btn btn-primary"
                    disabled={produk.jumlah === 1 ? true : false}
                    onClick={() => kurangJumlahProduk(produk, produk.orderId)}> - </button>
                  <input type="text" className="text-center"
                    style={{ width: `${produk.jumlah.toString().length + 1}ch` }}
                    value={produk.jumlah}
                    disabled
                  />
                  <button className="btn btn-primary"
                    onClick={() => tambahJumlahProduk(produk, produk.orderId)}> + </button>
                </div>

                <div>{convertPrice(produk.harga)}</div>
              </div>

              {produk.isSelected &&
                <button
                  className="btn btn-danger btn_deleteItem"
                  onClick={() => hapusProduk(produk)}>
                  <span className="iconify " data-icon="bxs:trash"></span>
                </button>}
            </div>
          </div>
        ))}

        {produks.length > 0 &&
          <Fragment>
            <label className="form-label">Estimasi Waktu Pengiriman</label>
            <div className="input-group">
              <input type="number" className="form-control"
                value={estimasiWaktuPengiriman || ''}
                onChange={(e) => setEstimasiWaktuPengiriman(e.target.value)}
              />
              <button className="btn btn-secondary">Hari</button>
            </div>
            {!estimasiWaktuPengiriman && <p className='text-danger'>Estimasi waktu pengiriman wajib diisi</p>}

            <label className="form-label mt-3">Keterangan Pesanan</label>
            <input type="text" className="form-control"
              value={keteranganOrderItem || ''}
              onChange={(e) => setKeteranganOrderItem(e.target.value)}
            />

            <form onSubmit={handleKodeEvent}>
              <label className="form-label mt-3">Kode Event</label>
              <div className="input-group">
                <input type="text" className="form-control"
                  value={kodeEvent}
                  onChange={(e) => setKodeEvent(e.target.value)}
                />
                <button type="submit" className="btn btn-primary">Gunakan</button>
              </div>
            </form>
            {errorKodeEvent && <p className='text-danger'>{errorKodeEvent}</p>}

            <label className="form-label mt-3">Tipe Retur</label>
            <select className="form-select mb-3" value={tipeRetur} onChange={(e) => setTipeRetur(e.target.value)}>
              {pilihanRetur.length && pilihanRetur.map((pilihan, index) => (
                <option value={pilihan.id} key={index}>{pilihan.nama}</option>
              ))}
            </select>
            {produks.map((produk) => {
              totalHarga = totalHarga + (produk.jumlah * produk.harga);
            })}
            <Table striped bordered hover className="mb-btnBottom">
              <thead>
                <tr>
                  <th>keterangan</th>
                  <th>jumlah</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Total</td>
                  <td>{totalHarga}</td>
                </tr>
                <tr>
                  <td>potongan {dataCustType.nama}</td>
                  <td> - {totalHarga * (dataCustType.diskon ?? 0) / 100}</td>
                </tr>
                <tr>
                  <td>event <span></span></td>
                  <td>- {hargaPromo}</td>
                </tr>
                <tr>
                  <td>total akhir<span></span></td>
                  <td>{totalHarga - (totalHarga * (dataCustType.diskon ?? 0) / 100) - hargaPromo}</td>
                </tr>
              </tbody>
            </Table>

            <div className="button_bottom d-flex justify-content-between">
              <div>
                <p className='mb-0'>Total Pesanan:</p>
                <h1 className={`mb-0 fs-4 `}>{convertPrice(totalHarga - (totalHarga * (dataCustType.diskon ?? 0) / 100) - hargaPromo)}</h1>
              </div>
              <button className='btn btn-success' onClick={checkout}>CHECKOUT</button>
            </div>
          </Fragment>
        }
      </div>
    </main>
  );
}

export default KeranjangSales;


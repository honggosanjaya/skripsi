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
import HeaderSales from './HeaderSales';
import { Button, Modal } from 'react-bootstrap';
import { HitungStokContext } from '../../contexts/HitungStokContext';

const KeranjangSales = ({ location }) => {
  const { dataUser, loadingDataUser } = useContext(UserContext);
  const history = useHistory();
  const { idCust } = useParams();
  const { token } = useContext(AuthContext);
  const { produks, setProduks, getAllProduks } = useContext(KeranjangSalesContext);
  const [errorMessage, setErrorMessage] = useState(null);
  const [successMessage, setSuccessMessage] = useState(null);
  const [isLoading, setIsLoading] = useState(false);
  const [estimasiWaktuPengiriman, setEstimasiWaktuPengiriman] = useState('');
  const [jatuhTempo, setJatuhTempo] = useState('');
  const [keteranganOrderItem, setKeteranganOrderItem] = useState(null);
  const [limitPembelian, setLimitPembelian] = useState(0);
  const [dataCustType, setDataCustType] = useState({});
  const [pilihanRetur, setPilihanRetur] = useState([]);
  const [tipeRetur, setTipeRetur] = useState('1');
  const [metodePembayaran, setMetodePembayaran] = useState('1');
  const [kodeEvent, setKodeEvent] = useState('');
  const [errorKodeEvent, setErrorKodeEvent] = useState(null);
  const [hargaPromo, setHargaPromo] = useState(0);
  const [tipeEvent, setTipeEvent] = useState('');
  const [besarEvent, setBesarEvent] = useState(0);
  const [minPembelianEvent, setMinPembelianEvent] = useState(null);
  const [errorProdukDlmKeranjang, setErrorProdukDlmKeranjang] = useState(false);
  const [totalHarga, setTotalHarga] = useState(0);
  const [diskon, setDiskon] = useState(0);
  const [isShowRincian, setIsShowRincian] = useState(false);
  const { kodePesanan, setKodePesanan } = useContext(HitungStokContext);
  const Swal = require('sweetalert2');
  const { state: idTrip } = location;
  let jmlProdukError = 0;
  let toHar = 0;

  let pilihanMetodePembayaran = [
    {
      id: 1, nama: 'tunai'
    },
    {
      id: 2, nama: 'giro'
    },
    {
      id: 3, nama: 'transfer'
    }
  ]

  const goback = () => {
    history.push({
      pathname: `/salesman/order/${idCust}`,
      state: idTrip
    })
  }

  useEffect(() => {
    axios({
      method: "get",
      url: `${window.location.origin}/api/tripCustomer/${idCust}`,
      headers: {
        Accept: "application/json",
      },
    })
      .then(response => {
        const idTipeRetur = response.data.data.tipe_retur;
        if (idTipeRetur != null) {
          setTipeRetur(idTipeRetur.toString());
        } else {
          setTipeRetur('1');
        }

        if (response.data.data.jatuh_tempo != null) {
          setJatuhTempo(response.data.data.jatuh_tempo);
        }

        if (response.data.data.metode_pembayaran != null) {
          setMetodePembayaran(response.data.data.metode_pembayaran)
        }
        setDiskon(response.data.data.link_customer_type.diskon);
      })
      .catch(error => {
        setErrorMessage(error.message);
      });
  }, [])

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
    console.log('idTrip', idTrip);
  }, [])

  useEffect(() => {
    setIsLoading(true);
    axios({
      method: "get",
      url: `${window.location.origin}/api/tripCustomer/${idCust}`,
      headers: {
        Accept: "application/json",
      },
    })
      .then(response => {
        setIsLoading(false);
        setLimitPembelian(response.data.data.limit_pembelian);
        setDataCustType(response.data.data.link_customer_type);
      })
      .catch(error => {
        setIsLoading(false);
        setErrorMessage(error.message);
      });
  }, [idCust])

  useEffect(() => {
    produks.map((produk) => {
      toHar = toHar + produk.jumlah * produk.harga;
      setTotalHarga(toHar);
      if (produk.error) {
        jmlProdukError = jmlProdukError + 1;
      }
      if (jmlProdukError > 0) {
        setErrorProdukDlmKeranjang(true);
      } else {
        setErrorProdukDlmKeranjang(false);
      }
    })
  }, [produks]);

  useEffect(() => {
    if (kodeEvent != '' && totalHarga > minPembelianEvent) {
      setErrorKodeEvent(null);
      if (tipeEvent == 'potongan') {
        setHargaPromo(besarEvent);
      } else {
        setHargaPromo((totalHarga - (totalHarga * diskon / 100)) * (besarEvent / 100));
      }
    } else if (kodeEvent != '') {
      setErrorKodeEvent('Anda tidak mencapai minimal pembelian');
      setHargaPromo(0);
    }
  }, [totalHarga, produks, kodeEvent])

  const hapusSemuaProduk = () => {
    produks.map((produk) => {
      KeranjangDB.deleteProduk(produk.id);
      getAllProduks();
    })
    setKodeEvent('');
    setErrorKodeEvent(null);
    setHargaPromo(null);
  }

  const handleHapusSemuaProduk = () => {
    Swal.fire({
      title: 'Apakah anda yakin?',
      text: "Item yang dihapus akan hilang!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
      if (result.isConfirmed) {
        produks.map((produk) => {
          KeranjangDB.deleteProduk(produk.id);
          getAllProduks();
        })
        setKodeEvent('');
        setErrorKodeEvent(null);
        setHargaPromo(null);
        Swal.fire(
          'Berhasil Dihapus!',
          'Seluruh item telah dihapus',
          'success'
        )
      }
    })
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
      nama: item.nama,
      stok: item.stok
    };
    KeranjangDB.updateProduk(produk);
    getAllProduks();
  }

  const kurangJumlahProduk = (item, orderid) => {
    const produk = {
      id: item.id,
      orderId: orderid,
      customer: parseInt(idCust),
      harga: item.harga,
      jumlah: item.jumlah - 1,
      nama: item.nama,
      stok: item.stok
    };
    KeranjangDB.updateProduk(produk);
    getAllProduks();
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
    if (estimasiWaktuPengiriman) {
      Swal.fire({
        title: 'Apakah anda yakin?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Checkout!'
      }).then((result) => {
        setIsLoading(true);
        axios({
          method: "post",
          url: `${window.location.origin}/api/salesman/buatOrder`,
          headers: {
            Accept: "application/json",
          },
          data: {
            keranjang: produks,
            idCustomer: idCust,
            kodePesanan: kodePesanan,
            idStaf: dataUser.id_staff,
            estimasiWaktuPengiriman: estimasiWaktuPengiriman,
            jatuhTempo: jatuhTempo,
            keterangan: keteranganOrderItem,
            kodeEvent: kodeEvent,
            totalHarga: (totalHarga - (totalHarga * (dataCustType.diskon ?? 0) / 100) - hargaPromo),
            idTrip: idTrip,
            tipeRetur: parseInt(tipeRetur),
            metode_pembayaran: metodePembayaran
          }
        })
          .then(response => {
            console.log('chekout', response);
            if (response.data.status === 'success') {
              hapusSemuaProduk();
              setIsLoading(false);
              setKodePesanan(null);

              Swal.fire({
                icon: 'success',
                title: 'Tersimpan!',
                text: response.data.success_message,
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#7066e0',
                confirmButtonText: 'Belanja Lagi',
                cancelButtonText: 'Selesai'
              }).then((result) => {
                if (result.isConfirmed) {
                  axios({
                    method: "get",
                    url: `${window.location.origin}/api/belanjalagi/${idTrip}`,
                    headers: {
                      Accept: "application/json",
                    },
                  })
                    .then((response) => {
                      history.push(`/salesman/order/${response.data.data.customer.id}`);
                    })
                    .catch(error => {
                      console.log(error.message);
                      history.push('/salesman');
                    });
                }
                else {
                  history.push('/salesman');
                }
              })
            } else {
              throw Error(response.data.error_message);
            }
          })
          .catch(error => {
            console.log('after checkout', error.message);
            setIsLoading(false);
            setIsShowRincian(false);
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: error.message,
            })
          });
      })
    }
  }

  const handleKodeEvent = (e) => {
    e.preventDefault();
    axios({
      method: "get",
      url: `${window.location.origin}/api/kodeEvent/${kodeEvent}`,
      headers: {
        Accept: "application/json",
      },
    })
      .then(response => {
        if (response.data.status === 'success') {
          setErrorKodeEvent(null);
          setMinPembelianEvent(response.data.data.min_pembelian);
          if (response.data.data.min_pembelian !== null && totalHarga > response.data.data.min_pembelian) {
            if (response.data.data.potongan) {
              setHargaPromo(response.data.data.potongan);
              setTipeEvent('potongan');
              setBesarEvent(response.data.data.potongan);
            } else {
              setHargaPromo((totalHarga - (totalHarga * diskon / 100)) * (response.data.data.diskon / 100));
              setTipeEvent('diskon');
              setBesarEvent(response.data.data.diskon);
            }
          } else if (totalHarga > response.data.data.min_pembelian) {
            if (response.data.data.potongan) {
              setHargaPromo(response.data.data.potongan);
              setTipeEvent('potongan');
              setBesarEvent(response.data.data.potongan);
            } else {
              setHargaPromo((totalHarga - (totalHarga * diskon / 100)) * (response.data.data.diskon / 100));
              setTipeEvent('diskon');
              setBesarEvent(response.data.data.diskon);
            }
          } else {
            setErrorKodeEvent('Anda tidak mencapai minimal pembelian');
            setHargaPromo(0);
            setTipeEvent('');
            setBesarEvent(0);
          }
        } else {
          throw Error(response.data.message);
        }
      })
      .catch(error => {
        setErrorKodeEvent(error.message);
        setHargaPromo(0);
        setTipeEvent('');
        setBesarEvent(0);
      });
  }

  return (
    <main className="page_main">
      <HeaderSales title='Keranjang' toBack={goback} />
      {limitPembelian != 0 && <div className="alert alert-info mb-0" role="alert">
        <p className="mb-0 text-center"><b>Limit Pembelian :</b> {limitPembelian ? limitPembelian : "Tanpa Limit"}</p>
      </div>}
      <div className="page_container pt-4">
        {(isLoading || loadingDataUser) && <LoadingIndicator />}
        {successMessage ? <AlertComponent successMsg={successMessage} /> : errorMessage && <AlertComponent errorMsg={errorMessage} />}
        {!isLoading && !loadingDataUser && produks.length === 0 && <small className='text-danger text-center d-block mt-5'>Keranjang Kosong</small>}

        {!isLoading && !loadingDataUser && produks.length !== 0 && <div className="d-flex align-items-center justify-content-between mb-3">
          <h1 className="fs-5 fw-bold">Keranjang Customer</h1>
          <button className='btn btn-danger' onClick={handleHapusSemuaProduk}>Hapus Semua</button>
        </div>}

        {!isLoading && !loadingDataUser && produks.length > 0 && produks.map((produk) => (
          <div className="cart_item" key={produk.id}>
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
              {produk.gambar ? <img src={`${urlAsset}/storage/item/${produk.gambar}`} className="item_cartimage" />
                : <img src={`${urlAsset}/images/default_produk.png`} className="item_cartimage" />}
            </div>

            <div className={produk.isSelected ? "grid_item" : ""}>
              <div className="detail_item">
                <h2 className={`mb-0 fs-6 fw-bold ${produk.isSelected ? 'elipsis' : ''}`}>{produk.nama}</h2>
                {dataCustType.diskon ?
                  <p className='mb-0 d-inline'>{convertPrice(produk.harga - (produk.harga * (dataCustType.diskon) / 100))}</p>
                  : <p className='mb-0 d-inline'>{convertPrice(produk.harga)}</p>
                }
                <div className="d-flex my-2">
                  {produk.error ? <button className="btn btn-primary" disabled={true}> - </button>
                    : <button className="btn btn-primary"
                      disabled={produk.jumlah === 1 ? true : false}
                      onClick={() => kurangJumlahProduk(produk, produk.orderId)}> - </button>}
                  <input type="text" className="text-center"
                    style={{ width: `${produk.jumlah.toString().length + 1}ch` }}
                    value={produk.jumlah}
                    disabled
                  />
                  {produk.error ? <button className="btn btn-primary" disabled={true}> + </button> :
                    <button className="btn btn-primary"
                      disabled={produk.jumlah == produk.stok ? true : false}
                      onClick={() => tambahJumlahProduk(produk, produk.orderId)}> + </button>}
                </div>
                {produk.error && <small className="text-danger">{produk.error}</small>}
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

        {!isLoading && !loadingDataUser && produks.length > 0 &&
          <div className="mb-btnBottom">
            <label className="form-label mt-3">Keterangan Pesanan</label>
            <textarea className="form-control" value={keteranganOrderItem || ''} onChange={(e) => setKeteranganOrderItem(e.target.value)} />

            <label className="form-label mt-3">Estimasi Waktu Pengiriman <span className='text-danger'>*</span></label>
            <div className="input-group">
              <input type="number" className="form-control"
                value={estimasiWaktuPengiriman}
                onChange={(e) => setEstimasiWaktuPengiriman(e.target.value)}
                min='0'
              />
              <div className="border p-2 d-flex justify-content-center align-items-center rounded-end">Hari</div>
            </div>

            <label className="form-label mt-3">Jatuh Tempo<span className='text-danger'>*</span></label>
            <div className="input-group">
              <input type="number" className="form-control"
                value={jatuhTempo}
                onChange={(e) => setJatuhTempo(e.target.value)}
                min='0'
              />
              <div className="border p-2 d-flex justify-content-center align-items-center rounded-end">Hari</div>
            </div>

            <label className="form-label mt-3">Kode Event</label>
            <div className="input-group">
              <input type="text" className="form-control"
                value={kodeEvent}
                onChange={(e) => setKodeEvent(e.target.value)}
              />
              <button className="btn btn-success" onClick={handleKodeEvent}>Gunakan</button>
            </div>
            {errorKodeEvent && <small className='text-danger d-block'>{errorKodeEvent}</small>}
            {hargaPromo > 0 && <small className='text-success d-block'>Eksta potongan {hargaPromo}</small>}

            <label className="form-label mt-3">Tipe Retur <span className='text-danger'>*</span></label>
            <select className="form-select mb-3" value={tipeRetur} onChange={(e) => setTipeRetur(e.target.value)}>
              {pilihanRetur.length && pilihanRetur.map((pilihan, index) => (
                <option value={pilihan.id} key={index}>{pilihan.nama}</option>
              ))}
            </select>

            <label className="form-label mt-3">Metode Pembayaran <span className='text-danger'>*</span></label>
            <select className="form-select mb-3" value={metodePembayaran} onChange={(e) => setMetodePembayaran(e.target.value)}>
              {pilihanMetodePembayaran.length && pilihanMetodePembayaran.map((pilihan, index) => (
                <option value={pilihan.id} key={index}>{pilihan.nama}</option>
              ))}
            </select>

            <Modal show={isShowRincian} onHide={() => setIsShowRincian(false)}>
              <Modal.Header closeButton>
                <Modal.Title><span className="iconify me-2" data-icon="uil:invoice"></span>Rincian Pesanan</Modal.Title>
              </Modal.Header>
              <Modal.Body>
                <Table>
                  <thead>
                    <tr>
                      <th className='text-center'>Nama</th>
                      <th className='text-center'>Kuantitas</th>
                      <th className='text-center'>Harga</th>
                    </tr>
                  </thead>
                  <tbody>
                    {produks.map((produk) => (
                      <tr key={produk.id}>
                        <td>{produk.nama}</td>
                        <td className='text-center'>{produk.jumlah} x {produk.harga}</td>
                        <td className='text-center'>{produk.jumlah * produk.harga}</td>
                      </tr>
                    ))}
                  </tbody>
                </Table>

                <div className="d-flex justify-content-between px-2">
                  <p className='mb-0 fw-bold'>Subtotal pesanan</p>
                  <p className='mb-0'>{totalHarga}</p>
                </div>
                <div className="d-flex justify-content-between px-2">
                  <p className='mb-0 fw-bold'>Diskon Customer ({dataCustType.nama})</p>
                  <p className='mb-0'>- {totalHarga * (dataCustType.diskon ?? 0) / 100}</p>
                </div>
                <div className="d-flex justify-content-between px-2">
                  <p className='mb-0 fw-bold'>Event</p>
                  <p className='mb-0'>- {hargaPromo}</p>
                </div>
                <hr />
                <div className="d-flex justify-content-between">
                  <p className='mb-0 fw-bold'>Total Akhir</p>
                  <p className='mb-0'>{totalHarga - (totalHarga * (dataCustType.diskon ?? 0) / 100) - hargaPromo}</p>
                </div>
              </Modal.Body>
              <Modal.Footer>
                <Button variant="success" onClick={checkout}>
                  <span className="iconify fs-3 me-1" data-icon="ic:baseline-shopping-cart-checkout"></span> CHEKOUT
                </Button>
              </Modal.Footer>
            </Modal>


            <div className="button_bottom d-flex justify-content-between">
              <div>
                <p className='mb-0'>Total Pesanan:</p>
                <h1 className={`mb-0 fs-4 `}>{convertPrice(totalHarga - (totalHarga * (dataCustType.diskon ?? 0) / 100) - hargaPromo)}</h1>
              </div>
              {(errorProdukDlmKeranjang || estimasiWaktuPengiriman == '' || jatuhTempo == '') ? <button className='btn btn-success' disabled={true}><span className="iconify me-2" data-icon="uil:invoice"></span> Rincian Pesanan</button> : <button className='btn btn-success' onClick={() => setIsShowRincian(true)}><span className="iconify me-2" data-icon="uil:invoice"></span> Rincian Pesanan</button>}
            </div>
          </div>
        }
      </div>
    </main>
  );
}

export default KeranjangSales;


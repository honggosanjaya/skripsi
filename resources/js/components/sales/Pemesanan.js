import React, { Fragment, useContext, useEffect, useState } from 'react';
import { useHistory, useParams } from 'react-router-dom';
import useSWR from 'swr';
import AlertComponent from '../reuse/AlertComponent';
import LoadingIndicator from '../reuse/LoadingIndicator';
import HeaderSales from './HeaderSales';
import InfiniteScroll from 'react-infinite-scroll-component';
import KeranjangDB from '../reuse/KeranjangDB';
import useInfinite from '../reuse/useInfinite';
import ProductSales from './ProductSales';
import { KeranjangSalesContext } from '../../contexts/KeranjangSalesContext';
import { AuthContext } from '../../contexts/AuthContext';
import { UserContext } from '../../contexts/UserContext';
import HitungStok from './HitungStok';
import { HitungStokContext } from '../../contexts/HitungStokContext';
import KeluarToko from './KeluarToko';
import FilterItem from './FilterItem';

const Pemesanan = ({ location }) => {
  const { idCust } = useParams();
  const [urlApi, setUrlApi] = useState(`api/salesman/listitems/${idCust}`);
  const [filterBy, setFilterBy] = useState(null);
  const { page, setPage, erorFromInfinite, paginatedData, isReachedEnd, orderRealTime } = useInfinite(`${urlApi}`, 4);
  const { token } = useContext(AuthContext);
  const { dataUser } = useContext(UserContext);
  const history = useHistory();
  const { produks, getAllProduks, isBelanjaLagi, setIsBelanjaLagi } = useContext(KeranjangSalesContext);
  const [kataKunci, setKataKunci] = useState('');
  const [errorMessage, setErrorMessage] = useState(null);
  const [orderId, setOrderId] = useState(null);
  const [errorKodeCustomer, setErrorKodeCustomer] = useState(null);
  const [successKodeCustomer, setSuccessKodeCustomer] = useState(null);
  const [koordinat, setKoordinat] = useState(null);
  const [idTrip, setIdTrip] = useState(null);
  const [show, setShow] = useState(false);
  const [showFilter, setShowFilter] = useState(false);
  const [alasanPenolakan, setAlasanPenolakan] = useState(null);
  const { state: idTripTetap } = location;
  const jamMasuk = Date.now() / 1000;
  const [customer, setCustomer] = useState([]);
  const [historyItem, setHistoryItem] = useState([]);
  const { newHistoryItem, setNewHistoryItem, kodePesanan, setKodePesanan, setIsKodePesananValid } = useContext(HitungStokContext);
  const [jmlItem, setJmlItem] = useState(null);
  let jumlahProdukKeranjang = 0;
  const [jumlahOrderRealTime, setJumlahOrderRealTime] = useState([]);
  const [isHandleKodeCust, setIsHandleKodeCust] = useState(false);
  const [shouldKeepOrder, setShouldKeepOrder] = useState(false);
  const [diskon, setDiskon] = useState(0);
  const [shouldDisabled, setShouldDisabled] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [loadingKode, setLoadingKode] = useState(false);
  const [itemKanvas, setItemKanvas] = useState([]);
  const [idItemKanvas, setIdItemKanvas] = useState([]);
  const [isHaveCodeCust, setIsHaveCodeCust] = useState(false);
  const Swal = require('sweetalert2');

  useEffect(() => {
    if (filterBy) {
      setUrlApi(`api/salesman/filteritems/${idCust}/${filterBy}`);
    } else {
      setUrlApi(`api/salesman/listitems/${idCust}`);
    }
  }, [filterBy])

  useEffect(() => {
    setNewHistoryItem(historyItem);
  }, [historyItem]);

  const { data: dataCustomer, error } = useSWR(
    [`${window.location.origin}/api/tripCustomer/${idCust}`, token], {
    revalidateOnFocus: false,
  });

  useEffect(() => {
    navigator.permissions.query({ name: 'geolocation' }).then((result) => {
      if (result.state === 'prompt') {
        let timerInterval;
        let seconds = 7;
        Swal.fire({
          title: 'Peringatan Izin Akses Lokasi Perangkat',
          html: 'Selanjutnya kami akan meminta akses lokasi anda, mohon untuk mengizinkannya. <br><br> Tunggu <b></b> detik untuk menutupnya',
          icon: 'info',
          allowOutsideClick: false,
          allowEscapeKey: false,
          timer: 7000,
          didOpen: () => {
            Swal.showLoading();
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval = setInterval(() => {
              if (seconds > 0) {
                seconds -= 1;
              }
              b.textContent = seconds;
            }, 1000);
          },
        }).then((result) => {
          navigator.geolocation.getCurrentPosition(function (position) {
            setKoordinat(position.coords.latitude + '@' + position.coords.longitude)
          });
        })
      } else if (result.state === 'granted') {
        navigator.geolocation.getCurrentPosition(function (position) {
          setKoordinat(position.coords.latitude + '@' + position.coords.longitude)
        });
      } else if (result.state === 'denied') {
        setKoordinat('0@0');
        let timerInterval2;
        let seconds2 = 4;
        Swal.fire({
          title: 'Tidak Ada Akses Lokasi Perangkat',
          html: 'Agar memudahkan kunjungan silahkan buka pengaturan browser anda dan ijinkan aplikasi mengakses lokasi. <br><br> Tunggu <b></b> detik untuk menutupnya',
          icon: 'info',
          allowOutsideClick: false,
          allowEscapeKey: false,
          confirmButtonText: 'Tutup',
          didOpen: () => {
            Swal.showLoading();
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval2 = setInterval(() => {
              if (seconds2 > 0) {
                seconds2 -= 1;
              }
              b.textContent = seconds2;
            }, 1000);
            setTimeout(() => { Swal.hideLoading() }, 4000);
          },
        })
      }
    });

    if (idTripTetap) {
      setIdTrip(idTripTetap);
    }

    getAllProduks();

    axios({
      method: "get",
      url: `${window.location.origin}/api/salesman/historyitems/${idCust}`,
      headers: {
        Accept: "application/json",
      },
    })
      .then((response) => {
        console.log('history produk', response.data);
        setHistoryItem(response.data.data.history);
        setJumlahOrderRealTime(response.data.orderRealTime);
      })
      .catch((error) => {
        console.log(error.message);
      });

    axios({
      method: "get",
      url: `${window.location.origin}/api/tripCustomer/${idCust}`,
      headers: {
        Accept: "application/json",
      },
    })
      .then(response => {
        console.log('cust.', response.data.data);
        setDiskon(response.data.data.link_customer_type.diskon);
        setCustomer(response.data.data);
      })
      .catch(error => {
        console.log(error.message);
      });
  }, []);

  useEffect(() => {
    if (dataUser.id_staff != undefined || dataUser.id_staff != null) {
      axios({
        method: "get",
        url: `${window.location.origin}/api/kanvas/${dataUser.id_staff}`,
        headers: {
          Accept: "application/json",
        },
      })
        .then(response => {
          console.log('itemkanvas', response.data);
          setIdItemKanvas(response.data.dataIdItem);
          setItemKanvas(response.data.dataItem);
        })
        .catch(error => {
          setErrorMessage(error.message);
        });
    }
  }, [dataUser])

  useEffect(() => {
    navigator.permissions.query({ name: 'geolocation' }).then((result) => {
      if (result.state !== 'granted') {
        setKoordinat('0@0')
      }
    });

    if (dataUser.nama && koordinat && idTripTetap == null && idTrip == null) {
      axios({
        method: "post",
        url: `${window.location.origin}/api/tripOrderCustomer`,
        data: {
          idCustomer: parseInt(idCust),
          idStaff: dataUser.id_staff,
          koordinat: koordinat,
          jam_masuk: jamMasuk,
        },
        headers: {
          Accept: "application/json",
        },
      })
        .then((response) => {
          console.log('trip', response.data.data);
          setIdTrip(response.data.data.id);
        })
        .catch((error) => {
          console.log(error.message);
        });
    }
  }, [dataUser, koordinat]);

  useEffect(() => {
    produks.map((produk) => {
      jumlahProdukKeranjang += produk.jumlah;
      if (produk.jumlah == 0) handleDeleteProduct(produk);
    })

    setJmlItem(jumlahProdukKeranjang);
  }, [produks]);

  useEffect(() => {
    if (error) setErrorMessage(error.message);
  }, [error]);

  if (error) return (
    <main className="page_main">
      <HeaderSales title="Salesman" />
      <AlertComponent errorMsg={errorMessage} />
    </main>
  )

  if (!dataCustomer) return (
    <main className="page_main">
      <HeaderSales title="Salesman" />
      <LoadingIndicator />
    </main>
  )

  const handleShow = () => {
    if (isBelanjaLagi == false) {
      setShow(true);
    } else {
      Swal.fire({
        title: 'Apakah anda yakin?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Keluar!'
      }).then((result) => {
        if (result.isConfirmed) {
          handleKeluarToko();
        }
      })
    }
  };
  const handleClose = () => setShow(false);
  const handleShowFilter = () => setShowFilter(true);
  const handleCloseFilter = () => setShowFilter(false);

  const checkTipeHarga = (produk, item) => {
    if (customer.tipe_harga == 2 && item.harga2_satuan) {
      produk.harga = item.harga2_satuan;
    } else if (customer.tipe_harga == 3 && item.harga3_satuan) {
      produk.harga = item.harga3_satuan;
    } else {
      produk.harga = item.harga1_satuan;
    }
  }

  const handleKeluarToko = () => {
    if (idTrip) {
      setIsLoading(true);
      setShouldDisabled(true);
      axios({
        method: "post",
        url: `${window.location.origin}/api/keluarToko/${idTrip}`,
        headers: {
          Accept: "application/json",
        },
        data: {
          'alasan_penolakan': alasanPenolakan,
          'isBelanjaLagi': isBelanjaLagi,
          'idCust': idCust,
          'idStaf': dataUser.id_staff,
          'koordinat': koordinat,
        }
      })
        .then((response) => {
          setShouldDisabled(false);
          console.log('trip', response.data.message);
          setIsLoading(false);
          setIsBelanjaLagi(false);
          hapusSemuaProduk();
          history.push('/salesman');
        })
        .catch((error) => {
          setShouldDisabled(false);
          setIsLoading(false);
          console.log(error.message);
        });
    }
  }

  const lihatKeranjang = () => {
    history.push({
      pathname: `/salesman/keranjang/${idCust}`,
      state: idTrip, koordinat // your data array of objects
    })
  }

  const hapusSemuaProduk = () => {
    produks.map((produk) => {
      KeranjangDB.deleteProduk(produk.id);
      getAllProduks();
    })
  }

  const handleKodeCustomer = (e) => {
    e.preventDefault();
    hapusSemuaProduk();
    setLoadingKode(true);
    axios({
      method: "get",
      url: `${window.location.origin}/api/kodeCustomer/${kodePesanan}`,
      headers: {
        Accept: "application/json",
      }
    })
      .then((response) => {
        console.log('handlekode', response.data);
        setLoadingKode(false);
        if (response.data.status === 'success') {
          setIsHandleKodeCust(true);
          setErrorKodeCustomer(null);
          const dataOrderItems = response.data.dataOrderItem;
          const dataOrder = response.data.dataOrder;

          if (dataOrder.id_customer == idCust) {
            setOrderId(dataOrder.id);
            setIsKodePesananValid(true);
            dataOrderItems.map((dataOrderItem) => {
              console.log('data item', dataOrderItem.link_item);

              let inStokKanvas = idItemKanvas.includes(dataOrderItem.id_item);
              let sisaStok = 0;
              let canStokKanvas = false;
              if (inStokKanvas) {
                sisaStok = itemKanvas.find(o => o.id_item == dataOrderItem.id_item).sisa_stok;
              }

              if (inStokKanvas && sisaStok >= dataOrderItem.kuantitas) {
                canStokKanvas = true;
              }

              let obj = {
                id: dataOrderItem.id_item,
                orderId: dataOrder.id,
                customer: dataOrder.id_customer,
                harga: dataOrderItem.harga_satuan,
                jumlah: dataOrderItem.kuantitas,
                gambar: dataOrderItem.link_item.gambar,
                nama: dataOrderItem.link_item.nama,
                stok: dataOrderItem.link_item.stok,
                canStokKanvas: canStokKanvas
              }
              if (dataOrderItem.link_item.stok < dataOrderItem.kuantitas) {
                obj["error"] = "Stok tidak mencukupi"
                const produk = obj;
                KeranjangDB.updateProduk(produk);
                getAllProduks();
              } else if (dataOrderItem.link_item.status_enum == '-1') {
                obj["error"] = "Item sudah tidak aktif"
                const produk = obj;
                KeranjangDB.updateProduk(produk);
                getAllProduks();
              } else {
                const produk = obj;
                KeranjangDB.updateProduk(produk);
                getAllProduks();
                setSuccessKodeCustomer(response.data.message);
              }
            })
          }
          else {
            setErrorKodeCustomer('Kode customer tidak sesusai');
            setIsHandleKodeCust(false);
            setIsKodePesananValid(false);
            setSuccessKodeCustomer('');
          }
        } else {
          throw Error(response.data.message);
        }
      })
      .catch((error) => {
        setErrorKodeCustomer(error.message);
        setSuccessKodeCustomer('');
        setIsHandleKodeCust(false);
        setLoadingKode(false);
      });
  }

  const handleTambahJumlah = (item, keep) => {
    let inStokKanvas = idItemKanvas.includes(item.id);
    let sisaStok = 0;
    let canStokKanvas = false;
    if (inStokKanvas) {
      sisaStok = itemKanvas.find(o => o.id_item == item.id).sisa_stok;
    }
    setIsHandleKodeCust(false);
    if (keep == true) {
      setShouldKeepOrder(true);
    }

    const exist = produks.find((x) => x.id === item.id);

    if (exist && item.stok > 0) {
      if (exist.jumlah < item.stok) {
        if (inStokKanvas && sisaStok >= exist.jumlah + 1) {
          canStokKanvas = true;
        }
      } else {
        if (inStokKanvas && sisaStok >= exist.jumlah) {
          canStokKanvas = true;
        }
      }

      const produk = {
        id: item.id,
        nama: item.nama,
        orderId: orderId ? parseInt(orderId) : 'belum ada',
        customer: parseInt(idCust),
        jumlah: exist.jumlah < item.stok ? exist.jumlah + 1 : exist.jumlah,
        gambar: item.gambar,
        stok: item.stok,
        canStokKanvas: canStokKanvas
      };
      checkTipeHarga(produk, item);
      KeranjangDB.updateProduk(produk);
      getAllProduks();
      if (exist.jumlah == item.stok) {
        alert('maksimal stok di keranjang');
      }
    }
    else if (!exist && item.stok > 0) {
      if (inStokKanvas && sisaStok >= 1) {
        canStokKanvas = true;
      }

      const produk = {
        id: item.id,
        nama: item.nama,
        orderId: orderId ? parseInt(orderId) : 'belum ada',
        customer: parseInt(idCust),
        jumlah: 1,
        gambar: item.gambar,
        stok: item.stok,
        canStokKanvas: canStokKanvas
      };
      checkTipeHarga(produk, item);
      KeranjangDB.putProduk(produk);
      getAllProduks();
    }
  }

  const handleKurangJumlah = (item, keep) => {
    let inStokKanvas = idItemKanvas.includes(item.id);
    let sisaStok = 0;
    let canStokKanvas = false;
    if (inStokKanvas) {
      sisaStok = itemKanvas.find(o => o.id_item == item.id).sisa_stok;
    }
    setIsHandleKodeCust(false);
    if (keep == true) {
      setShouldKeepOrder(true);
    }
    const exist = produks.find((x) => x.id === item.id);
    if (exist && exist.jumlah > 1) {
      if (inStokKanvas && sisaStok >= exist.jumlah - 1) {
        canStokKanvas = true;
      }

      const produk = {
        id: item.id,
        nama: item.nama,
        orderId: orderId ? parseInt(orderId) : 'belum ada',
        customer: parseInt(idCust),
        jumlah: exist.jumlah - 1,
        gambar: item.gambar,
        stok: item.stok,
        canStokKanvas: canStokKanvas
      };
      checkTipeHarga(produk, item);
      KeranjangDB.updateProduk(produk);
      getAllProduks();
    }

    if (exist && exist.jumlah == 1) {
      let setuju = confirm(`apakah anda yakin ingin menghapus produk ${item.nama} ?`);
      if (setuju) {
        KeranjangDB.deleteProduk(item.id);
        getAllProduks();
      }
    }
  }

  const checkifexist = (item) => {
    const exist = produks.find((x) => x.id === item.id);

    if (exist) {
      if (isNaN(exist.jumlah)) return 0
      else return exist.jumlah;
    }
    else return 0;
  }

  const handleValueChange = (item, newVal, keep) => {
    let inStokKanvas = idItemKanvas.includes(item.id);
    let sisaStok = 0;
    let canStokKanvas = false;
    if (inStokKanvas) {
      sisaStok = itemKanvas.find(o => o.id_item == item.id).sisa_stok;
    }
    setIsHandleKodeCust(false);
    if (keep == true) {
      setShouldKeepOrder(true);
    }

    const exist = produks.find((x) => x.id === item.id);
    if (exist) {
      if (isNaN(newVal) == false) {
        if (isNaN(parseInt(newVal))) {
          if (inStokKanvas && sisaStok >= 0) {
            canStokKanvas = true;
          }
        } else {
          if (inStokKanvas && sisaStok >= parseInt(newVal)) {
            canStokKanvas = true;
          }
        }

        const produk = {
          id: item.id,
          nama: item.nama,
          orderId: orderId ? parseInt(orderId) : 'belum ada',
          customer: parseInt(idCust),
          jumlah: isNaN(parseInt(newVal)) ? 0 : parseInt(newVal),
          gambar: item.gambar,
          stok: item.stok,
          canStokKanvas: canStokKanvas
        };
        checkTipeHarga(produk, item);
        KeranjangDB.updateProduk(produk);
        getAllProduks();
      }
    } else {
      if (isNaN(newVal) == false) {
        if (isNaN(parseInt(newVal))) {
          if (inStokKanvas && sisaStok >= 0) {
            canStokKanvas = true;
          }
        } else {
          if (inStokKanvas && sisaStok >= parseInt(newVal)) {
            canStokKanvas = true;
          }
        }

        const produk = {
          id: item.id,
          nama: item.nama,
          orderId: orderId ? parseInt(orderId) : 'belum ada',
          customer: parseInt(idCust),
          gambar: item.gambar,
          jumlah: isNaN(parseInt(newVal)) ? 0 : parseInt(newVal),
          stok: item.stok,
          canStokKanvas: canStokKanvas
        };
        checkTipeHarga(produk, item);
        KeranjangDB.putProduk(produk);
        getAllProduks();
      }
    }
  }

  const handleSubmitStokTerakhir = (item, newVal) => {
    axios({
      method: "post",
      url: `${window.location.origin}/api/salesman/updateStock`,
      headers: {
        Accept: "application/json",
      },
      data: {
        'id_customer': idCust,
        'id_item': item.link_item.id,
        'quantity': newVal
      }
    })
      .then((response) => {
        console.log('stok terakhir customer', response.data);
      })
      .catch((error) => {
        console.log(error.message);
      });

    const exist = newHistoryItem.find((x) => x.link_item.id === item.link_item.id);
    if (exist) {
      setNewHistoryItem(
        newHistoryItem.map((x) => {
          if (x.link_item.id === item.link_item.id)
            return { ...exist, isSelected: !x.isSelected }
          else return x
        }));
    }
  }

  const handleDeleteProduct = (item) => {
    KeranjangDB.deleteProduk(item.id);
    getAllProduks();
  }

  const handleCariProduk = (e) => {
    e.preventDefault();
    if (kataKunci == '') {
      setUrlApi(`api/salesman/listitems/${idCust}`);
    } else if (kataKunci !== '') {
      setUrlApi(`api/salesman/listitems/${idCust}/${kataKunci}`);
    }
  }

  const toBack = () => {
    hapusSemuaProduk();
    history.push('/salesman');
  }

  const handleFilterChange = (newFilter) => {
    setFilterBy(newFilter);
    if (newFilter != null) {
      setShouldKeepOrder(false);
    }
  }

  const handleCancelUseCodeCust = () => {
    setKodePesanan('');
    setIsHaveCodeCust(false);
    setErrorKodeCustomer(null);
    setSuccessKodeCustomer(null);
  }

  return (
    <main className='page_main'>
      <HeaderSales title="Order" isOrder={true} lihatKeranjang={lihatKeranjang} jumlahProdukKeranjang={jmlItem} toBack={toBack} />
      <div className="page_container pt-4">
        {isLoading && <LoadingIndicator />}
        <div className="kode_customer">
          <div className="d-flex justify-content-between mb-3">
            <p className='fw-bold'>Sudah punya kode customer?</p>
            {isHaveCodeCust ? <button className="btn btn-danger btn-sm" onClick={handleCancelUseCodeCust}>Batal</button>
              : <button className="btn btn-primary btn-sm" onClick={() => setIsHaveCodeCust(true)}>Punya</button>}
          </div>

          {isHaveCodeCust &&
            <Fragment>
              <form onSubmit={handleKodeCustomer}>
                <div className="input-group">
                  <input type="text" className="form-control"
                    value={kodePesanan}
                    onChange={(e) => setKodePesanan(e.target.value)}
                  />
                  {loadingKode ?
                    <button type="submit" className="btn btn-primary" disabled={true}>
                      <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                      </div>
                    </button>
                    : <button type="submit" className="btn btn-primary" disabled={kodePesanan !== '' ? false : true}>Proses</button>
                  }
                </div>
              </form>

              {successKodeCustomer && <small className='text-success'>{successKodeCustomer}</small>}
              {errorKodeCustomer && <small className='text-danger'>{errorKodeCustomer}</small>}
            </Fragment>
          }
        </div>

        <HitungStok historyItem={historyItem} handleTambahJumlah={handleTambahJumlah}
          checkifexist={checkifexist} handleValueChange={handleValueChange}
          handleKurangJumlah={handleKurangJumlah} handleSubmitStokTerakhir={handleSubmitStokTerakhir}
          jumlahOrderRealTime={jumlahOrderRealTime} tipeHarga={customer.tipe_harga} />

        <KeluarToko handleShow={handleShow} alasanPenolakan={alasanPenolakan}
          setAlasanPenolakan={setAlasanPenolakan} handleClose={handleClose}
          handleKeluarToko={handleKeluarToko} show={show} shouldDisabled={shouldDisabled} />

        <FilterItem showFilter={showFilter} handleCloseFilter={handleCloseFilter}
          filterBy={filterBy} setFilterBy={setFilterBy} handleFilterChange={handleFilterChange} />

        <div>
          <div className="d-flex justify-content-between mb-3">
            <h1 className='fs-5 mb-0 fw-bold'>Item</h1>
            <button className='btn' onClick={handleShowFilter}>
              <span className="iconify fs-3" data-icon="ci:filter"></span>
            </button>
          </div>

          <div className='mb-3'>
            <form onSubmit={handleCariProduk}>
              <div className="input-group">
                <input type="text" className="form-control" placeholder="Cari Produk..."
                  value={kataKunci} onChange={(e) => setKataKunci(e.target.value)}
                />
                <button type="submit" className="btn btn-primary">Cari</button>
              </div>
            </form>
          </div>

          {erorFromInfinite && <p className="text-danger">something is wrong</p>}
          <InfiniteScroll
            dataLength={paginatedData?.length ?? 0}
            next={() => setPage(page + 1)}
            hasMore={!isReachedEnd}
            loader={<p>Loading...</p>}
            endMessage={<p className="text-center">No more data</p>}>
            {paginatedData &&
              <ProductSales listItems={paginatedData} handleTambahJumlah={handleTambahJumlah}
                checkifexist={checkifexist} handleValueChange={handleValueChange}
                handleKurangJumlah={handleKurangJumlah} orderRealTime={orderRealTime}
                produkDlmKeranjang={produks} isHandleKodeCust={isHandleKodeCust}
                shouldKeepOrder={shouldKeepOrder} diskonTypeCust={diskon} tipeHarga={customer.tipe_harga}
              />
            }
          </InfiniteScroll>
        </div>
      </div>
    </main>
  );
}

export default Pemesanan;
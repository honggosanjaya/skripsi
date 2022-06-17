import React, { useContext, useEffect, useState } from 'react';
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
  const { produks, getAllProduks } = useContext(KeranjangSalesContext);
  const [kodePesanan, setKodePesanan] = useState('');
  const [kataKunci, setKataKunci] = useState('');
  const [errorMessage, setErrorMessage] = useState(null);
  const [orderId, setOrderId] = useState(null);
  const [errorKodeCustomer, setErrorKodeCustomer] = useState(null);
  const [koordinat, setKoordinat] = useState(null);
  const [idTrip, setIdTrip] = useState(null);
  const [show, setShow] = useState(false);
  const [showFilter, setShowFilter] = useState(false);
  const [alasanPenolakan, setAlasanPenolakan] = useState(null);
  const { state: idTripTetap } = location;
  const jamMasuk = Date.now() / 1000;
  const [customer, setCustomer] = useState([]);
  const [historyItem, setHistoryItem] = useState([]);
  const { newHistoryItem, setNewHistoryItem } = useContext(HitungStokContext);
  const [jmlItem, setJmlItem] = useState(null);
  let jumlahProdukKeranjang = 0;
  const [jumlahOrderRealTime, setJumlahOrderRealTime] = useState([]);
  const [isHandleKodeCust, setIsHandleKodeCust] = useState(false);
  const [shouldKeepOrder, setShouldKeepOrder] = useState(false);
  const [diskon, setDiskon] = useState(0);

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
    if (idTripTetap) {
      setIdTrip(idTripTetap);
    }
    navigator.geolocation.getCurrentPosition(function (position) {
      setKoordinat(position.coords.latitude + '@' + position.coords.longitude);
    });

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
        setDiskon(response.data.data.link_customer_type.diskon);
        setCustomer(response.data.data);
      })
      .catch(error => {
        console.log(error.message);
      });
  }, []);

  useEffect(() => {
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

  const handleShow = () => setShow(true);
  const handleClose = () => setShow(false);
  const handleShowFilter = () => setShowFilter(true);
  const handleCloseFilter = () => setShowFilter(false);

  const handleKeluarToko = () => {
    console.log('idTrip', idTrip);
    if (idTrip) {
      axios({
        method: "post",
        url: `${window.location.origin}/api/keluarToko/${idTrip}`,
        headers: {
          Accept: "application/json",
        },
        data: {
          'alasan_penolakan': alasanPenolakan,
        }
      })
        .then((response) => {
          console.log('trip', response.data.message);
          hapusSemuaProduk();
          history.push('/salesman');
        })
        .catch((error) => {
          console.log(error.message);
        });
    } else {
      console.log('silahkan melakukan trip terlebih dahulu');
    }
  }

  const lihatKeranjang = () => {
    history.push({
      pathname: `/salesman/keranjang/${idCust}`,
      state: idTrip // your data array of objects
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
    axios({
      method: "get",
      url: `${window.location.origin}/api/kodeCustomer/${kodePesanan}`,
      headers: {
        Accept: "application/json",
      }
    })
      .then((response) => {
        console.log('handlekode', response.data);
        if (response.data.status === 'success') {
          setIsHandleKodeCust(true);
          setErrorKodeCustomer(null);
          const dataOrderItems = response.data.dataOrderItem;
          const dataOrder = response.data.dataOrder;

          if (dataOrder.id_customer == idCust) {
            setOrderId(dataOrder.id);
            dataOrderItems.map((dataOrderItem) => {
              console.log('data item', dataOrderItem.link_item);
              let obj = {
                id: dataOrderItem.id_item,
                orderId: dataOrder.id,
                customer: dataOrder.id_customer,
                harga: dataOrderItem.harga_satuan,
                jumlah: dataOrderItem.kuantitas,
                gambar: dataOrderItem.link_item.gambar,
                nama: dataOrderItem.link_item.nama,
                stok: dataOrderItem.link_item.stok,
              }
              if (dataOrderItem.link_item.stok < dataOrderItem.kuantitas) {
                obj["error"] = "Stok tidak mencukupi"
                const produk = obj;
                KeranjangDB.updateProduk(produk);
                getAllProduks();
              } else if (dataOrderItem.link_item.status == 11) {
                obj["error"] = "Item sudah tidak aktif"
                const produk = obj;
                KeranjangDB.updateProduk(produk);
                getAllProduks();
              } else {
                const produk = obj;
                KeranjangDB.updateProduk(produk);
                getAllProduks();
              }
            })
          }
          else {
            setErrorKodeCustomer('Kode customer tidak sesusai');
            setIsHandleKodeCust(false);
          }
        } else {
          throw Error(response.data.message);
        }
      })
      .catch((error) => {
        setErrorKodeCustomer(error.message);
        setIsHandleKodeCust(false);
      });
  }

  const handleTambahJumlah = (item, keep) => {
    setIsHandleKodeCust(false);
    if (keep == true) {
      setShouldKeepOrder(true);
    }

    const exist = produks.find((x) => x.id === item.id);

    if (exist && item.stok > 0) {
      const produk = {
        id: item.id,
        nama: item.nama,
        orderId: orderId ? parseInt(orderId) : 'belum ada',
        customer: parseInt(idCust),
        harga: item.harga_satuan,
        jumlah: exist.jumlah < item.stok ? exist.jumlah + 1 : exist.jumlah,
        gambar: item.gambar,
        stok: item.stok
      };
      KeranjangDB.updateProduk(produk);
      getAllProduks();
      if (exist.jumlah == item.stok) {
        alert('maksimal stok di keranjang');
      }
    }
    else if (!exist && item.stok > 0) {
      const produk = {
        id: item.id,
        nama: item.nama,
        orderId: orderId ? parseInt(orderId) : 'belum ada',
        customer: parseInt(idCust),
        harga: item.harga_satuan,
        jumlah: 1,
        gambar: item.gambar,
        stok: item.stok
      };
      KeranjangDB.putProduk(produk);
      getAllProduks();
    }
  }

  const handleKurangJumlah = (item, keep) => {
    setIsHandleKodeCust(false);
    if (keep == true) {
      setShouldKeepOrder(true);
    }
    const exist = produks.find((x) => x.id === item.id);
    if (exist && exist.jumlah > 1) {
      const produk = {
        id: item.id,
        nama: item.nama,
        orderId: orderId ? parseInt(orderId) : 'belum ada',
        customer: parseInt(idCust),
        harga: item.harga_satuan,
        jumlah: exist.jumlah - 1,
        gambar: item.gambar,
        stok: item.stok
      };
      KeranjangDB.updateProduk(produk);
      getAllProduks();
    }

    if (exist && exist.jumlah == 1) {
      let setuju = confirm(`apakah anda yakin ingin menhapus produk ${item.nama} ?`);
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
    setIsHandleKodeCust(false);
    if (keep == true) {
      setShouldKeepOrder(true);
    }

    const exist = produks.find((x) => x.id === item.id);
    if (exist) {
      if (isNaN(newVal) == false) {
        const produk = {
          id: item.id,
          nama: item.nama,
          orderId: orderId ? parseInt(orderId) : 'belum ada',
          customer: parseInt(idCust),
          harga: item.harga_satuan,
          jumlah: isNaN(parseInt(newVal)) ? 0 : parseInt(newVal),
          gambar: item.gambar,
          stok: item.stok
        };
        KeranjangDB.updateProduk(produk);
        getAllProduks();
      }
    } else {
      if (isNaN(newVal) == false) {
        const produk = {
          id: item.id,
          nama: item.nama,
          orderId: orderId ? parseInt(orderId) : 'belum ada',
          customer: parseInt(idCust),
          harga: item.harga_satuan,
          gambar: item.gambar,
          jumlah: isNaN(parseInt(newVal)) ? 0 : parseInt(newVal),
          stok: item.stok
        };
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

  return (
    <main className='page_main'>
      <HeaderSales title="Order" isOrder={true} lihatKeranjang={lihatKeranjang} jumlahProdukKeranjang={jmlItem} toBack={toBack} />
      <div className="page_container pt-4">
        <div className="kode_customer">
          <p className='fw-bold'>Sudah punya kode customer?</p>
          <form onSubmit={handleKodeCustomer}>
            <div className="input-group">
              <input type="text" className="form-control"
                value={kodePesanan}
                onChange={(e) => setKodePesanan(e.target.value)}
              />
              <button type="submit" className="btn btn-primary" disabled={kodePesanan !== '' ? false : true}>Proses</button>
            </div>
          </form>
          {errorKodeCustomer && <small className='text-danger'>{errorKodeCustomer}</small>}
        </div>

        <HitungStok historyItem={historyItem} handleTambahJumlah={handleTambahJumlah}
          checkifexist={checkifexist} handleValueChange={handleValueChange}
          handleKurangJumlah={handleKurangJumlah} handleSubmitStokTerakhir={handleSubmitStokTerakhir}
          jumlahOrderRealTime={jumlahOrderRealTime} />

        <KeluarToko handleShow={handleShow} alasanPenolakan={alasanPenolakan}
          setAlasanPenolakan={setAlasanPenolakan} handleClose={handleClose}
          handleKeluarToko={handleKeluarToko} show={show} />

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
                shouldKeepOrder={shouldKeepOrder} diskonTypeCust={diskon}
              />
            }
          </InfiniteScroll>
        </div>
      </div>
    </main>
  );
}

export default Pemesanan;
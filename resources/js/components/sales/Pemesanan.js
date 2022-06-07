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

const Pemesanan = ({ location }) => {
  const { idCust } = useParams();
  const [urlApi, setUrlApi] = useState(`api/salesman/listitems/${idCust}`);
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
  const [alasanPenolakan, setAlasanPenolakan] = useState(null);
  const { state: idTripTetap } = location;
  const jamMasuk = Date.now() / 1000;

  const [historyItem, setHistoryItem] = useState([]);
  const { newHistoryItem, setNewHistoryItem } = useContext(HitungStokContext);
  const [jmlItem, setJmlItem] = useState(null);
  let jumlahProdukKeranjang = 0;
  const [jumlahOrderRealTime, setJumlahOrderRealTime] = useState([]);

  useEffect(() => {
    produks.map((produk) => {
      jumlahProdukKeranjang += produk.jumlah;
    })
    setJmlItem(jumlahProdukKeranjang);
  }, [produks])

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
        setHistoryItem(response.data.data);
        setJumlahOrderRealTime(response.data.orderRealTime);
      })
      .catch((error) => {
        console.log(error.message);
      });
  }, []);

  useEffect(() => {
    if (dataUser.nama && koordinat && isNaN(idTripTetap) && idTrip == null) {
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
      if (produk.jumlah == 0) handleDeleteProduct(produk);
    })
  }, [produks]);

  useEffect(() => {
    if (error) {
      setErrorMessage(error.message);
    }
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

  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);

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
          setErrorKodeCustomer(null);
          const dataOrderItems = response.data.dataOrderItem;
          const dataOrder = response.data.dataOrder;

          if (dataOrder.id_customer == idCust) {
            setOrderId(dataOrder.id);
            dataOrderItems.map((dataOrderItem) => {
              const produk = {
                id: dataOrderItem.id_item,
                orderId: dataOrder.id,
                customer: dataOrder.id_customer,
                harga: dataOrderItem.harga_satuan,
                jumlah: dataOrderItem.kuantitas,
                // gambar
              };
              KeranjangDB.updateProduk(produk);
              getAllProduks();
            })
          }
          else {
            setErrorKodeCustomer('kode customer tidak sesusai');
          }
        } else {
          throw Error(response.data.message);
        }

      })
      .catch((error) => {
        console.log(error.message);
        setErrorKodeCustomer(error.message);
      });
  }

  const handleTambahJumlah = (item) => {
    const exist = produks.find((x) => x.id === item.id);

    if (exist && item.stok > 0) {
      const produk = {
        id: item.id,
        nama: item.nama,
        orderId: orderId ? parseInt(orderId) : 'belum ada',
        customer: parseInt(idCust),
        harga: item.harga_satuan,
        jumlah: exist.jumlah < item.stok ? exist.jumlah + 1 : exist.jumlah,
        gambar: item.gambar
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
        gambar: item.gambar
      };
      KeranjangDB.putProduk(produk);
      getAllProduks();
    }
  }

  const handleKurangJumlah = (item) => {
    const exist = produks.find((x) => x.id === item.id);
    if (exist && exist.jumlah > 1) {
      const produk = {
        id: item.id,
        nama: item.nama,
        orderId: orderId ? parseInt(orderId) : 'belum ada',
        customer: parseInt(idCust),
        harga: item.harga_satuan,
        jumlah: exist.jumlah - 1,
        gambar: item.gambar
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

  const handleValueChange = (item, newVal) => {
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
          gambar: item.gambar
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
        'id_item': item.link_item[0].id,
        'quantity': newVal
      }
    })
      .then((response) => {
        console.log('stok terakhir customer', response.data);
      })
      .catch((error) => {
        console.log(error.message);
      });

    const exist = newHistoryItem.find((x) => x.link_item[0].id === item.link_item[0].id);
    if (exist) {
      setNewHistoryItem(
        newHistoryItem.map((x) => {
          if (x.link_item[0].id === item.link_item[0].id)
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
      setUrlApi(`api/salesman/listitems/${idCust}`)
    } else if (kataKunci !== '') {
      setUrlApi(`api/products/search/${kataKunci}`);
    }
  }

  return (
    <main className='page_main'>
      <HeaderSales title="Salesman" isOrder={true} lihatKeranjang={lihatKeranjang} produks={produks} jumlahProdukKeranjang={jmlItem} />
      <div className="page_container pt-4">
        <div className="kode_customer">
          <p>Sudah punya kode customer?</p>
          <form onSubmit={handleKodeCustomer}>
            <div className="input-group">
              <input type="text" className="form-control"
                value={kodePesanan}
                onChange={(e) => setKodePesanan(e.target.value)}
              />
              <button type="submit" className="btn btn-primary">Proses</button>
            </div>
          </form>
          {errorKodeCustomer && <p className='text-danger'>{errorKodeCustomer}</p>}
        </div>

        <HitungStok historyItem={historyItem} handleTambahJumlah={handleTambahJumlah}
          checkifexist={checkifexist} handleValueChange={handleValueChange}
          handleKurangJumlah={handleKurangJumlah} handleSubmitStokTerakhir={handleSubmitStokTerakhir}
          jumlahOrderRealTime={jumlahOrderRealTime} />

        <KeluarToko handleShow={handleShow} alasanPenolakan={alasanPenolakan}
          setAlasanPenolakan={setAlasanPenolakan} handleClose={handleClose}
          handleKeluarToko={handleKeluarToko} show={show} />

        <div>
          <h1 className='fs-4'>Item</h1>
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
                handleKurangJumlah={handleKurangJumlah} orderRealTime={orderRealTime} />
            }
          </InfiniteScroll>
        </div>
      </div>
    </main>
  );
}

export default Pemesanan;
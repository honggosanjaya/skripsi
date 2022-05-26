import React, { Component, useContext, useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
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
import { useHistory } from "react-router-dom";
import { Button, Modal } from 'react-bootstrap';
import { useMountedState } from 'react-use';

const Pemesanan = ({ location }) => {
  const [urlApi, setUrlApi] = useState('api/salesman/listitems');
  const { page, setPage, erorFromInfinite, paginatedData, isReachedEnd } = useInfinite(`${urlApi}`, 4);
  const { idCust } = useParams();
  const { token } = useContext(AuthContext);
  const { dataUser } = useContext(UserContext);
  const history = useHistory();
  const [kodePesanan, setKodePesanan] = useState('');
  const [kataKunci, setKataKunci] = useState('');
  const { produks, getAllProduks } = useContext(KeranjangSalesContext);
  const [errorMessage, setErrorMessage] = useState(null);
  const [orderId, setOrderId] = useState(null);
  const [errorKodeCustomer, setErrorKodeCustomer] = useState(null);
  const [koordinat, setKoordinat] = useState(null);
  const jamMasuk = Date.now() / 1000;
  const [idTrip, setIdTrip] = useState(null);
  const { state: idTripTetap } = location;
  const [show, setShow] = useState(false);
  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);
  const [alasanPenolakan, setAlasanPenolakan] = useState(null);

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
  }, []);

  useEffect(() => {
    console.log('idtrip', idTrip);
    console.log('idtriptetap', idTripTetap);
    console.log('isnan', isNaN(idTripTetap));
    console.log(koordinat);
    console.log(dataUser.nama);

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
      if (produk.jumlah == 0) {
        handleDeleteProduct(produk);
      }
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
    console.log('disini', idTrip);
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
    if (exist) {
      const produk = {
        id: item.id,
        orderId: orderId ? parseInt(orderId) : 'belum ada',
        customer: parseInt(idCust),
        harga: item.harga_satuan,
        jumlah: exist.jumlah < item.stok ? exist.jumlah + 1 : exist.jumlah,
      };
      KeranjangDB.updateProduk(produk);
      getAllProduks();
      if (exist.jumlah == item.stok) {
        alert('maksimal stok di keranjang');
      }
    }
    else {
      const produk = {
        id: item.id,
        orderId: orderId ? parseInt(orderId) : 'belum ada',
        customer: parseInt(idCust),
        harga: item.harga_satuan,
        jumlah: 1,
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
        orderId: orderId ? parseInt(orderId) : 'belum ada',
        customer: parseInt(idCust),
        harga: item.harga_satuan,
        jumlah: exist.jumlah - 1,
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
      if (isNaN(exist.jumlah)) {
        return 0
      } else {
        return exist.jumlah;
      }
    }
    else {
      return 0;
    }
  }

  const handleValueChange = (item, newVal) => {
    const exist = produks.find((x) => x.id === item.id);
    if (exist) {
      // if (isNaN(newVal) == false && newVal !== null && newVal !== '' && item.stok >= newVal && newVal > 0) {
      if (isNaN(newVal) == false) {
        const produk = {
          id: item.id,
          orderId: orderId ? parseInt(orderId) : 'belum ada',
          customer: parseInt(idCust),
          harga: item.harga_satuan,
          jumlah: isNaN(parseInt(newVal)) ? 0 : parseInt(newVal),
        };
        KeranjangDB.updateProduk(produk);
        getAllProduks();
      }
    } else {
      // if (isNaN(newVal) == false && newVal > 0) {
      if (isNaN(newVal) == false) {
        const produk = {
          id: item.id,
          orderId: orderId ? parseInt(orderId) : 'belum ada',
          customer: parseInt(idCust),
          harga: item.harga_satuan,
          jumlah: isNaN(parseInt(newVal)) ? 0 : parseInt(newVal),
        };
        KeranjangDB.putProduk(produk);
        getAllProduks();
      }
    }
  }

  const handleDeleteProduct = (item) => {
    KeranjangDB.deleteProduk(item.id);
    getAllProduks();
  }

  const handleCariProduk = (e) => {
    e.preventDefault();
    if (kataKunci == '') {
      setUrlApi('api/salesman/listitems')
    } else if (kataKunci !== '') {
      setUrlApi(`api/products/search/${kataKunci}`);
    }
  }

  return (
    <main className='page_main'>
      <HeaderSales title="Salesman" isOrder={true} lihatKeranjang={lihatKeranjang} />
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

        <div className="my-5">
          <h1 className="fs-6">Customer tidak jadi pesan?</h1>
          <Button variant="danger" onClick={handleShow}>
            Keluar
          </Button>

          <Modal show={show} onHide={handleClose}>
            <Modal.Header closeButton>
              <Modal.Title>Keluar Toko</Modal.Title>
            </Modal.Header>
            <Modal.Body>
              <label className="form-label mt-3">Alasan Penolakan</label>
              <input type="text" className="form-control"
                value={alasanPenolakan || ''}
                onChange={(e) => setAlasanPenolakan(e.target.value)}
              />
            </Modal.Body>
            <Modal.Footer>
              <Button variant="secondary" onClick={handleClose}>
                Batal
              </Button>
              <Button variant="primary" onClick={handleKeluarToko}>
                Keluar
              </Button>
            </Modal.Footer>
          </Modal>
        </div>

        <div className="item">
          <h1 className='fs-4'>Item</h1>
          <div className='mb-3'>
            <form onSubmit={handleCariProduk}>
              <div className="input-group">
                <input type="text" className="form-control" placeholder="Cari Produk..."
                  value={kataKunci}
                  onChange={(e) => setKataKunci(e.target.value)}
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
                handleKurangJumlah={handleKurangJumlah} />
            }
          </InfiniteScroll>
        </div>
      </div>
    </main>
  );
}

export default Pemesanan;
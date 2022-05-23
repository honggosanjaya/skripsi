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

const Pemesanan = () => {
  const [urlApi, setUrlApi] = useState('api/salesman/listitems')
  const { page, setPage, erorFromInfinite, paginatedData, isReachedEnd } = useInfinite(`${urlApi}`, 4);
  const { idCust } = useParams()
  const { token } = useContext(AuthContext);
  const [kodePesanan, setKodePesanan] = useState('');
  const [kataKunci, setKataKunci] = useState('');
  const { produks, getAllProduks } = useContext(KeranjangSalesContext);
  const [errorMessage, setErrorMessage] = useState(null);

  const { data: dataCustomer, error } = useSWR(
    [`${window.location.origin}/api/tripCustomer/${idCust}`, token], {
    revalidateOnFocus: false,
  });

  useEffect(() => {
    getAllProduks();
  }, [])

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

  const handleTambahJumlah = (item) => {
    const exist = produks.find((x) => x.id === item.id);
    if (exist) {
      const produk = {
        id: item.id,
        customer: idCust,
        nama: item.nama,
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
        customer: idCust,
        nama: item.nama,
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
        customer: idCust,
        nama: item.nama,
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
          customer: idCust,
          nama: item.nama,
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
          customer: idCust,
          nama: item.nama,
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
      <HeaderSales title="Salesman" isOrder={true} />
      <div className="page_container pt-4">
        <p>Sudah punya kode customer?</p>
        <input type="text" className="form-control"
          value={kodePesanan}
          onChange={(e) => setKodePesanan(e.target.value)}
        />

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
    </main>
  );
}

export default Pemesanan;
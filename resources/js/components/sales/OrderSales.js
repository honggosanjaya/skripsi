import React, { useContext, useState, useEffect } from 'react';
import { OrderSalesContext } from '../../contexts/OrderSalesContext';
import { Link } from "react-router-dom";
import { convertPrice } from "../reuse/HelperFunction";
import KeranjangDB from './KeranjangDB';
import HeaderSales from './HeaderSales';
import urlAsset from '../../config';

const OrderSales = () => {
  const { produks } = useContext(OrderSalesContext);
  const [keranjangItem, setKeranjangItem] = useState(null);

  const getAllProduks = () => {
    const keranjangItem = KeranjangDB.getAllProduks();
    keranjangItem.then((response) => {
      setKeranjangItem(response);
    })
  }

  useEffect(() => {
    getAllProduks();
  }, [])

  const handleTambahKeranjang = (item) => {
    const barang = {
      id: item.id,
      nama: item.nama,
      harga: item.harga,
      jumlah: 1,
    };
    KeranjangDB.putProduk(barang);
  }


  const tambahJumlahProduk = (item) => {
    const barang = {
      id: item.id,
      nama: item.nama,
      harga: item.harga,
      jumlah: item.jumlah + 1,
    };
    KeranjangDB.updateProduk(barang);
    getAllProduks();
  }

  const kurangJumlahProduk = (item) => {
    const barang = {
      id: item.id,
      nama: item.nama,
      harga: item.harga,
      jumlah: item.jumlah - 1,
    };
    KeranjangDB.updateProduk(barang);
    getAllProduks();
  }


  return (
    <main className="page_main">
      <HeaderSales title="Order" isOrder={true} />
      <div className="page_container pt-4">
        <div className="row customer_info">
          <div className="col-4">
            <img src={`${urlAsset}/images/default_fotoprofil.png`} className="avatar_pp d-block mx-auto" />
            <button className="btn btn-primary w-100 d-block my-3">Invoice</button>
            <button className="btn btn-warning w-100 d-block">History</button>
          </div>
          <div className="col-8">
            <h1>Customer Information</h1>
            <h2>Nama</h2>
            <h2>Jenis</h2>
            <h2>Wilayah</h2>
            <h2>Alamat</h2>
            <h2>Telepon</h2>
            <h2>Limit Pembelian</h2>
          </div>
        </div>



        <div className="productCard_wrapper mt-4">
          {produks.map(produk => (
            <div className="card product_card" key={produk.id}>
              <div className="product_img">
                <img src="/images/sales/gambar-produk.jpg" alt="" />
              </div>
              <div className="product_desc">
                <p className="product_title text-elipsis elipsis-two">{produk.nama}</p>
                <p>{convertPrice(produk.harga)}</p>

                <div className="d-flex flex-row mt-3 mb-2">
                  <button
                    className="btn btn-primary"
                    disabled={produk.jumlah === 0 ? true : false}
                    onClick={() => kurangJumlahProduk(produk)}
                  > - </button>

                  <input type="text" className="text-center"
                    style={{ width: `${produk.jumlah.toString().length + 1}ch` }}
                    value={produk.jumlah}
                    disabled
                  />

                  <button
                    className="btn btn-primary"
                    onClick={() => tambahJumlahProduk(produk)}
                  >+ </button>
                </div>

              </div>
            </div>
          ))}
        </div>

        <Link to="/produk/keranjang">Lihat Keranjang</Link>
      </div>

    </main>
  );
}

export default OrderSales;


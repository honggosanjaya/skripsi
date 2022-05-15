import React, { useContext } from 'react';
import { ProdukContext } from '../../contexts/ProdukContext';
import { Link } from "react-router-dom";
import { convertPrice } from "../reuse/HelperFunction";
import KeranjangDB from './KeranjangDB';

const Produk = () => {
  const { produks } = useContext(ProdukContext);

  const handleTambahKeranjang = (item) => {
    const produk = {
      id: item.id,
      nama: item.nama,
      harga: item.harga,
      jumlah: 1,
    };
    KeranjangDB.putProduk(produk);
  }


  return (
    <main className="page_main">
      <div className="page_container pt-4">
        <div className="productCard_wrapper">
          {produks.map(produk => (
            <div className="card product_card" key={produk.id}>
              <div className="product_img">
                <img src="/images/sales/gambar-produk.jpg" alt="" />
              </div>
              <div className="product_desc">
                <p className="product_title text-elipsis elipsis-two">{produk.nama}</p>
                <p>{convertPrice(produk.harga)}</p>

                <button className="btn addCart-btn" onClick={() => handleTambahKeranjang(produk)}>
                  <span className="iconify" data-icon="bxs:cart-add"></span>
                </button>

              </div>
            </div>
          ))}
        </div>

        <Link to="/produk/keranjang">Lihat Keranjang</Link>
      </div>

    </main>
  );
}

export default Produk;


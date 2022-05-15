import React, { useContext, useEffect, useState } from 'react';
import KeranjangDB from './KeranjangDB';
import { convertPrice } from "../reuse/HelperFunction";

const Keranjang = () => {
  const [produks, setProduks] = useState(null);

  const getAllProduks = () => {
    const produks = KeranjangDB.getAllProduks();
    produks.then((response) => {
      setProduks(response);
    })
  }

  useEffect(() => {
    getAllProduks();
  }, [])

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

  const tambahJumlahProduk = (item) => {
    const produk = {
      id: item.id,
      nama: item.nama,
      harga: item.harga,
      jumlah: item.jumlah + 1,
    };
    KeranjangDB.updateProduk(produk);
    getAllProduks();
  }

  const kurangJumlahProduk = (item) => {
    const produk = {
      id: item.id,
      nama: item.nama,
      harga: item.harga,
      jumlah: item.jumlah - 1,
    };
    KeranjangDB.updateProduk(produk);
    getAllProduks();
  }

  const hapusProduk = (item) => {
    KeranjangDB.deleteProduk(item.id);
    getAllProduks();
  }


  return (
    <main className="page_main">
      <div className="page_container pt-4">
        {(produks && produks.length > 0) && produks.map((produk) => (
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
              {/* <img className="item_image" src={item.gambar} alt="" /> */}
            </div>

            <div className={produk.isSelected ? "grid_item" : ""}>
              <div className="detail_item pl-3">
                <h5 className={`${produk.isSelected ? 'elipsis' : ''}`}>{produk.nama}</h5>

                <div className="d-flex flex-row mt-3 mb-2">
                  <button
                    className="btn btn-primary"
                    disabled={produk.jumlah === 1 ? true : false}
                    onClick={() => kurangJumlahProduk(produk)}
                  >
                    -
                  </button>

                  <input type="text" className="text-center"
                    style={{ width: `${produk.jumlah.toString().length + 1}ch` }}
                    value={produk.jumlah}
                    disabled
                  />

                  <button
                    className="btn btn-primary"
                    onClick={() => tambahJumlahProduk(produk)}>
                    +
                  </button>
                </div>

                <div>{convertPrice(produk.harga)}</div>
              </div>

              {produk.isSelected &&
                <button
                  className="btn btn-danger btn_deleteItem"
                  onClick={() => hapusProduk(produk)}>
                  <span class="iconify " data-icon="bxs:trash"></span>
                </button>}
            </div>
          </div>
        ))}

        {(produks && produks.length === 0) && <p className='text-danger'>keranjang kosong</p>}
      </div>
    </main>
  );
}

export default Keranjang;


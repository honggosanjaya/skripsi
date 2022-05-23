import React, { useContext, useEffect, useState, createContext } from 'react';
import KeranjangDB from '../reuse/KeranjangDB';
import { convertPrice } from "../reuse/HelperFunction";
import { KeranjangSalesContext } from '../../contexts/KeranjangSalesContext';

const KeranjangSales = () => {
  const { produks, setProduks, getAllProduks } = useContext(KeranjangSalesContext);
  const [errorMessage, setErrorMessage] = useState(null);
  const [successMessage, setSuccessMessage] = useState(null);

  let subtotal = 0;

  useEffect(() => {
    getAllProduks();
  }, [])

  const hapusSemuaProduk = () => {
    produks.map((produk) => {
      KeranjangDB.deleteProduk(produk.id);
      getAllProduks();
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

  const kirimPesanan = (e) => {
    e.preventDefault();
    axios({
      method: "post",
      url: `${window.location.origin}/api/salesman/buatOrder`,
      headers: {
        Accept: "application/json",
      },
      data: {
        keranjang: produks,
      }
    })
      .then(response => {
        console.log(response);
        setSuccessMessage(response.data.success_message);
        hapusSemuaProduk();
      })
      .catch(error => {
        console.log(error.message);
        setErrorMessage(error.message);
      });
  }


  return (
    <main className="page_main">

      <div className="page_container pt-4">
        {(produks && produks.length === 0) && <p className='text-danger text-center'>Keranjang Kosong</p>}
        {(produks && produks.length !== 0) && <button className='btn btn-danger' onClick={hapusSemuaProduk}>Hapus Semua</button>}

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
                  <span className="iconify " data-icon="bxs:trash"></span>
                </button>}
            </div>
          </div>
        ))}


        {produks.length > 0 && <div className="button_bottom d-flex justify-content-between">
          <div>
            <p className='mb-0'>Total Pesanan:</p>
            {produks.map((produk) => {
              subtotal = subtotal + (produk.jumlah * produk.harga);
            })}
            <h1 className='mb-0 fs-4'>{convertPrice(subtotal)}</h1>
          </div>
          <button className='btn btn-success' onClick={kirimPesanan}>CHECKOUT</button>
        </div>}
      </div>
    </main>
  );
}

export default KeranjangSales;


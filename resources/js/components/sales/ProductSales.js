import React, { Component, useEffect, useState } from 'react';
import { convertPrice } from "../reuse/HelperFunction";
import urlAsset from '../../config';

const ProductSales = ({ tipeHarga, listItems, handleTambahJumlah, checkifexist, handleValueChange, handleKurangJumlah, orderRealTime, groupingItemStok, produkDlmKeranjang, isHandleKodeCust, shouldKeepOrder, diskonTypeCust }) => {
  const [orderItems, setOrderItems] = useState([]);

  useEffect(() => {
    if (isHandleKodeCust) {
      let same = listItems.filter(function (o) {
        return produkDlmKeranjang.some(function (o2) {
          return o.id === o2.id;
        })
      });

      let differ = listItems.filter(object1 => {
        return !produkDlmKeranjang.some(object2 => {
          return object1.id === object2.id;
        });
      });

      differ.map((d) => {
        same.push(d);
      })

      setOrderItems(same);
    } else {
      setOrderItems(orderItems);
    }
  }, [isHandleKodeCust, listItems])

  return (
    <div className="productCard_wrapper">
      {(isHandleKodeCust || shouldKeepOrder) && orderItems.map((item) => (
        <div className={`card product_card ${(item.stok != null) && (item.status_enum == '-1' || item.stok == 0 || item.stok <= item.min_stok) ? 'inactive_product' : ''}`} key={item.id}>
          {item.stok < 10 && item.stok > 0 && item.status_enum != '-1' && item.stok > item.min_stok && <span className="badge badge_stok">Stok Menipis</span>}

          {(item.stok != null) && (item.status_enum == '-1' || item.stok == 0 || item.stok <= item.min_stok) &&
            <div className='inactive_sign'>
              <p className='mb-0'>Tidak Tersedia</p>
            </div>}
          <div className="product_img">
            {item.gambar ?
              <img src={`${urlAsset}/storage/item/${item.gambar}`} className="img-fluid" /> :
              <img src={`${urlAsset}/images/default_produk.png`} className="img-fluid" />}
          </div>
          <div className="product_desc">
            <h1 className='nama_produk fs-6'>{item.nama}</h1>
            <p className='mb-0 text-decoration-line-through'>
              {tipeHarga && tipeHarga == 1 ? convertPrice(item.harga1_satuan) : tipeHarga == 2 && item.harga2_satuan ? convertPrice(item.harga2_satuan) : tipeHarga == 3 && item.harga3_satuan ? convertPrice(item.harga3_satuan) : convertPrice(item.harga1_satuan)}
            </p>
            <p className='mb-0'><b className='text-danger'>
              {tipeHarga && tipeHarga == 1 ? convertPrice(item.harga1_satuan - (item.harga1_satuan * (diskonTypeCust ?? 0) / 100)) :
                tipeHarga == 2 && item.harga2_satuan ? convertPrice(item.harga2_satuan - (item.harga2_satuan * (diskonTypeCust ?? 0) / 100)) :
                  tipeHarga == 3 && item.harga3_satuan ? convertPrice(item.harga3_satuan - (item.harga3_satuan * (diskonTypeCust ?? 0) / 100)) :
                    convertPrice(item.harga1_satuan - (item.harga1_satuan * (diskonTypeCust ?? 0) / 100))
              }
            </b> / {item.satuan}</p>
            <p className='mb-0'>Stok: </p>
            <table className='table table-bordered border-secondary mb-0'>
              <thead>
                <tr>
                  <th scope="col">R.Time</th>
                  <th scope="col">Today</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{(item.stok ?? 0) + (groupingItemStok[item.id] ?? 0) - (orderRealTime[item.id] ?? 0)} {item.satuan}</td>
                  <td>{(item.stok ?? 0) + (groupingItemStok[item.id] ?? 0)} {item.satuan}</td>
                </tr>
              </tbody>
            </table>

            <div className="d-flex justify-content-between mt-2">
              <button className="btn btn-sm btn-primary" onClick={() => handleKurangJumlah(item, true)}>
                -
              </button>
              <input type="number" className="form-control"
                value={checkifexist(item)}
                onChange={(e) => handleValueChange(item, e.target.value, true)}
              />

              {item.stok == null ?
                <button className="btn btn-sm btn-primary" onClick={() => handleTambahJumlah(item, true, groupingItemStok[item.id] ?? 0)}>
                  +
                </button>
                :
                <button className="btn btn-sm btn-primary" onClick={() => handleTambahJumlah(item, true)}>
                  +
                </button>}
            </div>
          </div>
        </div>
      ))}

      {(!isHandleKodeCust && !shouldKeepOrder) && listItems.map((item) => (
        <div className={`card product_card ${(item.stok != null) && (item.status_enum == '-1' || item.stok == 0 || item.stok <= item.min_stok) ? 'inactive_product' : ''}`} key={item.id}>
          {item.stok < 10 && item.stok > 0 && item.status_enum != '-1' && item.stok > item.min_stok && <span className="badge badge_stok">Stok Menipis</span>}
          {(item.stok != null) && (item.status_enum == '-1' || item.stok == 0 || item.stok <= item.min_stok) &&
            <div className='inactive_sign'>
              <p className='mb-0'>Tidak Tersedia</p>
            </div>}
          <div className="product_img">
            {item.gambar ?
              <img src={`${urlAsset}/storage/item/${item.gambar}`} className="img-fluid" /> :
              <img src={`${urlAsset}/images/default_produk.png`} className="img-fluid" />
            }
          </div>
          <div className="product_desc">
            <h1 className='nama_produk fs-6 fw-bold'>{item.nama}</h1>
            <p className='mb-0 text-decoration-line-through'>
              {tipeHarga && tipeHarga == 1 ? convertPrice(item.harga1_satuan) : tipeHarga == 2 && item.harga2_satuan ? convertPrice(item.harga2_satuan) : tipeHarga == 3 && item.harga3_satuan ? convertPrice(item.harga3_satuan) : convertPrice(item.harga1_satuan)}
            </p>
            <p className='mb-0'><b className='text-danger'>
              {tipeHarga && tipeHarga == 1 ? convertPrice(item.harga1_satuan - (item.harga1_satuan * (diskonTypeCust ?? 0) / 100)) :
                tipeHarga == 2 && item.harga2_satuan ? convertPrice(item.harga2_satuan - (item.harga2_satuan * (diskonTypeCust ?? 0) / 100)) :
                  tipeHarga == 3 && item.harga3_satuan ? convertPrice(item.harga3_satuan - (item.harga3_satuan * (diskonTypeCust ?? 0) / 100)) :
                    convertPrice(item.harga1_satuan - (item.harga1_satuan * (diskonTypeCust ?? 0) / 100))
              }
            </b> / {item.satuan}</p>
            <p className='mb-0'>Stok: </p>
            <table className='table table-bordered border-secondary mb-0'>
              <thead>
                <tr>
                  <th scope="col">R.Time</th>
                  <th scope="col">Today</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{(item.stok ?? 0) + (groupingItemStok[item.id] ?? 0) - (orderRealTime[item.id] ?? 0)} {item.satuan}</td>
                  <td>{(item.stok ?? 0) + (groupingItemStok[item.id] ?? 0)} {item.satuan}</td>
                </tr>
              </tbody>
            </table>

            <div className="d-flex justify-content-between mt-2">
              <button className="btn btn-sm btn-primary" onClick={() => handleKurangJumlah(item)}>
                -
              </button>
              <input type="number" className="form-control"
                value={checkifexist(item)}
                onChange={(e) => handleValueChange(item, e.target.value)}
              />
              {item.stok == null ?
                <button className="btn btn-sm btn-primary" onClick={() => handleTambahJumlah(item, false, groupingItemStok[item.id] ?? 0)}>
                  +
                </button>
                :
                <button className="btn btn-sm btn-primary" onClick={() => handleTambahJumlah(item)}>
                  +
                </button>}
            </div>
          </div>
        </div>
      ))}
    </div>
  );
}

export default ProductSales;
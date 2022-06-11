import React, { Component, useEffect, useState } from 'react';
import { convertPrice } from "../reuse/HelperFunction";
import urlAsset from '../../config';

const ProductSales = ({ listItems, handleTambahJumlah, checkifexist, handleValueChange, handleKurangJumlah, orderRealTime, produkDlmKeranjang, isHandleKodeCust, shouldKeepOrder, diskonTypeCust }) => {
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
        <div className={`card product_card ${(item.status == 11 || item.stok == 0) ? 'inactive_product' : ''}`} key={item.id}>
          {item.stok < 10 && item.stok > 0 && item.status != 11 && <span className="badge badge_stok">Stok Menipis</span>}
          {(item.status == 11 || item.stok == 0) &&
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
            <p className='mb-0 text-decoration-line-through'>{convertPrice(item.harga_satuan)}</p>
            <p className='mb-0'><b className='text-danger'>{convertPrice(item.harga_satuan - (item.harga_satuan * (diskonTypeCust ?? 0) / 100))}</b> / {item.satuan}</p>
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
                  <td>{item.stok - (orderRealTime[item.id] ?? 0)} {item.satuan}</td>
                  <td>{item.stok} {item.satuan}</td>
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
              <button className="btn btn-sm btn-primary" onClick={() => handleTambahJumlah(item, true)}>
                +
              </button>
            </div>
          </div>
        </div>
      ))}

      {(!isHandleKodeCust && !shouldKeepOrder) && listItems.map((item) => (
        <div className={`card product_card ${(item.status == 11 || item.stok == 0) ? 'inactive_product' : ''}`} key={item.id}>
          {item.stok < 10 && item.stok > 0 && item.status != 11 && <span className="badge badge_stok">Stok Menipis</span>}
          {(item.status == 11 || item.stok == 0) &&
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
            <p className='mb-0 text-decoration-line-through'>{convertPrice(item.harga_satuan)}</p>
            <p className='mb-0'><b className='text-danger'>{convertPrice(item.harga_satuan - (item.harga_satuan * (diskonTypeCust ?? 0) / 100))}</b> / {item.satuan}</p>
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
                  <td>{item.stok - (orderRealTime[item.id] ?? 0)} {item.satuan}</td>
                  <td>{item.stok} {item.satuan}</td>
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
              <button className="btn btn-sm btn-primary" onClick={() => handleTambahJumlah(item)}>
                +
              </button>
            </div>
          </div>
        </div>
      ))}
    </div>
  );
}

export default ProductSales;
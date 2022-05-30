import React, { Component } from 'react';
import { convertPrice } from "../reuse/HelperFunction";
import urlAsset from '../../config';

const ProductSales = ({ listItems, handleTambahJumlah, checkifexist, handleValueChange, handleKurangJumlah }) => {
  return (
    <div className="productCard_wrapper">
      {listItems.map((item, index) => (
        <div className={`card product_card ${(item.status == 11 || item.stok == 0) ? 'inactive_product' : ''}`} key={index}>
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
            <h1 className='nama_produk fs-6'>{item.nama}</h1>
            <p className='mb-0'>{convertPrice(item.harga_satuan)} / {item.satuan}</p>
            <p className='mb-0'>Stok: </p>
            <table className='table table-bordered border-secondary mb-0'>
              <thead>
                <tr>
                  <th scope="col">Real</th>
                  <th scope="col">Today</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>none</td>
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
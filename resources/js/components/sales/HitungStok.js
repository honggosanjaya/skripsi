import React, { useContext, useEffect } from 'react';
import urlAsset from '../../config';
import { HitungStokContext } from '../../contexts/HitungStokContext';

const HitungStok = ({ historyItem, checkifexist, handleValueChange, handleTambahJumlah, handleKurangJumlah, handleSubmitStokTerakhir, jumlahOrderRealTime }) => {
  const { newHistoryItem, setNewHistoryItem } = useContext(HitungStokContext);

  useEffect(() => {
    setNewHistoryItem(historyItem);
  }, [historyItem]);

  // useEffect(() => {
  //   setNewHistoryItem(historyItem);
  // }, []);

  const handlePilihProdukChange = (item) => {
    const exist = newHistoryItem.find((x) => x.link_item.id === item.link_item.id);
    if (exist) {
      setNewHistoryItem(newHistoryItem.map((x) => {
        if (x.link_item.id === item.link_item.id)
          return { ...exist, isSelected: !x.isSelected }
        else
          return x
      }));
    }
  }

  const handleStokTerakhirChange = (item, newValue) => {
    const exist = newHistoryItem.find((x) => x.link_item.id === item.link_item.id);
    if (exist) {
      setNewHistoryItem(
        newHistoryItem.map((x) => {
          if (x.link_item.id === item.link_item.id)
            return { ...exist, newStok: newValue }
          else
            return x
        }));
    }
  }

  return (
    <div className="history-item mt-4">
      <h1 className='fs-5 fw-bold'>History Item</h1>
      {newHistoryItem.length == 0 && <small className="text-danger text-center d-block">Tidak Ada Riwayat Pembelian</small>}
      {newHistoryItem.map((item, index) => (
        <div className={`card_historyItem position-relative p-3`} key={index}>
          {item.link_item.stok < 10 && item.link_item.stok > 0 && item.link_item.status != 11 && item.link_item.stok > item.link_item.min_stok && <span className="badge badge_stok">Stok Menipis</span>}

          {(item.link_item.status == 11 || item.link_item.stok == 0 || item.link_item.stok <= item.link_item.min_stok) &&
            <span className="badge badge_stok">Tidak Tersedia</span>
          }

          <div className="row">
            <div className="col-2">
              {item.link_item.gambar ?
                <img src={`${urlAsset}/storage/item/${item.link_item.gambar}`} className="item_image" />
                : <img src={`${urlAsset}/images/default_produk.png`} className="item_image border" />}
            </div>
            <div className="col-10">
              <h1 className="fs-6 ms-2 mb-1 text-capitalize fw-bold">{item.link_item.nama}</h1>
              <p className="mb-0 ms-2">{item.link_item.harga_satuan} / {item.link_item.satuan}</p>
            </div>

            <p className="mb-0">Max stok : {item.stok_maksimal_customer}</p>
            <div className="row">
              <div className="col">
                <p className="mb-0">Stok left</p>
              </div>

              <div className="col">
                <input type="number" className="form-control"
                  value={item.newStok ? item.newStok : (item.newStok == '' ? '' : item.stok_terakhir_customer)}
                  onChange={(e) => handleStokTerakhirChange(item, e.target.value)}
                  disabled={item.isSelected ? false : true}
                  min='0'
                />
              </div>

              <div className="col">
                {!item.isSelected && <label>
                  <input type="checkbox" className="d-none"
                    checked={item.isSelected || false}
                    onChange={() => { handlePilihProdukChange(item) }}
                  />
                  <span className="btn btn-warning ms-1">Ubah</span>
                </label>}

                {item.isSelected && item.newStok &&
                  <button className="btn btn-success ms-1" onClick={() => handleSubmitStokTerakhir(item, item.newStok)}>OK</button>}

                {item.isSelected && !item.newStok &&
                  <button className="btn btn-success ms-1" onClick={() => handlePilihProdukChange(item)}>OK</button>}
              </div>
            </div>
          </div>

          <div className="row">
            <div className="col-3">
              <p className="mb-0">Stok :</p>
            </div>
            <div className="col-9">
              <table className='table table-bordered border-secondary mb-0'>
                <thead>
                  <tr>
                    <th scope="col">Real Time</th>
                    <th scope="col">Today</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{item.link_item.stok - (jumlahOrderRealTime[item.link_item.id] ?? 0)} / {item.link_item.satuan}</td>
                    <td>{item.link_item.stok} / {item.link_item.satuan}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          {(item.link_item.status != 11 && item.link_item.stok != 0 && item.link_item.stok > item.link_item.min_stok) &&
            <div className="d-flex justify-content-between mt-2 w-75 mx-auto">
              <button className="btn btn-sm btn-primary" onClick={() => handleKurangJumlah(item.link_item)}>
                -
              </button>
              <input type="number" className="form-control w-50"
                value={checkifexist(item.link_item)}
                onChange={(e) => handleValueChange(item.link_item, e.target.value)}
              />
              <button className="btn btn-sm btn-primary" onClick={() => handleTambahJumlah(item.link_item)}>
                +
              </button>
            </div>
          }
        </div>
      ))}
    </div>
  );
}

export default HitungStok;
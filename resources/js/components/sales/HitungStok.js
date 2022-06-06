import React, { useContext, useEffect } from 'react';
import urlAsset from '../../config';
import { HitungStokContext } from '../../contexts/HitungStokContext';

const HitungStok = ({ historyItem, checkifexist, handleValueChange, handleTambahJumlah, handleKurangJumlah, handleSubmitStokTerakhir }) => {
  const { newHistoryItem, setNewHistoryItem } = useContext(HitungStokContext);

  useEffect(() => {
    setNewHistoryItem(historyItem);
  }, [historyItem]);

  const handlePilihProdukChange = (item) => {
    const exist = newHistoryItem.find((x) => x.link_item[0].id === item.link_item[0].id);
    if (exist) {
      setNewHistoryItem(newHistoryItem.map((x) => {
        if (x.link_item[0].id === item.link_item[0].id)
          return { ...exist, isSelected: !x.isSelected }
        else
          return x
      }));
    }
  }

  const handleStokTerakhirChange = (item, newValue) => {
    const exist = newHistoryItem.find((x) => x.link_item[0].id === item.link_item[0].id);
    if (exist) {
      setNewHistoryItem(
        newHistoryItem.map((x) => {
          if (x.link_item[0].id === item.link_item[0].id)
            return { ...exist, newStok: newValue }
          else
            return x
        }));
    }
  }

  return (
    <div className="history-item mt-4">
      <h1 className='fs-4'>History Item</h1>
      {newHistoryItem.map((item, index) => (
        <div className="card_historyItem p-3" key={index}>
          <h1 className="fs-6 text-capitalize">{item.link_item[0].nama}</h1>
          <div className="row">
            <div className="col-4">
              {item.link_item[0].gambar ?
                <img src={`${urlAsset}/images/${item.link_item[0].gambar}`} className="item_image" />
                : <img src={`${urlAsset}/images/default_fotoprofil.png`} className="item_image" />}
            </div>
            <div className="col-8">
              <p className="mb-0">price : {item.link_item[0].harga_satuan}</p>
              <p className="mb-0">max stok : {item.stok_maksimal_customer}</p>
              <div >
                <p className="mb-0">stok left :</p>

                <input type="number" className="form-control"
                  value={item.newStok ? item.newStok : (item.newStok == '' ? '' : item.stok_terakhir_customer)}
                  onChange={(e) => handleStokTerakhirChange(item, e.target.value)}
                  disabled={item.isSelected ? false : true}
                  min='0'
                />

                {!item.isSelected && <label className="customCheckbox_wrapper">
                  <input type="checkbox" className="btn btn-warning"
                    checked={item.isSelected || false}
                    onChange={() => { handlePilihProdukChange(item) }}
                  />
                  <span className="btn btn-warning">Ubah</span>
                </label>}

                {item.isSelected && item.newStok &&
                  <button className="btn btn-success" onClick={() => handleSubmitStokTerakhir(item, item.newStok)}>OK</button>}

                {item.isSelected && !item.newStok &&
                  <button className="btn btn-success" onClick={() => handlePilihProdukChange(item)}>OK</button>}
              </div>
            </div>
          </div>

          <div className="row">
            <div className="col-3">
              <p className="mb-0">stok :</p>
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
                    <td>{item.link_item[0].stok}</td>
                    <td>none</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div className="d-flex justify-content-between mt-2">
            <button className="btn btn-sm btn-primary" onClick={() => handleKurangJumlah(item.link_item[0])}>
              -
            </button>
            <input type="number" className="form-control"
              value={checkifexist(item.link_item[0])}
              onChange={(e) => handleValueChange(item.link_item[0], e.target.value)}
            />
            <button className="btn btn-sm btn-primary" onClick={() => handleTambahJumlah(item.link_item[0])}>
              +
            </button>
          </div>
        </div>
      ))}
    </div>
  );
}

export default HitungStok;
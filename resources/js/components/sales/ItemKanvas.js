import React, { useState, useContext, useEffect, Fragment } from 'react';
import HeaderSales from './HeaderSales';
import { UserContext } from '../../contexts/UserContext';
import LoadingIndicator from '../reuse/LoadingIndicator';
import { Link } from "react-router-dom";

const ItemKanvas = () => {
  const { dataUser } = useContext(UserContext);
  const [listKanvas, setListKanvas] = useState(null);
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    if (dataUser.id_staff) {
      setIsLoading(true);
      axios({
        method: "get",
        url: `${window.location.origin}/api/salesman/itemkanvasactive/${dataUser.id_staff}`,
        headers: {
          Accept: "application/json",
        },
      })
        .then(response => {
          console.log(response.data.data);
          setListKanvas(response.data.data);
          setIsLoading(false);
        })
        .catch(error => {
          console.log(error.message);
          setIsLoading(false);
        });
    }
  }, [dataUser])

  return (
    <main className="page_main">
      <HeaderSales title="Item Kanvas" />
      {isLoading && <LoadingIndicator />}
      <div className="page_container pt-4">
        <div className="row">
          <div className="col d-flex justify-content-end">
            <Link to="/salesman/itemkanvas/history" className="btn btn-primary mb-4">
              <span className="iconify fs-3 text-white me-2" data-icon="ic:round-history"></span>History
            </Link>
          </div>
        </div>

        <div className='info-2column'>
          <span className='d-flex'>
            <b>Nama kanvas</b>
            {listKanvas && listKanvas.length == 0 ? <p className='mb-0 word_wrap'>-</p>
              : <p className='mb-0 word_wrap'>{listKanvas && listKanvas[0].nama}</p>
            }
          </span>
          <span className='d-flex'>
            <b>Waktu Dibawa</b>
            {listKanvas && listKanvas.length == 0 ? <p className='mb-0 word_wrap'>-</p>
              : <p className='mb-0 word_wrap'>{listKanvas && listKanvas[0].waktu_dibawa}</p>
            }
          </span>
        </div>

        <div className="table-responsive">
          <table className="table mt-3">
            <thead>
              <tr>
                <th scope="col" className='text-center'>No</th>
                <th scope="col" className='text-center'>Nama Barang</th>
                <th scope="col" className='text-center'>Stok Awal</th>
                <th scope="col" className='text-center'>Sisa Stok</th>
              </tr>
            </thead>
            <tbody>
              {listKanvas && listKanvas.length == 0 &&
                <tr>
                  <td colSpan={4}>
                    <span className='text-danger text-center'>Tidak ada kanvas yang sedang aktif</span>
                  </td>
                </tr>
              }

              {listKanvas && listKanvas.map((kanvas, index) => (
                <tr key={index}>
                  <td className='text-center'>{index + 1}</td>
                  <td>{kanvas.link_item.nama ?? null}</td>
                  <td className='text-center'>{kanvas.stok_awal ?? null}</td>
                  <td className='text-center'>{kanvas.sisa_stok ?? null}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </main>
  );
}

export default ItemKanvas;
import React, { useState, useContext, useEffect, Fragment } from 'react';
import axios from 'axios';
import HeaderSales from './HeaderSales';
import { UserContext } from '../../contexts/UserContext';
import { getTime } from "../reuse/HelperFunction";

const HistoryTrip = () => {
  const { dataUser } = useContext(UserContext);

  var todayDate = new Date().toISOString().slice(0, 10);

  const [tanggal, setTanggal] = useState(todayDate);
  const [dataKunjungans, setDataKunjungans] = useState(null);

  useEffect(() => {
    if (dataUser.id_staff) {
      axios({
        method: "post",
        url: `${window.location.origin}/api/historytrip/${dataUser.id_staff}`,
        headers: {
          Accept: "application/json",
        },
        data: {
          date: tanggal
        },
      })
        .then(response => {
          console.log('dataku', response.data.data);
          setDataKunjungans(response.data.data);
        })
        .catch(error => {
          console.log(error.message);
        });
    }
  }, [dataUser, tanggal])


  return (
    <main className="page_main">
      <HeaderSales title="Riwayat Kunjungan" />

      <div className="page_container pt-4">
        <label>Tanggal Kunjungan</label>
        <div className="input-group">
          <input
            type='date'
            className="form-control"
            id="tanggalTrip"
            value={tanggal}
            onChange={(e) => setTanggal(e.target.value)}
          />
        </div>

        {dataKunjungans &&
          <Fragment>
            <h6 className="my-4">Jumlah Kunjungan : {dataKunjungans.length}</h6>

            <div className="table-responsive">
              <table className="table">
                <thead>
                  <tr>
                    <th scope="col" className='text-center'>Nama Toko</th>
                    <th scope="col" className='text-center'>Wilayah</th>
                    <th scope="col" className='text-center'>Jam Masuk</th>
                    <th scope="col" className='text-center'>Jam Keluar</th>
                    <th scope="col" className='text-center'>Effective Call</th>
                  </tr>
                </thead>
                <tbody>
                  {dataKunjungans.map((data) => (
                    <tr key={data.id}>
                      <td>{data.link_customer.nama ?? null}</td>
                      <td>{data.link_customer.link_district.nama ?? null}</td>
                      {data.waktu_masuk ? <td>{getTime(data.waktu_masuk)}</td> : <td></td>}
                      {data.waktu_keluar ? <td>{getTime(data.waktu_keluar)}</td> : <td></td>}
                      {data.status_enum ? <td className='text-center'>{data.status_enum == '2' ? 'YA' : 'TIDAK'}</td> : <td></td>}
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </Fragment>
        }
      </div>
    </main>
  );
}

export default HistoryTrip;
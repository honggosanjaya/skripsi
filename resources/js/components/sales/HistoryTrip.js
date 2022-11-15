import React, { useState, useContext, useEffect, Fragment } from 'react';
import axios from 'axios';
import HeaderSales from './HeaderSales';
import { UserContext } from '../../contexts/UserContext';
import { getTime } from "../reuse/HelperFunction";
import LoadingIndicator from '../reuse/LoadingIndicator';
import Modal from 'react-bootstrap/Modal';
import Button from 'react-bootstrap/Button';
import { convertDate } from "../reuse/HelperFunction";

const HistoryTrip = () => {
  const { dataUser } = useContext(UserContext);
  const [dataTarget, setDataTarget] = useState(null);
  var todayDate = new Date().toISOString().slice(0, 10);

  const [tanggal, setTanggal] = useState(todayDate);
  const [dataKunjungans, setDataKunjungans] = useState(null);
  const [dataEC, setDataEC] = useState([]);
  const [dataTargetKunjungan, setDataTargetKunjungan] = useState([]);
  const [dataTargetEC, setDataTargetEC] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const [showModal, setShowModal] = useState(false);
  const [detailTrip, setDetailTrip] = useState(null);

  useEffect(() => {
    setIsLoading(true);
    axios({
      method: "get",
      url: `${window.location.origin}/api/salesman/target`,
      headers: {
        Accept: "application/json",
      },
    })
      .then(response => {
        console.log('data target', response.data.data);
        setDataTarget(response.data.data);
        setIsLoading(false);
      })
      .catch(error => {
        console.log(error.message);
        setIsLoading(false);
      });
  }, [])

  useEffect(() => {
    if (dataKunjungans) {
      setDataEC(dataKunjungans.filter((item) => { return item.status_enum == '2' }));
    }
  }, [dataKunjungans])

  useEffect(() => {
    if (dataTarget) {
      setDataTargetKunjungan(dataTarget.filter((item) => { return item.jenis_target == '3' }));
      setDataTargetEC(dataTarget.filter((item) => { return item.jenis_target == '4' }));
    }
  }, [dataTarget])

  useEffect(() => {
    if (dataUser.id_staff) {
      setIsLoading(true);
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
          console.log('data kunjungan', response.data.data);
          setDataKunjungans(response.data.data);
          setIsLoading(false);
        })
        .catch(error => {
          console.log(error.message);
          setIsLoading(false);
        });
    }
  }, [dataUser, tanggal])

  const handleCloseModal = () => {
    setShowModal(false);
  }

  const handleClickTrip = (idTrip) => {
    setShowModal(true);
    const filteredTrip = dataKunjungans.filter(x =>
      x.id == idTrip
    );

    setDetailTrip(filteredTrip[0]);
  }

  return (
    <main className="page_main">
      <HeaderSales title="Riwayat Kunjungan" />
      {isLoading && <LoadingIndicator />}
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

        {dataKunjungans && dataTarget && dataTargetKunjungan[0] &&
          <h6 className='mt-4'>Jumlah Kunjungan : {dataKunjungans.length} / {dataTargetKunjungan[0].value}
            <span className='text-primary'>
              ({Math.round((dataKunjungans.length / dataTargetKunjungan[0].value * 100) * 10) / 10} % terpenuhi)
            </span>
          </h6>
        }

        {dataKunjungans && dataTargetKunjungan.length == 0 &&
          <h6 className='mt-4'>Jumlah Kunjungan : {dataKunjungans.length}</h6>
        }

        {dataKunjungans && dataTarget && dataTargetEC[0] &&
          <h6 className='mb-4'>Jumlah Effective Call : {dataEC.length} / {dataTargetEC[0].value}
            <span className='text-primary'>
              ({Math.round((dataEC.length / dataTargetEC[0].value * 100) * 10) / 10} % terpenuhi)
            </span>
          </h6>
        }

        {dataKunjungans && dataTargetEC.length == 0 &&
          <h6 className='mb-4'>Jumlah Effective Call : {dataEC.length}</h6>
        }

        {dataKunjungans && dataTarget &&
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
                  <tr key={data.id} onClick={() => handleClickTrip(data.id)}>
                    {data.link_customer ? <td>{data.link_customer.nama ?? null}</td> : <td></td>}
                    {data.link_customer ?
                      (data.link_customer.link_district && <td>{data.link_customer.link_district.nama ?? null}</td>)
                      : <td></td>}
                    {data.waktu_masuk ? <td>{getTime(data.waktu_masuk)}</td> : <td></td>}
                    {data.waktu_keluar ? <td>{getTime(data.waktu_keluar)}</td> : <td></td>}
                    {data.status_enum ? <td className='text-center'>{data.status_enum == '2' ? 'YA' : 'TIDAK'}</td> : <td></td>}
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        }

        {detailTrip && <Modal show={showModal} onHide={handleCloseModal}>
          <Modal.Header closeButton>
            <Modal.Title>Detail Trip</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <div className='info-2column'>
              <span className='d-flex'>
                <b>Customer</b>
                {detailTrip.link_customer ? <p className='mb-0 word_wrap'>{detailTrip.link_customer.nama ?? null}</p> : <p></p>}
              </span>
              <span className='d-flex'>
                <b>Waktu Masuk</b>
                {detailTrip.waktu_masuk && <p className='mb-0 word_wrap'>{convertDate(detailTrip.waktu_masuk)}</p>}
              </span>
              <span className='d-flex'>
                <b>Waktu Keluar</b>
                {detailTrip.waktu_keluar && <p className='mb-0 word_wrap'>{convertDate(detailTrip.waktu_keluar)}</p>}
              </span>
              <span className='d-flex'>
                <b>Status</b>
                {detailTrip.status_enum && <p className='mb-0 word_wrap'>{detailTrip.status_enum == '1' ? 'Tidak Effective Call' : (detailTrip.status_enum == '2' && 'Effective Call')}</p>}
              </span>
              {detailTrip.alasan_penolakan &&
                <span className='d-flex'>
                  <b>Alasan Penolakan</b>
                  <p className='mb-0 word_wrap'>{detailTrip.alasan_penolakan}</p>
                </span>}
            </div>
          </Modal.Body>
          <Modal.Footer>
            <Button variant="danger" onClick={handleCloseModal}><span className="iconify fs-3 me-1" data-icon="carbon:close-outline"></span>Tutup</Button>
          </Modal.Footer>
        </Modal>}
      </div>
    </main>
  );
}

export default HistoryTrip;
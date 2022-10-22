import React, { useState, useContext, useEffect, Fragment } from 'react';
import HeaderSales from './HeaderSales';
import { UserContext } from '../../contexts/UserContext';
import LoadingIndicator from '../reuse/LoadingIndicator';
import Modal from 'react-bootstrap/Modal';
import Button from 'react-bootstrap/Button';

const HistoryKanvas = () => {
  const { dataUser } = useContext(UserContext);
  const [listKanvas, setListKanvas] = useState([]);
  const [listItemKanvas, setListItemKanvas] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const [show, setShow] = useState(false);

  useEffect(() => {
    if (dataUser) {
      setIsLoading(true);
      axios({
        method: "get",
        url: `${window.location.origin}/api/salesman/itemkanvas/${dataUser.id_staff}`,
        headers: {
          Accept: "application/json",
        },
      })
        .then(response => {
          setListKanvas(response.data.data);
          setIsLoading(false);
        })
        .catch(error => {
          console.log(error.message);
          setIsLoading(false);
        });
    }
  }, [dataUser])

  const handleClose = () => setShow(false);

  const handleShow = (idsKanvas) => {
    const ids = idsKanvas.replace(/,/g, '-');
    setIsLoading(true);
    axios({
      method: "get",
      url: `${window.location.origin}/api/administrasi/getDetailKanvas/${ids}`,
      headers: {
        Accept: "application/json"
      },
    }).then(response => {
      setListItemKanvas(response.data.data);
      setIsLoading(false);
    })
    setShow(true);
  };

  return (
    <main className="page_main">
      <HeaderSales title="History Kanvas" />
      {isLoading && <LoadingIndicator />}
      <div className="page_container pt-4">
        <div className="table-responsive">
          <table className="table mt-3">
            <thead>
              <tr>
                <th scope="col" className='text-center'>No</th>
                <th scope="col" className='text-center'>Nama Kanvas</th>
                <th scope="col" className='text-center'>Waktu Dibawa</th>
                <th scope="col" className='text-center'>Waktu Dikembalikan</th>
                <th scope="col" className='text-center'>Status</th>
              </tr>
            </thead>
            <tbody>
              {listKanvas.map((kanvas, index) => (
                <tr key={index}>
                  <td>{index + 1}</td>
                  <td>
                    <p className="mb-0 text-primary" onClick={() => handleShow(kanvas.ids)}>
                      {kanvas.nama ?? null}
                    </p>
                  </td>
                  <td>{kanvas.waktu_dibawa ?? null}</td>
                  <td>{kanvas.waktu_dikembalikan ?? null}</td>
                  {kanvas.waktu_dikembalikan == null ? <td>Belum dikembalikan</td> : <td>Sudah dikembalikan</td>}
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        <Modal show={show} onHide={handleClose}>
          <Modal.Header closeButton>
            <Modal.Title>Detail Kanvas</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <div className='info-2column'>
              {listItemKanvas.length > 0 &&
                <Fragment>
                  <span className='d-flex'>
                    <b>Nama</b>
                    <p className='mb-0 word_wrap'>{listItemKanvas[0][0].nama ?? null}</p>
                  </span>

                  <span className='d-flex'>
                    <b>P. pembawaan</b>
                    <p className='mb-0 word_wrap'>{listItemKanvas[0][0].link_staff_pengonfirmasi_pembawaan.nama ?? null}</p>
                  </span>

                  {listItemKanvas[0][0].link_staff_pengonfirmasi_pengembalian != null && <span className='d-flex'>
                    <b>P. pengembalian</b>
                    <p className='mb-0 word_wrap'>{listItemKanvas[0][0].link_staff_pengonfirmasi_pengembalian.nama ?? null}</p>
                  </span>}
                </Fragment>
              }

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
                    {listItemKanvas.map((item, index) => (
                      <tr key={index}>
                        <td className='text-center'>{index + 1}</td>
                        <td className='text-center'>{item[0].link_item.nama}</td>
                        <td className='text-center'>{item[0].stok_awal}</td>
                        <td className='text-center'>{item[0].sisa_stok}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </Modal.Body>
          <Modal.Footer>
            <Button variant="danger" onClick={handleClose}><span className="iconify fs-3 me-1" data-icon="carbon:close-outline"></span>Tutup</Button>
          </Modal.Footer>
        </Modal>

      </div>
    </main>
  );
}

export default HistoryKanvas;
import React, { Fragment, useContext, useEffect, useState } from 'react';
import { UserContext } from '../../contexts/UserContext';
import { convertPrice } from "../reuse/HelperFunction";
import useInfinite from '../reuse/useInfinite';
import InfiniteScroll from 'react-infinite-scroll-component';
import Modal from 'react-bootstrap/Modal';
import Button from 'react-bootstrap/Button';

const HistoryPembelian = ({ idCust }) => {
  const { dataUser } = useContext(UserContext);
  const { page, setPage, erorFromInfinite, paginatedData, isReachedEnd } = useInfinite(`api/historyPembelian/${idCust}`, 10);
  const [detailHistoryBeli, setDetailHistoryBeli] = useState(null);
  const [showModalHistoryBeli, setShowModalHistoryBeli] = useState(false);

  const getDate = (date) => {
    const newDate = date.substring(0, 10);
    const results = newDate.split('-');
    return `${results[2]}-${results[1]}-${results[0]}`;
  }

  const handleClickDetailRencana = (idInvoice) => {
    setShowModalHistoryBeli(true);
    const result = paginatedData.find(obj => {
      return obj.id === idInvoice
    })
    setDetailHistoryBeli(result);
  }

  const handleCloseDetailModalKunjungan = () => {
    setShowModalHistoryBeli(false);
  }

  return (
    <div className="history_pembelian mt-4">
      <p className='mb-0 fw-bold'>History Pembelian</p>
      {erorFromInfinite && <p className="text-danger">something is wrong</p>}

      <InfiniteScroll
        dataLength={paginatedData?.length ?? 0}
        next={() => setPage(page + 1)}
        hasMore={!isReachedEnd}
        loader={<p>Loading...</p>}
        endMessage={<p className="text-center mt-3">No more data</p>}>
        {paginatedData && paginatedData.map((dt) => (
          <div className="d-flex py-2 align-items-center justify-content-between border-bottom" key={dt.id} onClick={() => handleClickDetailRencana(dt.id)}>
            <h6>{getDate(dt.created_at)}</h6>
            <h6>{convertPrice(dt.harga_total)}</h6>
          </div>
        ))
        }
      </InfiniteScroll>

      {detailHistoryBeli && <Modal show={showModalHistoryBeli} onHide={handleCloseDetailModalKunjungan} centered={true}>
        <Modal.Header closeButton>
          <Modal.Title>Detail Riwayat Pembelian</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <div className='info-2column'>
            <span className='d-flex'>
              <b>No Invoice</b>
              <p className='mb-0 word_wrap'>{detailHistoryBeli.nomor_invoice ?? null}</p>
            </span>
          </div>
          <table className="table">
            <thead>
              <tr>
                <th scope="col" className='text-center'>Nama Item</th>
                <th scope="col" className='text-center'>Jumlah</th>
              </tr>
            </thead>
            <tbody>
              {detailHistoryBeli.link_order.link_order_item.map((data, index) => (
                <tr key={index}>
                  <td>{data.link_item.nama ?? null}</td>
                  <td className='text-center'>{data.kuantitas ?? null}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </Modal.Body>
        <Modal.Footer>
          <Button className='btn btn-danger' variant="danger" onClick={handleCloseDetailModalKunjungan}>
            <span className="iconify fs-3 me-1" data-icon="carbon:close-outline"></span>Tutup
          </Button>
        </Modal.Footer>
      </Modal>}
    </div>
  );
}

export default HistoryPembelian;
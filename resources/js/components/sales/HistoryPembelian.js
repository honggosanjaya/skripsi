import React from 'react';
import { convertPrice } from "../reuse/HelperFunction";
import useInfinite from '../reuse/useInfinite';
import InfiniteScroll from 'react-infinite-scroll-component';

const HistoryPembelian = ({ idCust, setShowModalHistoryBeli, setDetailHistoryBeli, setShowDetailModalKunjungan, setShowDetailModalTagihan }) => {
  const { page, setPage, erorFromInfinite, paginatedData, isReachedEnd } = useInfinite(`api/historyPembelian/${idCust}`, 10);

  const getDate = (date) => {
    const newDate = date.substring(0, 10);
    const results = newDate.split('-');
    return `${results[2]}-${results[1]}-${results[0]}`;
  }

  const handleClickDetailRencana = (idInvoice) => {
    setShowModalHistoryBeli(true);
    setShowDetailModalKunjungan(false);
    setShowDetailModalTagihan(false);
    const result = paginatedData.find(obj => {
      return obj.id === idInvoice
    })
    setDetailHistoryBeli(result);
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
            <h6 className='mb-0'>{getDate(dt.created_at)}</h6>
            <h6 className='mb-0'>{convertPrice(dt.harga_total)}</h6>
          </div>
        ))
        }
      </InfiniteScroll>
    </div>
  );
}

export default HistoryPembelian;
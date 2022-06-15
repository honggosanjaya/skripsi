import React, { Component, Fragment } from 'react';
import { convertDate } from "../reuse/HelperFunction";

const ListShipping = ({ listShipping, statusShipping, handleShow, keyword, setKeyword, cariShipping }) => {
  return (
    <Fragment>
      <form onSubmit={cariShipping}>
        <div className="input-group mb-3">
          <input type="text" className="form-control"
            value={keyword || ''} onChange={(e) => setKeyword(e.target.value)}
          />
          <button type="submit" className="btn btn-primary"><span className="iconify me-2" data-icon="fe:search"></span>Cari</button>
        </div>
      </form>


      {listShipping.length > 0 && <div className='pengiriman_wrapper'>
        {listShipping.map((data, index) => (
          <div className={`list_pengiriman px-2 ${data.link_order_track.status != statusShipping ? ((statusShipping == 23 && data.link_order_track.status == 24) ? "d-block" : "d-none") : "d-block"}`} key={`jadwal${index}`}>
            <div className='info-shipping'>
              <span><b>No. Invoice</b>{data.link_invoice.nomor_invoice}</span>
              <span><b>Cutomer</b>{data.link_customer.nama}</span>
              {data.link_customer.telepon &&
                <Fragment>
                  <span><b>Telepon</b>{data.link_customer.telepon}</span>
                </Fragment>}
              <span><b>Alamat</b>{data.link_customer.full_alamat}</span>
              <span className='d-flex'>
                <b>Jam Berangkat</b>
                <div>{convertDate(data.link_order_track.waktu_berangkat)}
                </div>
              </span>
              {(data.link_order_track.waktu_sampai)
                ?
                <span className='d-flex'>
                  <b>Jam Sampai</b>
                  <div>{convertDate(data.link_order_track.waktu_sampai)}
                  </div>
                </span> : ""}
            </div>
            <p className='mb-0 detail-pengiriman_link' onClick={() => handleShow(data.id)}>Lihat detail</p>
          </div>
        ))}
      </div>}
    </Fragment>
  );
}

export default ListShipping;
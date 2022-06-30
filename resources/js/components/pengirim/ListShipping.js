import React, { Component, Fragment } from 'react';
import { convertDate } from "../reuse/HelperFunction";

const ListShipping = ({ listShipping, statusShipping, handleShow, keyword, setKeyword, cariShipping }) => {
  return (
    <Fragment>
      <form onSubmit={cariShipping}>
        <div className="input-group mb-3">
          <input type="text" className="form-control"
            value={keyword || ''} onChange={(e) => setKeyword(e.target.value)}
            placeholder="Cari nama customer"
          />
          <button type="submit" className="btn btn-primary"><span className="iconify" data-icon="fe:search"></span></button>
        </div>
      </form>


      {listShipping.length > 0 && <div className='pengiriman_wrapper'>
        {listShipping.map((data, index) => (
          <div className={`list_pengiriman px-2 ${data.link_order_track.status != statusShipping ? ((statusShipping == 23 && data.link_order_track.status == 24) ? "d-block" : "d-none") : "d-block"}`} key={`jadwal${index}`}>
            <div className='info-shipping'>

              <span className='d-flex'>
                <b>No. Invoice</b>
                <div>{data.link_invoice.nomor_invoice}
                </div>
              </span>

              <span className='d-flex'>
                <b>Cutomer</b>
                <div>{data.link_customer.nama}
                </div>
              </span>

              {data.link_customer.telepon &&
                <Fragment>
                  <span className='d-flex'>
                    <b>Telepon</b>
                    <div>{data.link_customer.telepon}
                    </div>
                  </span>
                </Fragment>}

              <span className='d-flex'>
                <b>Alamat</b>
                <div>{data.link_customer.full_alamat}
                </div>
              </span>

              <span className='d-flex'>
                <b>Waktu Berangkat</b>
                <div>{convertDate(data.link_order_track.waktu_berangkat)}
                </div>
              </span>
              {(data.link_order_track.waktu_sampai)
                ?
                <span className='d-flex'>
                  <b>Waktu Sampai</b>
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
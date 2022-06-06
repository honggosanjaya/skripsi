import React, { Component, Fragment } from 'react';
import { convertDate } from "../reuse/HelperFunction";

const ListShipping = ({ listShipping, statusShipping, handleShow }) => {
  return (
    <Fragment>
      {listShipping.length > 0 && <div className='pengiriman_wrapper'>
        {listShipping.map((data, index) => (
          <div className={`list_pengiriman px-2 ${data.link_order_track.status != statusShipping ? "d-none" : "d-block"}`} key={`jadwal${index}`}>
            <div className='info-shipping'>
              <span><b>No. Invoice</b>{data.link_invoice.nomor_invoice}</span>
              <span><b>Cutomer</b>{data.link_customer.nama}</span>
              {data.link_customer.telepon &&
                <Fragment>
                  <span><b>Telepon</b>{data.link_customer.telepon}</span>
                </Fragment>}
              <span><b>Alamat</b>{data.link_customer.full_alamat}</span>
              <span><b>Jam Berangkat</b></span><br />{convertDate(data.link_order_track.waktu_berangkat)}
            </div>
            <p className='mb-0 detail-pengiriman_link' onClick={() => handleShow(data.id)}>Lihat detail</p>
          </div>
        ))}
      </div>}
    </Fragment>
  );
}

export default ListShipping;
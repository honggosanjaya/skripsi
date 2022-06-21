import React, { Component, Fragment } from 'react';
import Modal from 'react-bootstrap/Modal';
import Button from 'react-bootstrap/Button';
import { convertDate } from "../reuse/HelperFunction";
import urlAsset from '../../config';

const DetailShipping = ({ detailShipping, isLoading, show, handleClose, handlePengirimanSampai, handlePengajuanRetur, listDetailItem }) => {
  return (
    <Fragment>
      {detailShipping && !isLoading &&
        <Modal show={show} onHide={handleClose}>
          <Modal.Header closeButton>
            <Modal.Title>Detail Pengiriman</Modal.Title>
          </Modal.Header>
          <Modal.Body>

            <div className='info-shipping'>
              <span><b>No. Invoice</b>{detailShipping.link_invoice.nomor_invoice}</span>
              <span><b>Customer</b>{detailShipping.link_customer.nama}</span>
              <span><b>Telepon</b>{detailShipping.link_customer.telepon}</span>
              <span><b>Alamat</b>
                {detailShipping.link_customer.koordinat && <a target="_blank" href={`https://www.google.com/maps/search/?api=1&query=${detailShipping.link_customer.koordinat.replace("@", ",")}`}>{detailShipping.link_customer.full_alamat}</a>}
              </span>
              <span><b>Keterangan Alamat</b>{detailShipping.link_customer.keterangan_alamat}</span>
              <span className='d-flex'><b>Jam Berangkat </b> <div>{convertDate(detailShipping.link_order_track.waktu_berangkat)}</div></span>
              <span><b>Total Pembayaran</b>{detailShipping.link_invoice.harga_total}</span>
              {(detailShipping.link_customer.foto)
                ? <img src={`${urlAsset}/storage/customer/${detailShipping.link_customer.foto}`} className="mt-2 img-fluid d-block mx-auto" />
                : ''}
              <table className="table mt-3">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Satuan</th>
                  </tr>
                </thead>
                <tbody>
                  {listDetailItem.map((data, index) => (
                    <tr key={`item${index}`}>
                      <th scope="row">{index + 1}</th>
                      {data.link_item && <td>{data.link_item.nama}</td>}
                      <td>{data.kuantitas}</td>
                      {data.link_item && <td>{data.link_item.satuan}</td>}
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </Modal.Body>
          <Modal.Footer>
            <Button variant="danger" onClick={handleClose}><span className="iconify fs-3 me-1" data-icon="carbon:close-outline"></span>Tutup</Button>
            {detailShipping.link_order_track.status == 22 &&
              <Button variant="success" onClick={handlePengirimanSampai}><span className="iconify fs-3 me-1" data-icon="material-symbols:download-done"></span>Pengiriman Sampai</Button>}
            {(detailShipping.link_invoice.link_retur.length == 0 && (detailShipping.link_order_track.status == 23 || detailShipping.link_order_track.status == 24)) &&
              <Button variant="warning" onClick={() => handlePengajuanRetur(detailShipping.id_customer)}>Ajukan Retur</Button>}
          </Modal.Footer>
        </Modal>}
    </Fragment>
  );
}

export default DetailShipping;
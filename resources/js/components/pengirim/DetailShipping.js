import React, { Component, Fragment } from 'react';
import Modal from 'react-bootstrap/Modal';
import Button from 'react-bootstrap/Button';
import { convertDate } from "../reuse/HelperFunction";

const DetailShipping = ({ detailShipping, isLoading, show, handleClose, handlePengirimanSampai, handlePengajuanRetur, listDetailItem }) => {
  return (
    <Fragment>
      {detailShipping && !isLoading &&
        <Modal show={show} onHide={handleClose}>
          <Modal.Header closeButton>
            <Modal.Title>Nomor invoice: {detailShipping.link_invoice.nomor_invoice}</Modal.Title>
          </Modal.Header>
          <Modal.Body>

            <div className='info-shipping'>
              <span><b>No. Invoice</b>{detailShipping.link_invoice.nomor_invoice}</span>
              <span><b>Customer</b>{detailShipping.link_customer.nama}</span>
              <span><b>Telepon</b>{detailShipping.link_customer.telepon}</span>
              <span><b>Alamat</b>
                <a target="_blank" href={`https://www.google.com/maps/search/?api=1&query=${detailShipping.link_customer.koordinat.replace("@", ",")}`}>{detailShipping.link_customer.full_alamat}</a>
              </span>
              <span><b>Keterangan Alamat</b>{detailShipping.link_customer.keterangan_alamat}</span>
              <span><b>Jam Berangkat </b></span><br />{convertDate(detailShipping.link_order_track.waktu_berangkat)}<br />
              <span><b>Total Pembayaran</b>{detailShipping.link_invoice.harga_total}</span>
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
                      <td>{data.link_item.nama}</td>
                      <td>{data.kuantitas}</td>
                      <td>{data.link_item.satuan}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </Modal.Body>
          <Modal.Footer>
            <Button variant="danger" onClick={handleClose}>Close</Button>
            {detailShipping.link_order_track.status == 22 &&
              <Button variant="success" onClick={handlePengirimanSampai}>Pengiriman Sampai</Button>}
            {detailShipping.link_order_track.status == 23 &&
              <Button variant="warning" onClick={() => handlePengajuanRetur(detailShipping.id_customer)}>Ajukan Retur</Button>}
          </Modal.Footer>
        </Modal>}
    </Fragment>
  );
}

export default DetailShipping;
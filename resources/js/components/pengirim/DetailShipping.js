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
            <div className='info-2column'>
              <span className='d-flex'>
                <b>No. Invoice</b>
                <p className='mb-0 word_wrap'>{detailShipping.link_invoice.nomor_invoice ?? null}</p>
              </span>

              <span className='d-flex'>
                <b>Customer</b>
                <p className='mb-0 word_wrap'>{detailShipping.link_customer.nama ?? null}</p>
              </span>

              <span className='d-flex'>
                <b>Telepon</b>
                <p className='mb-0 word_wrap'>{detailShipping.link_customer.telepon ?? null}</p>
              </span>

              <span className='d-flex'>
                <b>Alamat</b>
                {detailShipping.link_customer.koordinat && <a target="_blank" href={`https://www.google.com/maps/search/?api=1&query=${detailShipping.link_customer.koordinat.replace("@", ",")}`}><p className='mb-0 word_wrap'>{detailShipping.link_customer.full_alamat}</p></a>}
                {detailShipping.link_customer.koordinat == null && <p className='mb-0 word_wrap'>{detailShipping.link_customer.full_alamat}</p>}
              </span>

              <span className='d-flex'>
                <b>Keterangan Alamat</b>
                <p className='mb-0 word_wrap'>{detailShipping.link_customer.keterangan_alamat ?? null}</p>
              </span>

              {detailShipping.link_order_track.waktu_berangkat != null &&
                <span className='d-flex'><b>Waktu Berangkat </b> <div>{convertDate(detailShipping.link_order_track.waktu_berangkat)}</div></span>}

              <span className='d-flex'>
                <b>Total Pembayaran</b>
                <p className='mb-0 word_wrap'>{detailShipping.link_invoice.harga_total ?? null}</p>
              </span>

              {(detailShipping.link_customer.foto)
                ? <img src={`${urlAsset}/storage/customer/${detailShipping.link_customer.foto}`} className="mt-2 img-fluid d-block mx-auto" />
                : ''}
              <table className="table mt-3">
                <thead>
                  <tr>
                    <th scope="col" className='text-center'>No</th>
                    <th scope="col" className='text-center'>Nama Barang</th>
                    <th scope="col" className='text-center'>Kuantitas</th>
                    <th scope="col" className='text-center'>Satuan</th>
                  </tr>
                </thead>
                <tbody>
                  {listDetailItem.map((data, index) => (
                    <tr key={`item${index}`}>
                      <th scope="row" className='text-center'>{index + 1}</th>
                      {data.link_item && <td>{data.link_item.nama ?? null}</td>}
                      <td className='text-center'>{data.kuantitas ?? null}</td>
                      {data.link_item && <td className='text-center'>{data.link_item.satuan ?? null}</td>}
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </Modal.Body>
          <Modal.Footer>
            <Button variant="danger" onClick={handleClose}><span className="iconify fs-3 me-1" data-icon="carbon:close-outline"></span>Tutup</Button>
            {detailShipping.link_order_track.status_enum == '3' &&
              <Button variant="success" onClick={handlePengirimanSampai}><span className="iconify fs-3 me-1" data-icon="material-symbols:download-done"></span>Pengiriman Sampai</Button>}
            {(detailShipping.link_invoice.link_retur.length == 0 && detailShipping.link_order_track.status_enum == '4') &&
              <Button variant="warning" onClick={() => handlePengajuanRetur(detailShipping.id_customer)}>
                <span className="iconify fs-3 me-1" data-icon="ic:baseline-assignment-return"></span>Ajukan Retur
              </Button>}
          </Modal.Footer>
        </Modal>}
    </Fragment>
  );
}

export default DetailShipping;
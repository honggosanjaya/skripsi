import React, { Component, useState } from 'react';
import { Accordion } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import urlAsset from '../../config';

const ListCustomer = ({ listCustomer, isOrder }) => {
  const [isShowFoto, setIsShowFoto] = useState(false);
  const handleToggleFoto = () => {
    setIsShowFoto(!isShowFoto);
  }

  return (
    <Accordion defaultActiveKey="0">
      {listCustomer && listCustomer.map(customer => (
        <Accordion.Item eventKey={customer.id} key={customer.id}>
          <Accordion.Header>
            <div className="row">
              <div className="col-5">
                <span>{customer.nama} | {customer.link_customer_type && customer.link_customer_type.nama}</span>
              </div>
              <div className="col-4">
                <span>{customer.full_alamat}</span>
              </div>
              <div className="col-3">
                <span>{customer.link_district.nama}</span>
              </div>
            </div>
          </Accordion.Header>
          <Accordion.Body>
            <p className='mb-2 fw-bold'> Keterangan alamat</p>
            <p className="mb-2 fs-7">{customer.keterangan_alamat}</p>
            <div className="action d-flex justify-content-between mt-3">
              <button type="button" className="btn btn-primary" onClick={handleToggleFoto}>
                <span className="iconify me-1" data-icon="ant-design:picture-outlined"></span>
                {isShowFoto ? 'Tutup Foto' : 'Lihat Foto'}
              </button>
              {isOrder ?
                <Link to={`/salesman/order/${customer.id}`} className="btn btn-success">
                  <span className="iconify me-1" data-icon="carbon:ibm-watson-orders"></span>Order
                </Link> :
                <Link to={`/salesman/trip/${customer.id}`} className="btn btn-success">
                  <span className="iconify me-1" data-icon="bx:trip"></span>Trip
                </Link>}
            </div>

            {(isShowFoto && customer.foto)
              ? <img src={`${urlAsset}/storage/customer/${customer.foto}`} className="mt-2 img-fluid d-block mx-auto" />
              : (isShowFoto && !customer.foto) ? <span className='mt-2 d-block text-center'>Tidak ada foto</span> : ''}
          </Accordion.Body>
        </Accordion.Item>
      ))}
    </Accordion>
  );
}

export default ListCustomer;
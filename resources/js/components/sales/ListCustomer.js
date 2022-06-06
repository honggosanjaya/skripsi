import React, { Component } from 'react';
import { Accordion } from 'react-bootstrap';
import { Link } from 'react-router-dom';

const ListCustomer = ({ listCustomer, isOrder }) => {
  return (
    <Accordion defaultActiveKey="0">
      {listCustomer.map(data => (
        <Accordion.Item eventKey={data.id} key={data.id}>
          <Accordion.Header>
            <div className="container-fluid">
              <div className="row">
                <div className="col-5">
                  {data.nama}
                </div>
                <div className="col-4">
                  {data.full_alamat}
                </div>
                <div className="col-3">
                  {data.id_wilayah}
                </div>
              </div>
            </div>
          </Accordion.Header>
          <Accordion.Body>
            <h5> Keterangan alamat</h5>
            {data.keterangan_alamat}
            <div className="action d-flex justify-content-between mt-3">
              <button type="button" className="btn btn-primary">
                <span className="iconify me-1" data-icon="ant-design:picture-outlined"></span>Lihat Foto
              </button>
              {isOrder ?
                <Link to={`/salesman/order/${data.id}`} className="btn btn-success">
                  <span className="iconify me-1" data-icon="carbon:ibm-watson-orders"></span>Order
                </Link> :
                <Link to={`/salesman/trip/${data.id}`} className="btn btn-success">
                  <span className="iconify me-1" data-icon="bx:trip"></span>Trip
                </Link>}
            </div>
          </Accordion.Body>
        </Accordion.Item>
      ))}
    </Accordion>
  );
}

export default ListCustomer;
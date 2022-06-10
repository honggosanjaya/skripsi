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
      {listCustomer.map(data => (
        <Accordion.Item eventKey={data.id} key={data.id}>
          <Accordion.Header>
            <div className="container-fluid">
              <div className="row">
                <div className="col-5">
                  {data.nama} | {data.link_customer_type.nama}
                </div>
                <div className="col-4">
                  {data.full_alamat}
                </div>
                <div className="col-3">
                  {data.link_district.nama}
                </div>
              </div>
            </div>
          </Accordion.Header>
          <Accordion.Body>
            <h5> Keterangan alamat</h5>
            {data.keterangan_alamat}
            <div className="action d-flex justify-content-between mt-3">
              <button type="button" className="btn btn-primary" onClick={handleToggleFoto}>
                <span className="iconify me-1" data-icon="ant-design:picture-outlined"></span>
                {isShowFoto ? 'Tutup Foto' : 'Lihat Foto'}
              </button>
              {isOrder ?
                <Link to={`/salesman/order/${data.id}`} className="btn btn-success">
                  <span className="iconify me-1" data-icon="carbon:ibm-watson-orders"></span>Order
                </Link> :
                <Link to={`/salesman/trip/${data.id}`} className="btn btn-success">
                  <span className="iconify me-1" data-icon="bx:trip"></span>Trip
                </Link>}
            </div>

            {(isShowFoto && data.foto)
              ? <img src={`${urlAsset}/storage/customer/${data.foto}`} className="mt-2 img-fluid d-block mx-auto" />
              : (isShowFoto && !data.foto) ? <span className='mt-2 d-block text-center'>Tidak ada foto</span> : ''}
          </Accordion.Body>
        </Accordion.Item>
      ))}
    </Accordion>
  );
}

export default ListCustomer;
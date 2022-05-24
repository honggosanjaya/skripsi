import React, { Fragment, Component, useState, useEffect, useContext } from 'react';
import { splitCharacter } from '../reuse/HelperFunction';
import HeaderSales from './HeaderSales';
import { Accordion } from 'react-bootstrap';
import { AuthContext } from '../../contexts/AuthContext';
import { UserContext } from '../../contexts/UserContext';

const DashboardSales = () => {
  // const { token, isAuth } = useContext(AuthContext);
  // const { dataUser } = useContext(UserContext);
  const [namaCust, setNamaCust] = useState('');
  const [alamatUtama, setAlamatUtama] = useState('');
  const [listCustomer, setListCustomer] = useState([]);
  const [addButton, setAddButton] = useState('');
  const [dataShow, setDataShow] = useState('inactive');

  // useEffect(() => {
  //   console.log(isAuth);
  //   console.log(token);
  //   console.log('tf', isAuth === 'true');
  //   console.log('tf', token !== null)
  //   if (isAuth === 'true' && token !== null) {
  //     console.log('perlihatkan')
  //   } else {
  //     console.log('janganperlihatkan')
  //   }
  // }, [token, isAuth])

  const cariCustomer = (e) => {
    e.preventDefault();
    axios({
      method: "post",
      url: `${window.location.origin}/api/cariCustomer`,
      headers: {
        Accept: "application/json",
      },
      data: {
        nama: namaCust,
        alamat_utama: alamatUtama,
      }
    })
      .then(response => {
        // console.log(response.data.data);
        setListCustomer(response.data.data)
        setAddButton('active')
        setDataShow('inactive')
        return response.data.data;
      })
      .catch(error => {
        setDataShow('active')
        setAddButton('active')
        console.log(error.message);
      });
  }

  const showListCustomer = listCustomer.map((data, index) => {
    return (
      <Accordion.Item eventKey={data.id} key={index}>
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
            <button type="button" className="btn btn-primary">gambar</button>
            <a type="button" href={`/salesman/trip/${data.id}`} className="btn btn-success">trip</a>
          </div>
        </Accordion.Body>
      </Accordion.Item>
    );
  });

  return (
    <main className="page_main">
      {/* {isAuth === 'true' && token !== null ? */}
      <Fragment>
        <HeaderSales isDashboard={true} />
        <div className="page_container pt-4">
          <div className="word d-flex justify-content-center">
            {splitCharacter("salesman")}
          </div>
          <button className='btn btn-primary w-100' data-bs-toggle="modal" data-bs-target="#cariDataCustomer">Trip</button>
          <button className='btn btn-success w-100 mt-4' data-bs-toggle="modal" data-bs-target="#cariDataCustomer">Order</button>
        </div>

        <div className="modal fade modal_cariCust" id="cariDataCustomer" tabIndex="-1" aria-labelledby="cariDataCustomerLabel" aria-hidden="true">
          <div className="modal-dialog">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title" id="exampleModalLabel">Cari Customer</h5>
                <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div className="modal-body">
                <form onSubmit={cariCustomer}>
                  <div className="mb-3">
                    <label className="form-label">Nama Customer</label>
                    <input type="text" value={namaCust || ''} onChange={(e) => setNamaCust(e.target.value)} className="form-control" />
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Alamat Utama</label>
                    <input type="text" value={alamatUtama || ''} onChange={(e) => setAlamatUtama(e.target.value)} className="form-control" />
                  </div>
                  <button type="submit" className="btn btn-primary">Search</button>
                </form>
                <div className="box-list-customer mt-5">
                  <h1 className={`d-block text-center ${dataShow == 'active' ? '' : 'd-none'}`}>
                    Data Not Found
                  </h1>
                  <Accordion defaultActiveKey="0">
                    {showListCustomer}
                  </Accordion>
                </div>
                <a type="button" href="/salesman/trip/" className={`btn btn-primary d-block ${addButton == 'active' ? '' : 'd-none'}`}>masih belum menemukan silahkan tambah baru</a>
              </div>
            </div>
          </div>
        </div>
      </Fragment>
      {/* : ''} */}
    </main>
  );
}

export default DashboardSales;
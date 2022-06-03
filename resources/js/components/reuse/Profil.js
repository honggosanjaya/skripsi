import React, { Component, Fragment, useContext } from 'react';
import { UserContext } from '../../contexts/UserContext';
import urlAsset from '../../config';
import HeaderSales from '../sales/HeaderSales';
import LoadingIndicator from './LoadingIndicator';
import { Link, useHistory } from "react-router-dom";
import HeaderShipper from '../pengirim/HeaderShipper';

const Profil = () => {
  const { dataUser, loadingDataUser } = useContext(UserContext);
  const history = useHistory();

  const toBack = () => {
    if (dataUser.role == 'salesman') {
      history.push('/salesman');
    } else if (dataUser.role == 'shipper') {
      history.push('/shipper');
    }
  }

  return (
    <main className="page_main">
      {dataUser.role == 'salesman' && <HeaderSales title="Profil Saya" toBack={toBack} />}
      {dataUser.role == 'shipper' && <HeaderShipper title="Profil Saya" toBack={toBack} />}
      {loadingDataUser && <LoadingIndicator />}
      <div className="page_container pt-4">
        {!loadingDataUser &&
          <Fragment>
            <ul className="info-list">
              <li><b>Nama</b>{dataUser.nama}</li>
              <li><b>Email</b>{dataUser.email}</li>
              <li><b>Telepon</b>{dataUser.telepon}</li>
              <li><b>Role</b>{dataUser.role}</li>
              <li><b>Status</b>{dataUser.status}</li>
              <li><b>Foto Profil</b>{!dataUser.foto_profil && 'Belum ada foto profil'}</li>
              {dataUser.foto_profil &&
                <img src={`${urlAsset}/storage/staff/${dataUser.foto_profil}`} className="img-fluid" />
              }
            </ul>
            <Link to='/changepassword' className="btn btn-primary w-100">Ubah Password</Link>
          </Fragment>
        }
      </div>
    </main>
  );
}

export default Profil;
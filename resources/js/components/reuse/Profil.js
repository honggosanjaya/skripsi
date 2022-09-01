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

        {dataUser.foto_profil &&
          <img src={`${urlAsset}/storage/staff/${dataUser.foto_profil}`} className="profil-saya_foto" />}

        {!loadingDataUser &&
          <Fragment>
            <ul className="info-list mt-4">
              <li><b>Nama</b>{dataUser.nama ?? null}</li>
              <li><b>Email</b>{dataUser.email ?? null}</li>
              <li><b>Telepon</b>{dataUser.telepon ?? null}</li>
              <li><b>Role</b>{dataUser.role ?? null}</li>
              {dataUser.status_enum && <li><b>Status</b>{dataUser.status_enum == '1' ? "Aktif" : "Tidak Aktif"}</li>}
            </ul>
            <Link to='/changepassword' className="btn btn-outline-primary w-100 mt-4">Ubah Password</Link>
          </Fragment>
        }
      </div>
    </main>
  );
}

export default Profil;
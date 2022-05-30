import React, { Component, useContext } from 'react';
import { UserContext } from '../../contexts/UserContext';
import urlAsset from '../../config';
import HeaderSales from '../sales/HeaderSales';
import LoadingIndicator from './LoadingIndicator';

const Profil = () => {
  const { dataUser, loadingDataUser } = useContext(UserContext);
  return (
    <main className="page_main">
      <HeaderSales title="Salesman" />
      {loadingDataUser && <LoadingIndicator />}
      <div className="page_container pt-4">
        {!loadingDataUser &&
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
          </ul>}
      </div>
    </main>
  );
}

export default Profil;
import React, { Fragment, useContext, useEffect, useState } from 'react';
import { useHistory } from "react-router-dom";
import urlAsset from '../../config';
import { Dropdown } from 'react-bootstrap';
import { AuthContext } from '../../contexts/AuthContext';
import { UserContext } from '../../contexts/UserContext';
import LoadingIndicator from '../reuse/LoadingIndicator';

const HeaderSales = ({ title, isDashboard, isOrder, lihatKeranjang, toBack, jumlahProdukKeranjang }) => {
  const { isLoadingAuth, handleLogout } = useContext(AuthContext);
  const history = useHistory();
  const { dataUser } = useContext(UserContext);

  const goback = () => {
    history.go(-1);
  }

  const handleViewProfile = () => {
    history.push('/salesman/profil');
  }

  return (
    <header className='header_mobile d-flex justify-content-between align-items-center'>
      {!isDashboard &&
        <Fragment>
          <div className='d-flex align-items-center'>
            {toBack ? <button className='btn' onClick={toBack}>
              <span className="iconify text-white" data-icon="eva:arrow-back-fill"></span>
            </button> :
              <button className='btn' onClick={goback}>
                <span className="iconify text-white" data-icon="eva:arrow-back-fill"></span>
              </button>}
            <h1 className='page_title text-white'>{title}</h1>
          </div>
          {isOrder &&
            <button className="btn" onClick={lihatKeranjang}>
              <span className="iconify text-white" data-icon="clarity:shopping-cart-solid"></span>
              <span className='text-white fw-bold'>{jumlahProdukKeranjang}</span>
            </button>
          }
        </Fragment>
      }

      {isLoadingAuth && <LoadingIndicator />}

      {isDashboard &&
        <Fragment>
          <h1 className='logo text-white'>salesMan</h1>
          <Dropdown align="end">
            <Dropdown.Toggle id="dropdown-basic">
              {dataUser.foto_profil ?
                <img src={`${urlAsset}/storage/staff/${dataUser.foto_profil}`} className="avatar_pp" />
                :
                <img src={`${urlAsset}/images/default_fotoprofil.png`} className="avatar_pp" />
              }
            </Dropdown.Toggle>

            <Dropdown.Menu>
              <Dropdown.Item>
                <div className="btn btn-success w-100" onClick={handleViewProfile}>
                  <span className="iconify me-2 fs-5" data-icon="carbon:user-profile"></span>Profil
                </div>
              </Dropdown.Item>
              <Dropdown.Item>
                <div className="btn btn-danger w-100" onClick={handleLogout}>
                  <span className="iconify me-2 fs-5" data-icon="carbon:logout"></span>Logout
                </div>
              </Dropdown.Item>
            </Dropdown.Menu>
          </Dropdown>
        </Fragment>
      }
    </header>
  );
}

export default HeaderSales;

HeaderSales.defaultProps = {
  isDashboard: false,
  isOrder: false,
  jumlahProdukKeranjang: 0
}
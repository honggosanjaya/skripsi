import React, { Fragment, useContext, useState } from 'react';
import { Link, useHistory } from "react-router-dom";
import urlAsset from '../../config';
import { AuthContext } from '../../contexts/AuthContext';
import { Dropdown } from 'react-bootstrap';
import LoadingIndicator from '../reuse/LoadingIndicator';

const HeaderShipper = ({ title, isDashboard, toBack }) => {
  const { isLoadingAuth, handleLogout } = useContext(AuthContext);
  const history = useHistory();

  const goback = () => {
    history.go(-1);
  }

  const handleViewProfile = () => {
    history.push('/shipper/profil');
  }

  return (
    <header className='header_mobile d-flex justify-content-between align-items-center'>
      {!isDashboard &&
        <Fragment>
          <div className='d-flex align-items-center'>
            {toBack ? <button className='btn' onClick={toBack}>
              <span className="iconify" data-icon="eva:arrow-back-fill"></span>
            </button> :
              <button className='btn' onClick={goback}>
                <span className="iconify" data-icon="eva:arrow-back-fill"></span>
              </button>}
            <h1 className='page_title'>{title}</h1>
          </div>
        </Fragment>
      }

      {isLoadingAuth && <LoadingIndicator />}

      {isDashboard &&
        <Fragment>
          <h1 className='logo'>salesMan</h1>
          <Dropdown>
            <Dropdown.Toggle id="dropdown-basic">
              <img src={`${urlAsset}/images/default_fotoprofil.png`} className="avatar_pp" />
            </Dropdown.Toggle>

            <Dropdown.Menu>
              <Dropdown.Item>
                <div className="btn btn-warning w-100" onClick={handleViewProfile}>
                  <span className="iconify me-2" data-icon="carbon:user-profile"></span>Profil
                </div>
              </Dropdown.Item>
              <Dropdown.Item>
                <div className="btn btn-danger w-100" onClick={handleLogout}>
                  <span className="iconify me-2" data-icon="carbon:logout"></span>Logout
                </div>
              </Dropdown.Item>
            </Dropdown.Menu>
          </Dropdown>
        </Fragment>
      }
    </header>
  );
}

export default HeaderShipper;

HeaderShipper.defaultProps = {
  isDashboard: false
}
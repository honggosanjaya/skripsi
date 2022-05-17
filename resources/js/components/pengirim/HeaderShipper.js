import React, { Fragment, useContext } from 'react';
import { Link, useHistory } from "react-router-dom";
import urlAsset from '../../config';

const HeaderShipper = ({ title, isDashboard }) => {
  const history = useHistory();

  const goback = () => {
    history.go(-1);
  }
  return (
    <header className='header_mobile d-flex justify-content-between align-items-center'>
      {!isDashboard &&
        <Fragment>
          <div className='d-flex align-items-center'>
            <button className='btn' onClick={goback}>
              <span className="iconify" data-icon="eva:arrow-back-fill"></span>
            </button>
            <h1 className='page_title'>{title}</h1>
          </div>
        </Fragment>
      }
      {isDashboard &&
        <Fragment>
          <h1 className='logo'>salesMan</h1>
          <Link to="#">
            <img src={`${urlAsset}/images/default_fotoprofil.png`} className="avatar_pp" />
          </Link>
        </Fragment>
      }
    </header>
  );
}

export default HeaderShipper;

HeaderShipper.defaultProps = {
  isDashboard: false
}
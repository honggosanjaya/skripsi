import React, { Fragment, useContext } from 'react';
import { Link, useHistory } from "react-router-dom";
import urlAsset from '../../config';

const HeaderSales = ({ title, isDashboard, isOrder }) => {
  const history = useHistory();

  const goback = () => {
    history.go(-1);
  }
  return (
    <header className='header_mobile d-flex justify-content-between align-items-center'>
      {!isDashboard &&
        <Fragment>
          <div className='d-flex'>
            <button className='btn btn_goback' onClick={goback}>
              <span className="iconify" data-icon="eva:arrow-back-fill"></span>
            </button>
            <h1>{title}</h1>
          </div>
          {isOrder && <span className="iconify" data-icon="clarity:shopping-cart-solid"></span>}
        </Fragment>
      }
      {isDashboard &&
        <Fragment>
          <h1 className='page_title'>salesMan</h1>
          <Link to="#">
            <img src={`${urlAsset}/images/default_fotoprofil.png`} className="avatar_pp" />
          </Link>
        </Fragment>
      }
    </header>
  );
}

export default HeaderSales;

HeaderSales.defaultProps = {
  isDashboard: false,
  isOrder: false
}
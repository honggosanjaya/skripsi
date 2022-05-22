import React, { Fragment, useContext, useEffect, useState } from 'react';
import { Link, useHistory } from "react-router-dom";
import urlAsset from '../../config';
import { KeranjangSalesContext } from '../../contexts/KeranjangSalesContext';

const HeaderSales = ({ title, isDashboard, isOrder }) => {
  const history = useHistory();
  const { produks, getAllProduks } = useContext(KeranjangSalesContext);

  useEffect(() => {
    getAllProduks();
  }, [produks])


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
          {isOrder &&
            <Link to="/salesman/order/keranjang">
              <span className="iconify" data-icon="clarity:shopping-cart-solid"></span>
              {produks && <span>{produks.length}</span>}
            </Link>
          }
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

export default HeaderSales;

HeaderSales.defaultProps = {
  isDashboard: false,
  isOrder: false
}
import React, { useEffect, useContext } from 'react';
import { splitCharacter } from '../reuse/HelperFunction';
import HeaderShipper from './HeaderShipper';
import { Link } from 'react-router-dom';
import { AuthContext } from '../../contexts/AuthContext';

const DashboardShipper = () => {
  // const { token, isAuth, checkIsAuth } = useContext(AuthContext);

  // useEffect(() => {
  //   checkIsAuth();
  // }, [token, isAuth])

  return (
    <main className="page_main" id="listShipper">
      <HeaderShipper isDashboard={true} />
      <div className="page_container pt-4 dashboard_shipper">
        <div className="word d-flex justify-content-center">
          {splitCharacter("shipper")}
        </div>

        <div className="object-movement">
          <div className="car"><span className="iconify fs-2" data-icon="twemoji:delivery-truck"></span></div>
        </div>

        <h1 className='fs-6 fw-bold'>Menu untuk Tenaga Pengirim</h1>

        <Link to='/shipper/jadwal' className='btn btn-primary btn-lg w-100 mt-3'>
          <span className="iconify me-2" data-icon="fa-solid:shipping-fast"></span>
          Pengiriman
        </Link>
      </div>
    </main>
  );
}

export default DashboardShipper;
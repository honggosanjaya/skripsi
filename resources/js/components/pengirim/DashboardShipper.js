import React, { Component, useState, useEffect, useContext } from 'react';
import { splitCharacter } from '../reuse/HelperFunction';
import HeaderShipper from './HeaderShipper';
import { useHistory } from 'react-router';
import { Link } from 'react-router-dom';

const DashboardShipper = () => {
  // const { token, isAuth, setErrorAuth } = useContext(AuthContext);
  // const history = useHistory();

  // useEffect(() => {
  //   if (isAuth === 'true' && token !== null) {
  //     console.log('perlihatkan')
  //   } else {
  //     history.push('/spa/login');
  //   }
  // }, [token, isAuth])


  return (
    <main className="page_main">
      <HeaderShipper isDashboard={true} />
      <div className="page_container pt-4">
        <div className="word d-flex justify-content-center">
          {splitCharacter("shipper")}
        </div>
        <Link to='/shipper/jadwal' className='btn btn-primary w-100 mt-4'>
          <span className="iconify me-2" data-icon="fa-solid:shipping-fast"></span>
          Pengiriman
        </Link>
      </div>
    </main>
  );
}

export default DashboardShipper;
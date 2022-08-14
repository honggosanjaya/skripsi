import React, { useEffect, useContext, Fragment } from 'react';
import { splitCharacter } from '../reuse/HelperFunction';
import HeaderShipper from './HeaderShipper';
import { Link } from 'react-router-dom';
import { AuthContext } from '../../contexts/AuthContext';
import { UserContext } from '../../contexts/UserContext';
import { useHistory } from "react-router";

const DashboardShipper = () => {
  const { isDefaultPassword } = useContext(AuthContext);
  const { dataUser } = useContext(UserContext);
  const Swal = require('sweetalert2');
  const history = useHistory();

  useEffect(() => {
    if (isDefaultPassword) {
      Swal.fire({
        title: 'Anda Menggunakan Password Default',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ubah Password!'
      }).then((result) => {
        if (result.isConfirmed) {
          history.push('/changepassword');
        }
      })
    }
  }, [])

  return (
    <main className="page_main" id="listShipper">
      {dataUser.role == 'shipper' ?
        <Fragment>
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
        </Fragment>
        : ''}
    </main>
  );
}

export default DashboardShipper;
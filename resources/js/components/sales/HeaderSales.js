import React, { Fragment, useContext, useEffect } from 'react';
import { useHistory } from "react-router-dom";
import urlAsset from '../../config';
import { KeranjangSalesContext } from '../../contexts/KeranjangSalesContext';
import { Dropdown } from 'react-bootstrap';
import { AuthContext } from '../../contexts/AuthContext';
import LoadingIndicator from '../reuse/LoadingIndicator';

const HeaderSales = ({ title, isDashboard, isOrder, lihatKeranjang, toBack }) => {
  const { isLoadingAuth, handleLogout } = useContext(AuthContext);
  const history = useHistory();
  const { produks, getAllProduks } = useContext(KeranjangSalesContext);

  useEffect(() => {
    getAllProduks();
  }, [produks])

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
            <h1 className='page_title'>{title}</h1>
          </div>
          {isOrder &&
            <button className="btn" onClick={lihatKeranjang}>
              <span className="iconify" data-icon="clarity:shopping-cart-solid"></span>
              {produks && <span>{produks.length}</span>}
            </button>
          }
        </Fragment>
      }

      {isLoadingAuth && <LoadingIndicator />}

      {isDashboard &&
        <Fragment>
          <h1 className='logo'>salesMan</h1>
          <Dropdown align="end">
            <Dropdown.Toggle id="dropdown-basic">
              <img src={`${urlAsset}/images/default_fotoprofil.png`} className="avatar_pp" />
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
}
import React, { Fragment, useContext, useEffect, useState } from 'react';
import { Link, useHistory } from "react-router-dom";
import urlAsset from '../../config';
import { KeranjangSalesContext } from '../../contexts/KeranjangSalesContext';
import { Dropdown } from 'react-bootstrap';
import { AuthContext } from '../../contexts/AuthContext';
import LoadingIndicator from '../reuse/LoadingIndicator';

const HeaderSales = ({ title, isDashboard, isOrder, lihatKeranjang }) => {
  const { token, setToken, setIsAuth, setErrorAuth } = useContext(AuthContext);
  const history = useHistory();
  const { produks, getAllProduks } = useContext(KeranjangSalesContext);
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    getAllProduks();
  }, [produks])


  const goback = () => {
    history.go(-1);
  }

  const handleLogout = () => {
    setIsLoading(true);
    axios({
      method: "post",
      url: `${window.location.origin}/api/v1/logout`,
      headers: {
        Authorization: "Bearer " + token,
      }
    })
      .then((response) => {
        console.log('logout', response.data);
        setIsLoading(false);
        setIsAuth('false');
        setToken(null);
        history.push('/spa/login');
      })
      .catch((error) => {
        setIsLoading(false);
        setErrorAuth(error.message);
        console.log('eror logout', error.message);
      });
  }

  const handleViewProfile = () => {
    history.push('/salesman/profil');
  }

  return (
    <header className='header_mobile d-flex justify-content-between align-items-center'>
      {isLoading && <LoadingIndicator />}
      {!isDashboard &&
        <Fragment>
          <div className='d-flex align-items-center'>
            <button className='btn' onClick={goback}>
              <span className="iconify" data-icon="eva:arrow-back-fill"></span>
            </button>
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

export default HeaderSales;

HeaderSales.defaultProps = {
  isDashboard: false,
  isOrder: false
}
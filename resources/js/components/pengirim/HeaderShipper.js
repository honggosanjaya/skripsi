import React, { Fragment, useContext, useState } from 'react';
import { Link, useHistory } from "react-router-dom";
import urlAsset from '../../config';
import { AuthContext } from '../../contexts/AuthContext';
import { Dropdown } from 'react-bootstrap';

const HeaderShipper = ({ title, isDashboard }) => {
  const { token, setToken, setIsAuth, setErrorAuth } = useContext(AuthContext);
  const history = useHistory();
  const [isLoading, setIsLoading] = useState(false);

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
          <Dropdown>
            <Dropdown.Toggle id="dropdown-basic">
              <img src={`${urlAsset}/images/default_fotoprofil.png`} className="avatar_pp" />
            </Dropdown.Toggle>

            <Dropdown.Menu>
              <Dropdown.Item>
                <div className="btn btn-danger" onClick={handleLogout}>Logout</div>
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
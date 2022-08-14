import React, { useRef, useEffect, useContext, useState } from 'react';
import axios from 'axios';
import { AuthContext } from '../../contexts/AuthContext';
import { UserContext } from '../../contexts/UserContext';
import { useHistory } from 'react-router';
import AlertComponent from './AlertComponent';
import HeaderShipper from '../pengirim/HeaderShipper';
import HeaderSales from '../sales/HeaderSales';
import LoadingIndicator from './LoadingIndicator';

let source;
const ChangePassword = () => {
  const { dataUser } = useContext(UserContext);
  const { setIsDefaultPassword } = useContext(AuthContext);
  const [oldPassword, setOldPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmNewPassword, setConfirmNewPassword] = useState('');
  const [isAllowChangePassword, setIsAllowChangePassword] = useState(false);
  const [errorValidasi, setErrorValidasi] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const [hiddenOldPassword, setHiddenOldPassword] = useState(true);
  const [hiddenNewPassword, setHiddenNewPassword] = useState(true);
  const [hiddenConfirmNewPassword, setHiddenConfirmNewPassword] = useState(true);
  const [errorMessage, setErrorMessage] = useState(null);
  const [successMessage, setSuccessMessage] = useState(null);
  const history = useHistory();
  const _isMounted = useRef(true);

  useEffect(() => {
    source = axios.CancelToken.source();
    return () => {
      _isMounted.current = false;
      if (source) {
        source.cancel("Cancelling in cleanup");
      }
    }
  }, []);

  const toBack = () => {
    if (dataUser.role == 'salesman') {
      history.push('/salesman/profil');
    } else if (dataUser.role == 'shipper') {
      history.push('/shipper/profil');
    }
  }

  const toggleShowOldPassword = (e) => {
    e.preventDefault();
    setHiddenOldPassword(!hiddenOldPassword);
  }

  const toggleShowNewPassword = (e) => {
    e.preventDefault();
    setHiddenNewPassword(!hiddenNewPassword);
  }

  const toggleShowConfirmNewPassword = (e) => {
    e.preventDefault();
    setHiddenConfirmNewPassword(!hiddenConfirmNewPassword);
  }

  const handleChangePassword = (e) => {
    e.preventDefault();
    setIsLoading(true);
    source = axios.CancelToken.source();
    axios({
      method: "post",
      url: `${window.location.origin}/api/changepassword/${dataUser.id_staff}`,
      headers: {
        Accept: "application/json",
      },
      cancelToken: source.token,
      data: {
        'new_password': newPassword,
        'confirm_newpassword': confirmNewPassword,
      }
    })
      .then((response) => {
        if (_isMounted.current) {
          setIsLoading(false);
          if (response.data.status == 'success') {
            setErrorValidasi([]);
            setErrorMessage(null);
            setSuccessMessage(response.data.message);
            setNewPassword('');
            setConfirmNewPassword('');
            setIsDefaultPassword(false);
            if (dataUser.role == 'salesman') {
              history.push('/salesman/profil');
            } else if (dataUser.role == 'shipper') {
              history.push('/shipper/profil');
            }
          } else {
            setErrorValidasi(response.data.validate_err);
            throw Error("Error validasi");
          }
        }
      })
      .catch((error) => {
        if (_isMounted.current) {
          setIsLoading(false);
          if (error.response != undefined) {
            setErrorMessage(error.response.data.message);
          } else {
            setErrorMessage(error.message);
          }
        }
      });
  }

  const handleCheckPassword = (e) => {
    e.preventDefault();
    setIsLoading(true);
    source = axios.CancelToken.source();
    axios({
      method: "post",
      url: `${window.location.origin}/api/checkpassword/${dataUser.id_staff}`,
      headers: {
        Accept: "application/json",
      },
      cancelToken: source.token,
      data: {
        'old_password': oldPassword,
      }
    })
      .then((response) => {
        if (_isMounted.current) {
          setIsLoading(false);
          if (response.data.status == 'success') {
            setIsAllowChangePassword(true);
            setErrorMessage(null);
            setErrorValidasi([]);
          } else {
            setErrorValidasi(response.data.validate_err);
            throw Error("Error validasi");
          }
        }
      })
      .catch((error) => {
        if (_isMounted.current) {
          setIsLoading(false);
          if (error.response != undefined) {
            setErrorMessage(error.response.data.message);
          } else {
            setErrorMessage(error.message);
          }
        }
      });
  }

  return (
    <main className="page_main">
      {dataUser.role == 'salesman' && <HeaderSales title="Ubah Password" toBack={toBack} />}
      {dataUser.role == 'shipper' && <HeaderShipper title="Ubah Password" toBack={toBack} />}
      {isLoading && <LoadingIndicator />}
      <div className="page_container pt-4">
        {errorMessage && <AlertComponent errorMsg={errorMessage} />}
        {successMessage && <AlertComponent successMsg={successMessage} />}
        {!isAllowChangePassword &&
          <form onSubmit={handleCheckPassword}>
            <div className="mb-4">
              <label htmlFor="old_password">Password Lama</label>
              <div className="input-group">
                <input
                  type={hiddenOldPassword ? 'password' : 'text'}
                  className="form-control"
                  id="old_password"
                  placeholder="Masukkan password lama"
                  value={oldPassword}
                  onChange={(e) => setOldPassword(e.target.value)}
                  autoComplete="off"
                />
                <button onClick={toggleShowOldPassword} className="btn btn-primary">
                  <i className="iconify"
                    data-icon={hiddenOldPassword ? "akar-icons:eye-open" : "akar-icons:eye-slashed"}></i>
                </button>
              </div>
              {errorValidasi && <small className="text-danger mb-3">{errorValidasi.old_password}</small>}
            </div>
            <button type="submit" className="btn btn-outline-primary w-100 mt-4">Kirim</button>
          </form>}

        {isAllowChangePassword &&
          <form onSubmit={handleChangePassword}>
            <div className="mb-4">
              <label htmlFor="new_password">Password Baru</label>
              <div className="input-group">
                <input
                  type={hiddenNewPassword ? 'password' : 'text'}
                  className="form-control"
                  id="new_password"
                  placeholder="Masukkan password baru"
                  value={newPassword}
                  onChange={(e) => setNewPassword(e.target.value)}
                  autoComplete="off"
                />
                <button onClick={toggleShowNewPassword} className="btn btn_showHidePsw">
                  <i className="iconify"
                    data-icon={hiddenNewPassword ? "akar-icons:eye-open" : "akar-icons:eye-slashed"}></i>
                </button>
              </div>
              {errorValidasi && (errorValidasi.new_password && <small className="text-danger mb-3">{errorValidasi.new_password}</small>)}
            </div>

            <div className="mb-4">
              <label htmlFor="confirm_newpassword">Konfirmasi Password Baru</label>
              <div className="input-group">
                <input
                  type={hiddenConfirmNewPassword ? 'password' : 'text'}
                  className="form-control"
                  id="confirm_newpassword"
                  placeholder="Masukkan konfirmasi password baru"
                  value={confirmNewPassword}
                  onChange={(e) => setConfirmNewPassword(e.target.value)}
                  autoComplete="off"
                />
                <button onClick={toggleShowConfirmNewPassword} className="btn btn_showHidePsw">
                  <i className="iconify"
                    data-icon={hiddenConfirmNewPassword ? "akar-icons:eye-open" : "akar-icons:eye-slashed"}></i>
                </button>
              </div>
              {errorValidasi && (errorValidasi.confirm_newpassword && <small className="text-danger mb-3">{errorValidasi.confirm_newpassword}</small>)}
            </div>
            <button type="submit" className="btn btn-outline-primary w-100 mt-4">Kirim</button>
          </form>
        }
      </div>
    </main>
  );
}

export default ChangePassword;
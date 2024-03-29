import React, { Component, useContext, useEffect, useState } from 'react';
import { useHistory } from "react-router";
import axios from 'axios';
import { AuthContext } from '../../contexts/AuthContext';
import LoadingIndicator from './LoadingIndicator';
import AlertComponent from './AlertComponent';
import { UserContext } from '../../contexts/UserContext';

const LoginReact = () => {
  const history = useHistory();
  const { token, setToken, isAuth, setIsAuth, errorAuth, setErrorAuth, setIsDefaultPassword } = useContext(AuthContext);
  const { dataUser, setDataUser } = useContext(UserContext);
  const [hiddenPassword, setHiddenPassword] = useState(true);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const company_name = process.env.MIX_COMPANY_NAME;

  useEffect(() => {
    axios({
      method: "get",
      url: `${window.location.origin}/api/logoutUser`,
      headers: {
        Accept: "application/json",
      }
    })
      .then(() => {
      })
      .catch((error) => {
        console.log(error.message);
      });
  }, []);

  useEffect(() => {
    if (isAuth === 'true' && token !== null && dataUser) {
      if (dataUser.role == 'administrasi' || dataUser.role == 'supervisor' || dataUser.role == 'owner') {
        axios({
          method: "get",
          url: `${window.location.origin}/api/forceLogout`,
          headers: {
            Accept: "application/json",
          }
        })
          .then(() => {
            setIsAuth('false');
            setToken(null);
          })
          .catch((error) => {
            console.log(error.message);
          });
      }
    }
  }, [dataUser])

  const toggleShow = (e) => {
    e.preventDefault();
    setHiddenPassword(!hiddenPassword);
  }

  const handleSubmit = (e) => {
    e.preventDefault();
    setIsLoading(true);
    axios({
      method: "post",
      url: `${window.location.origin}/api/v1/login`,
      headers: {
        Accept: "application/json",
      },
      data: {
        email: email,
        password: password,
      }
    })
      .then((response) => {
        console.log('login', response.data.data);
        if (password == '12345678') {
          setIsDefaultPassword(true);
        }
        setIsLoading(false);
        setErrorAuth('');
        if (response.data.status == 'success') {
          setToken(response.data.token);
          setIsAuth('true');
          setErrorAuth(null);
          setDataUser(response.data.data);
          if (response.data.data.role == 'salesman') {
            history.push('/salesman');
          } else if (response.data.data.role == 'shipper') {
            history.push('/shipper');
          }
        }

        if (response.data.status == 'error') {
          if (response.data.message == 'please remove local storage') {
            // console.log('rmv local srg run')
            window.localStorage.setItem('token', null);
            window.localStorage.setItem('isAuth ', 'false');
            window.location.reload();
          }
        }
      })
      .catch((error) => {
        setIsLoading(false);
        // window.localStorage.setItem('token', null);
        window.localStorage.setItem('isAuth ', 'false');
        // console.log('catch running')
        if (error.response.status === 401) {
          setErrorAuth(error.response.data.message);
        } else {
          setErrorAuth(error.message);
        }
      });
  }

  return (
    <main className='page_main login_page'>
      <div className="page_container pt-5">
        {isLoading && <LoadingIndicator />}
        <h1 className='logo text-center fs-1 mb-5'>salesMan</h1>
        <h1 className='fs-3 text-center fw-bold'>Selamat Datang<span className="iconify ms-2" data-icon="emojione:hand-with-fingers-splayed"></span></h1>
        <h2 className='fs-6 text-center'>Aplikasi web salesMan <br /> {`${company_name}`}</h2>
        {errorAuth && <AlertComponent errorMsg={errorAuth} />}
        {errorAuth == 'Anda mengakses halaman login yang salah' && <p className='mb-3 text-center'>
          Halaman login khusus untuk salesman dan tenaga pengirim, untuk staff lain silahkan login
          <a href="/login" className="custom-form-input"> disini</a>
        </p>}

        <form onSubmit={handleSubmit} className="mt-5">
          <div className="mb-3">
            <label htmlFor="email">Email</label>
            <input
              type="email"
              className="form-control"
              id="email"
              placeholder="Masukkan Email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
            />
          </div>

          <div className="mb-4">
            <label htmlFor="password">Password</label>
            <div className="input-group">
              <input
                type={hiddenPassword ? 'password' : 'text'}
                className="form-control"
                id="password"
                placeholder="Masukkan Kata Sandi"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                autoComplete="off"
              />
              <button onClick={toggleShow} className="btn btn_showHidePsw">
                <i className="iconify"
                  data-icon={hiddenPassword ? "akar-icons:eye-open" : "akar-icons:eye-slashed"}></i>
              </button>
            </div>
          </div>

          <button type="submit" className="btn btn-primary w-100 my-4" disabled={isLoading}>MASUK</button>
          <a href="/logout" className="custom-form-input fs-7 text-center d-block">Login sebagai Tenaga Kantor</a>
        </form>
      </div>
    </main>
  );
}

export default LoginReact;
import React, { Component, useContext, useEffect, useState } from 'react';
import { Link } from "react-router-dom";
import { useHistory } from "react-router";
import axios from 'axios';
import { AuthContext } from '../../contexts/AuthContext';
import LoadingIndicator from './LoadingIndicator';
import AlertComponent from './AlertComponent';
import { UserContext } from '../../contexts/UserContext';

const LoginReact = () => {
  const history = useHistory();
  const { token, setToken, isAuth, setIsAuth, errorAuth, setErrorAuth } = useContext(AuthContext);
  const { dataUser, setDataUser, setErrorDataUser } = useContext(UserContext);
  const [hiddenPassword, setHiddenPassword] = useState(true);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    console.log('auth', isAuth);
    console.log('token', token);
    console.log('data user', dataUser);

    if (isAuth === 'true' && token !== null && dataUser) {
      console.log('sudah login', dataUser);
      if (dataUser.role == 'salesman') {
        history.push('/salesman');
      } else if (dataUser.role == 'shipper') {
        history.push('/shipper');
      } else if (dataUser.role == 'administrasi' || dataUser.role == 'supervisor' || dataUser.role == 'owner') {
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

        if (token != null) {
          console.log('dijaankan');
          axios({
            method: "get",
            url: `${window.location.origin}/api/user`,
            headers: {
              Accept: "application/json",
              Authorization: "Bearer " + token,
            }
          })
            .then((response) => {
              if (response.data.status === 'error') {
                axios({
                  method: "get",
                  url: `${window.location.origin}/api/forceLogout`,
                  headers: {
                    Accept: "application/json",
                  }
                })
              }
            })
          // .catch((error) => {
          //   setErrorDataUser(error.message);
          // });
        }
      })
      .catch((error) => {
        setIsLoading(false);
        console.log(error.response.data.message);
        setIsAuth('false');
        if (error.response.status === 401) {
          setErrorAuth(error.response.data.message);
        } else {
          setErrorAuth(error.message);
        }
      });
  }

  return (
    <main className='page_main login_page'>
      {(isAuth !== 'true' || token === null) &&
        <div className="page_container pt-5">
          {isLoading && <LoadingIndicator />}

          <h1 className='fs-3 text-center'>Selamat Datang</h1>
          <h2 className='fs-6 text-center'>Aplikasi web salesMan <br /> UD Mandiri</h2>

          {errorAuth && <AlertComponent errorMsg={errorAuth} />}
          {errorAuth == 'Anda mengakses halaman login yang salah' && <p className='mb-3 text-center'>
            Halaman login khusus untuk salesman dan tenaga pengirim, untuk staff lain silahkan login
            <a href="/login" className="custom-form-input"> disini</a>
          </p>}

          <form onSubmit={handleSubmit} className="mt-4">
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

            <button type="submit" className="btn btn-primary w-100 my-4">MASUK</button>
            <p className="hyperlink"><Link to="#" className="lupa_sandi">Lupa Password?</Link></p>
          </form>
        </div>
      }
    </main>
  );
}

export default LoginReact;
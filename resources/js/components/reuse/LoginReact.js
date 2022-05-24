import React, { Component, useContext, useEffect, useState } from 'react';
import { Link } from "react-router-dom";
import { useHistory } from "react-router";
import axios from 'axios';
import { AuthContext } from '../../contexts/AuthContext';
import LoadingIndicator from './LoadingIndicator';
import AlertComponent from './AlertComponent';

const LoginReact = () => {
  const history = useHistory();
  const { token, setToken, isAuth, setIsAuth } = useContext(AuthContext);
  const [hiddenPassword, setHiddenPassword] = useState(true);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [errorValidasi, setErrorValidasi] = useState([]);
  const [error, setError] = useState('');

  // useEffect(() => {
  //   console.log(typeof isAuth);//string
  //   console.log('tf', isAuth == 'true');//false
  //   console.log('tf', token !== null);//true
  //   if (isAuth === 'true' && token !== null) {
  //     history.push('/spa/checkrole');
  //   }
  // }, [])

  const toggleShow = (e) => {
    e.preventDefault();
    setHiddenPassword(!hiddenPassword);
  }

  const handleSubmit = (e) => {
    e.preventDefault();
    setIsLoading(true);
    setErrorValidasi([]);
    setError('');

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
        setIsLoading(false);
        setError('');
        if (response.data.status == 'success') {
          setErrorValidasi([]);
          setToken(response.data.token);
          setIsAuth('true');
          if (response.data.role == 'salesman') {
            history.push('/salesman');
          } else if (response.data.data.role == 'shipper') {
            history.push('/shipper');
          }
        }
        else {
          setErrorValidasi(response.data.validate_err);
        }
      })
      .catch((error) => {
        setIsLoading(false);
        console.log(error.response.data.message);
        setIsAuth('false');
        if (error.response.status === 401) {
          setError(error.response.data.message);
        } else {
          setError(error.message);
        }
      });
  }

  return (
    <main className='page_main login_page'>
      <div className="page_container pt-5">
        {isLoading && <LoadingIndicator />}

        <h1 className='fs-3 text-center'>Selamat Datang</h1>
        <h2 className='fs-6 text-center'>Aplikasi web salesMan <br /> UD Mandiri</h2>

        {error && <AlertComponent errorMsg={error} />}
        {error == 'Anda mengakses halaman login yang salah' && <p className='mb-3 text-center'>
          Halaman login khusus untuk salesman dan tenaga pengirim, untuk staff lain silahkan login
          <a href="/login" className="custom-form-input">disini</a>
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
            {errorValidasi && <small className="text-danger">{errorValidasi.email}</small>}
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
            {errorValidasi && <small className="text-danger">{errorValidasi.password}</small>}
          </div>


          <button type="submit" className="btn btn-primary w-100 my-4">MASUK</button>
          <p className="hyperlink"><Link to="#" className="lupa_sandi">Lupa Password?</Link></p>
        </form>
      </div>
    </main>
  );
}

export default LoginReact;
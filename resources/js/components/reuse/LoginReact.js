import React, { Component, useContext, useEffect, useState } from 'react';
import { Link } from "react-router-dom";
import { useHistory } from "react-router";
import axios from 'axios';
import { AuthContext } from '../../contexts/AuthContext';

const LoginReact = () => {
  const history = useHistory();
  const { token, setToken, isAuth, setIsAuth } = useContext(AuthContext);
  const [hiddenPassword, setHiddenPassword] = useState(true);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  // const [isLoading, setIsLoading] = useState(false);
  const [errorValidasi, setErrorValidasi] = useState([]);
  const [error, setError] = useState('');

  // useEffect(() => {
  //   if (isAuth === 'true' && token !== null) {
  //     // local storage role
  //     // push to beranda
  //   }
  // }, [])

  const toggleShow = (e) => {
    e.preventDefault();
    setHiddenPassword(!hiddenPassword);
  }

  const handleSubmit = (e) => {
    e.preventDefault();
    // setIsLoading(true);
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
        console.log(response);
        if (response.data.status == 'success') {
          // setIsLoading(false);
          setError('');
          setErrorValidasi([]);
          setToken(response.data.token);
          setIsAuth(true);
          if (response.data.role == 'salesman') {
            history.push('/salesman');
          } else if (response.data.data.role == 'shipper') {
            history.push('/shipper');
          } else {
            setError('Woi kamu gak masuk sini');
          }
        }
        else {
          setErrorValidasi(response.data.validate_err);
        }
      })
      .catch((error) => {
        console.log(error.response.data.message);
        if (error.response.status === 401) {
          setError(error.response.data.message);
        } else {
          setError(error.message);
        }
        setIsAuth(false);
      });
  }

  return (
    <main className='page_main login_page'>
      <div className="page_container pt-5">
        <h1 className='heading-1'>Selamat Datang</h1>
        <h2 className='heading-2 '>Aplikasi web salesMan <br /> UD Mandiri</h2>
        <p className='mb-3 text-center'> 
          halaman login khusus untuk salesman dan tenaga pengirim, untuk staff lain silahkan melalui link ini 
          <a href="/login" class="custom-form-input"> link</a>
        </p>
        

        {error &&
          <div className="alert alert-danger alert-dismissible fade show" role="alert">
            <p className='text-center mb-0'>{error}</p>
            <button type="button" className="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        }

        <form onSubmit={handleSubmit}>
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
          <p className="hyperlink"><Link to="#" className="lupa_sandi">Lupa Kata Sandi?</Link></p>
        </form>
      </div>
    </main>
  );
}

export default LoginReact;
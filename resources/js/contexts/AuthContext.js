import React, { createContext, useState, useEffect } from 'react';
import { useHistory } from "react-router";

export const AuthContext = createContext();

const AuthContextProvider = (props) => {
  const history = useHistory();
  const prevToken = window.localStorage.getItem('token') || null;
  const prevLogin = window.localStorage.getItem('isAuth ') || 'false';
  const [isDefaultPassword, setIsDefaultPassword] = useState(false);

  const [token, setToken] = useState(prevToken);
  const [errorAuth, setErrorAuth] = useState('');
  const [successAuth, setSuccessAuth] = useState('');
  const [isAuth, setIsAuth] = useState(prevLogin);
  const [isLoadingAuth, setIsLoadingAuth] = useState(false);

  useEffect(() => {
    window.localStorage.setItem('token', token);
    window.localStorage.setItem('isAuth ', isAuth);
  }, [token, isAuth]);

  const handleLogout = () => {
    setIsLoadingAuth(true);
    axios({
      method: "post",
      url: `${window.location.origin}/api/v1/logout`,
      headers: {
        Authorization: "Bearer " + token,
      }
    })
      .then((response) => {
        setIsLoadingAuth(false);
        setIsAuth('false');
        setToken(null);
        history.push('/spa/login');
      })
      .catch((error) => {
        setIsLoadingAuth(false);
        setErrorAuth(error.message);
      });
  }

  const checkIsAuth = () => {
    console.log('takeout perubahan react ke laravel');
    // if (isAuth === 'true' && token !== null) {
    // } else {
    //   history.push('/spa/login');
    // }
  }

  useEffect(() => {
    checkIsAuth();
  }, [token, isAuth]);

  const defaultContext = {
    token, setToken,
    errorAuth, setErrorAuth,
    successAuth, setSuccessAuth,
    isAuth, setIsAuth,
    isLoadingAuth, handleLogout,
    checkIsAuth,
    isDefaultPassword, setIsDefaultPassword
  }

  return (
    <AuthContext.Provider
      value={defaultContext}>
      {props.children}
    </AuthContext.Provider>
  );
}

export default AuthContextProvider;
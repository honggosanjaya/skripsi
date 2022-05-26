import React, { createContext, useState, useEffect } from 'react';
import { useHistory } from "react-router";

export const AuthContext = createContext();

const AuthContextProvider = (props) => {
  const history = useHistory();
  const prevToken = window.localStorage.getItem('token') || null;
  const prevLogin = window.localStorage.getItem('isAuth ') || 'false';

  const [token, setToken] = useState(prevToken);
  const [errorAuth, setErrorAuth] = useState('');
  const [successAuth, setSuccessAuth] = useState('');
  const [isAuth, setIsAuth] = useState(prevLogin);

  useEffect(() => {
    window.localStorage.setItem('token', token);
    window.localStorage.setItem('isAuth ', isAuth);
  }, [token, isAuth]);

  const defaultContext = {
    token, setToken,
    errorAuth, setErrorAuth,
    successAuth, setSuccessAuth,
    isAuth, setIsAuth,
  }

  return (
    <AuthContext.Provider
      value={defaultContext}>
      {props.children}
    </AuthContext.Provider>
  );
}

export default AuthContextProvider;
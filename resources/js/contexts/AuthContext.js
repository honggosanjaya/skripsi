import React, { createContext, useState, useEffect } from 'react';

export const AuthContext = createContext();

const AuthContextProvider = (props) => {
  const prevToken = window.localStorage.getItem('token') || null;
  const prevLogin = window.localStorage.getItem('isAuthenticated ') || false;

  const [token, setToken] = useState(prevToken);
  const [isAuth, setIsAuth] = useState(prevLogin);

  useEffect(() => {
    window.localStorage.setItem('token', token);
    window.localStorage.setItem('isAuth ', isAuth);
  }, [token, isAuth])

  const defaultContext = {
    token, setToken,
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
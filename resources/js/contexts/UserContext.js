import React, { createContext, useContext, useEffect, useState } from 'react';
import { useHistory } from 'react-router';
import { AuthContext } from './AuthContext';

export const UserContext = createContext();

const UserContextProvider = (props) => {
  const { token, setErrorAuth, setSuccessAuth, setIsAuth } = useContext(AuthContext);
  const history = useHistory();
  const [loadingDataUser, setLoadingDataUser] = useState(false);
  const [dataUser, setDataUser] = useState([]);

  useEffect(() => {
    setLoadingDataUser(true);
    axios({
      method: "get",
      url: `${window.location.origin}/api/user`,
      headers: {
        Authorization: "Bearer " + token,
      }
    })
      .then((response) => {
        if (response.data.status !== 'success') {
          setIsAuth('false');
          throw Error(response.data.message);
        } else {
          console.log("user :", response.data);
          setDataUser(response.data.data);
          setIsAuth('true');
          setLoadingDataUser(false);
        }
      })
      .catch((error) => {
        if (error.response.data.message === 'Unauthenticated.' || error.response.status === 401) {
          setIsAuth('false');
        }
        history.push('/spa/login');
        setErrorAuth('Login Terlebih Dahulu');
        setLoadingDataUser(false);
        setSuccessAuth('');
      });
  }, []);

  return (
    <UserContext.Provider value={{ dataUser, loadingDataUser, setDataUser }}>
      {props.children}
    </UserContext.Provider>
  );
}

export default UserContextProvider;
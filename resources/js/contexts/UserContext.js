import React, { createContext, useContext, useEffect, useState } from 'react';
import { AuthContext } from './AuthContext';
import { useHistory } from "react-router";
export const UserContext = createContext();

const UserContextProvider = (props) => {
  const { token, setToken, isAuth, setIsAuth } = useContext(AuthContext);
  const [loadingDataUser, setLoadingDataUser] = useState(false);
  const [dataUser, setDataUser] = useState([]);
  const [errorDataUser, setErrorDataUser] = useState(null);
  const history = useHistory();


  const forceLogout = () => {
    console.log('dari user context logout paksa');
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
        history.push('/spa/login');
      })
  }

  useEffect(() => {
    if (isAuth === 'true' && token !== null & dataUser.length === 0) {
      setLoadingDataUser(true);
      axios({
        method: "get",
        url: `${window.location.origin}/api/user`,
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token,
        }
      })
        .then((response) => {
          console.log("user context:", response.data.data);
          setLoadingDataUser(false);
          if (response.data.status === 'success') {
            setDataUser(response.data.data);
          } else {
            forceLogout();
          }
        })
        .catch((error) => {
          setLoadingDataUser(false);
          setErrorDataUser(error.message);
          // disini masih ragu, kalau error forceLogout(); dikomen aja
          // forceLogout();
        });
    }
  }, [dataUser]);

  return (
    <UserContext.Provider value={{ dataUser, loadingDataUser, setDataUser }}>
      {props.children}
    </UserContext.Provider>
  );
}

export default UserContextProvider;
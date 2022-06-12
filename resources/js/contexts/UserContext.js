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
    let unmounted = false;
    let source = axios.CancelToken.source();
    if (isAuth === 'true' && token !== null & dataUser.length === 0) {
      setLoadingDataUser(true);
      axios({
        method: "get",
        url: `${window.location.origin}/api/user`,
        cancelToken: source.token,
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + token,
        }
      })
        .then((response) => {
          if (!unmounted) {
            console.log("user context:", response.data.data);
            setLoadingDataUser(false);
            if (response.data.status === 'success') {
              setDataUser(response.data.data);
            } else {
              forceLogout();
            }
          }
        })
        .catch((error) => {
          if (!unmounted) {
            setLoadingDataUser(false);
            setErrorDataUser(error.message);
            // disini masih ragu, kalau error forceLogout(); dikomen aja
            // forceLogout();
          }
        });
    }

    return function () {
      unmounted = true;
      source.cancel("Cancelling in cleanup");
    };
  }, [dataUser]);

  return (
    <UserContext.Provider value={{ dataUser, loadingDataUser, setDataUser }}>
      {props.children}
    </UserContext.Provider>
  );
}

export default UserContextProvider;
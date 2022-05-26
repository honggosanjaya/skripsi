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
            console.log('dari user context logout paksa');
            // setIsAuth('false');
            // setToken(null);
            // history.push('/spa/login');
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
        })
        .catch((error) => {
          setLoadingDataUser(false);
          setErrorDataUser(error.message);
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
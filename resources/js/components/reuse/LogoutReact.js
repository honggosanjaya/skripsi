import React, { useContext, useEffect } from 'react';
import { AuthContext } from '../../contexts/AuthContext';
import LoadingIndicator from '../reuse/LoadingIndicator';

const LogoutReact = () => {
  const { isLoadingAuth, handleLogout } = useContext(AuthContext);

  useEffect(() => {
    handleLogout();
  }, []);

  return (
    <div>
      {isLoadingAuth && <LoadingIndicator />}
    </div>
  );

  // return null;
}

export default LogoutReact;
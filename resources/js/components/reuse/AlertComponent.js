import React, { useEffect, useState } from 'react';

const AlertComponent = ({ errorMsg, successMsg }) => {
  const [errorMessage, setErrorMessage] = useState(errorMsg);
  const [successMessage, setSuccessMessage] = useState(successMsg);

  useEffect(() => {
    setErrorMessage(errorMsg);
    setSuccessMessage(successMsg);
  }, [successMsg, errorMsg])

  return (
    <div>
      {errorMessage && <div className="alert alert-danger alert-dismissible fade show" role="alert">
        {errorMessage}
        <button type="button" className="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>}

      {successMessage && <div className="alert alert-success alert-dismissible fade show" role="alert">
        {successMessage}
        <button type="button" className="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>}
    </div>
  );
}

AlertComponent.defaultProps = {
  errorMsg: '',
  successMsg: '',
}

export default AlertComponent;
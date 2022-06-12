import React, { createContext, useState, useEffect } from 'react';

export const ReturContext = createContext();

const ReturContextProvider = (props) => {
  const [idInvoice, setIdInvoice] = useState([]);

  const defaultContext = {
    idInvoice,
    setIdInvoice,
  }

  return (
    <ReturContext.Provider
      value={defaultContext}>
      {props.children}
    </ReturContext.Provider>
  );
}

export default ReturContextProvider;
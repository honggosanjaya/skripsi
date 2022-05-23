import React, { createContext, useState, useEffect } from 'react';

export const CustomerContext = createContext();

const CustomerContextProvider = (props) => {
  const [customer, setCustomer] = useState(null);

  const defaultContext = {
    customer,
    setCustomer
  }

  return (
    <CustomerContext.Provider
      value={defaultContext}>
      {props.children}
    </CustomerContext.Provider>
  );
}

export default CustomerContextProvider;
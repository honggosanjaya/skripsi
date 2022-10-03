import React, { createContext, useState, useEffect } from 'react';
import KeranjangDB from '../components/reuse/KeranjangDB';

export const KeranjangSalesContext = createContext();

const KeranjangSalesContextProvider = (props) => {

  const [produks, setProduks] = useState([]);
  const [isBelanjaLagi, setIsBelanjaLagi] = useState(false);

  const getAllProduks = () => {
    const produks = KeranjangDB.getAllProduks();
    produks.then((response) => {
      setProduks(response);
    })
  }

  useEffect(() => {
    getAllProduks();
  }, [])

  const defaultContext = {
    produks,
    setProduks,
    getAllProduks,
    isBelanjaLagi,
    setIsBelanjaLagi
  }

  return (
    <KeranjangSalesContext.Provider
      value={defaultContext}>
      {props.children}
    </KeranjangSalesContext.Provider>
  );
}

export default KeranjangSalesContextProvider;
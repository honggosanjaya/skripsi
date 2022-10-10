import React, { createContext, useState, useEffect } from 'react';
import KeranjangDB from '../components/reuse/KeranjangDB';

export const KeranjangSalesContext = createContext();

const KeranjangSalesContextProvider = (props) => {

  const [produks, setProduks] = useState([]);
  const [isBelanjaLagi, setIsBelanjaLagi] = useState(false);
  const [canOrderKanvas, setCanOrderKanvas] = useState(false);

  const getAllProduks = () => {
    const produks = KeranjangDB.getAllProduks();
    produks.then((response) => {
      setProduks(response);
    })
  }

  useEffect(() => {
    getAllProduks();
  }, [])

  useEffect(() => {
    let allTrue = produks.every((v) => v["canStokKanvas"] == true);
    setCanOrderKanvas(allTrue);
  }, [produks])

  const defaultContext = {
    produks,
    setProduks,
    getAllProduks,
    isBelanjaLagi,
    setIsBelanjaLagi,
    canOrderKanvas
  }

  return (
    <KeranjangSalesContext.Provider
      value={defaultContext}>
      {props.children}
    </KeranjangSalesContext.Provider>
  );
}

export default KeranjangSalesContextProvider;
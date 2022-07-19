import React, { createContext, useState, useEffect } from 'react';

export const HitungStokContext = createContext();

const HitungStokContextProvider = (props) => {

  const [newHistoryItem, setNewHistoryItem] = useState([]);
  const [kodePesanan, setKodePesanan] = useState(null);

  const defaultContext = {
    newHistoryItem,
    setNewHistoryItem,
    kodePesanan,
    setKodePesanan,
  }

  return (
    <HitungStokContext.Provider
      value={defaultContext}>
      {props.children}
    </HitungStokContext.Provider>
  );
}

export default HitungStokContextProvider;
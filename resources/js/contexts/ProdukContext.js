import React, { createContext, useState } from 'react';

export const ProdukContext = createContext();

const ProdukContextProvider = (props) => {
  const produks = [
    { "id": 1, "nama": "Sapu Apik", "harga": 10000 },
    { "id": 2, "nama": "Sepatu Apik", "harga": 300000 },
    { "id": 3, "nama": "Hp Apik", "harga": 20000000 },
  ];


  return (
    <ProdukContext.Provider value={{ produks }}>
      {props.children}
    </ProdukContext.Provider>
  );
}

export default ProdukContextProvider;
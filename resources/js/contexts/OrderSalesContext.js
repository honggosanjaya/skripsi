import React, { createContext, useState } from 'react';

export const OrderSalesContext = createContext();

const OrderSalesContextProvider = (props) => {
  const produks = [
    { "id": 1, "nama": "Sapu Apik", "harga": 10000 },
    { "id": 2, "nama": "Sepatu Apik", "harga": 300000 },
    { "id": 3, "nama": "Hp Apik", "harga": 20000000 },
  ];


  return (
    <OrderSalesContext.Provider value={{ produks }}>
      {props.children}
    </OrderSalesContext.Provider>
  );
}

export default OrderSalesContextProvider;
import React, { Component, useContext, useEffect, useState } from 'react';
import { useHistory } from "react-router";
import { UserContext } from '../../contexts/UserContext';

const CheckRole = () => {
  const history = useHistory();
  const { dataUser, loadingDataUser } = useContext(UserContext);

  useEffect(() => {
    if (dataUser.role == 3) {
      history.push('/salesman');
    } else if (dataUser.role == 4) {
      history.push('/shipper');
    }
  }, [dataUser])

  return null;
}

export default CheckRole;
// import React, { Component, useContext, useEffect, useState } from 'react';
// import { useHistory } from "react-router";
// import { UserContext } from '../../contexts/UserContext';

// const CheckRole = () => {
//   const history = useHistory();
//   const { dataUser } = useContext(UserContext);

//   useEffect(() => {
//     if (dataUser.length) {
//       if (dataUser.role == 'salesman') {
//         history.push('/salesman');
//       } else if (dataUser.role == 'shipper') {
//         history.push('/shipper');
//       }
//     } else {
//       console.log('kamu loh belum login');
//     }
//   }, [])
//   // dataUser

//   return null;
// }

// export default CheckRole;
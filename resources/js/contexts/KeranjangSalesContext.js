import React, { createContext, useState, useEffect } from 'react';
import KeranjangDB from '../components/reuse/KeranjangDB';
// import GroupItemDB from '../components/reuse/GroupItemDB';

export const KeranjangSalesContext = createContext();

const KeranjangSalesContextProvider = (props) => {
  const [produks, setProduks] = useState([]);
  // const [groupProduks, setGroupProduks] = useState([]);
  const [isBelanjaLagi, setIsBelanjaLagi] = useState(false);
  const [canOrderKanvas, setCanOrderKanvas] = useState(false);
  const [idCustomer, setIdCustomer] = useState(null);
  // const [dataGroupItem, setDataGroupItem] = useState([]);

  const getAllProduks = () => {
    const produks = KeranjangDB.getAllProduks();
    produks.then((response) => {
      setProduks(response);
    })
  }

  // const getAllGroupProduks = () => {
  //   const groupProduks = GroupItemDB.getAllProduks();
  //   groupProduks.then((response) => {
  //     setGroupProduks(response);
  //   })
  // }

  useEffect(() => {
    getAllProduks();
    // getAllGroupProduks();
  }, [])

  useEffect(() => {
    let allTrue = produks.every((v) => v["canStokKanvas"] == true);
    setCanOrderKanvas(allTrue);
  }, [produks])

  // useEffect(() => {
  //   let unmounted = false;
  //   let source = axios.CancelToken.source();
  //   axios({
  //     method: "get",
  //     url: `${window.location.origin}/api/salesman/groupItem`,
  //     cancelToken: source.token,
  //     headers: {
  //       Accept: "application/json",
  //     },
  //   })
  //     .then((response) => {
  //       if (!unmounted) {
  //         setDataGroupItem(response.data.data);
  //         // console.log('dataGroupItem', response.data.data);
  //       }
  //     })
  //     .catch((error) => {
  //       if (!unmounted) {
  //         console.log(error.message);
  //       }
  //     });

  //   return function () {
  //     unmounted = true;
  //     source.cancel("Cancelling in cleanup");
  //   };
  // }, []);

  const defaultContext = {
    produks,
    setProduks,
    getAllProduks,
    isBelanjaLagi,
    setIsBelanjaLagi,
    canOrderKanvas,
    idCustomer,
    setIdCustomer,
    // groupProduks, setGroupProduks,
    // getAllGroupProduks,
    // dataGroupItem, setDataGroupItem
  }

  return (
    <KeranjangSalesContext.Provider
      value={defaultContext}>
      {props.children}
    </KeranjangSalesContext.Provider>
  );
}

export default KeranjangSalesContextProvider;
import React from 'react';
import ReactDOM from "react-dom";
import { SWRConfig } from 'swr';
import { BrowserRouter as Router, Route, Switch } from "react-router-dom";


import AuthContextProvider from '../contexts/AuthContext';
import KeranjangSalesContextProvider from '../contexts/KeranjangSalesContext';
import UserContextProvider from '../contexts/UserContext';


import LoginReact from './reuse/LoginReact';
import LogoutReact from './reuse/LogoutReact';
import Profil from './reuse/Profil';
import ChangePassword from './reuse/ChangePassword';


import NotFound from './reuse/NotFound';
import DashboardSales from './sales/DashboardSales';
import KeranjangSales from './sales/KeranjangSales';
import TripSales from './sales/TripSales';
import HitungStokContextProvider from '../contexts/HitungStokContext';
import Pemesanan from './sales/Pemesanan';


import DashboardShipper from './pengirim/DashboardShipper';
import JadwalShipper from './pengirim/JadwalShipper';
import ReturShipper from './pengirim/ReturShipper';
import ReturContextProvider from '../contexts/ReturContext';
import HistoryTrip from './sales/HistoryTrip';
import Reimbursement from './sales/Reimbursement';
import HistoryInvoice from './sales/HistoryInvoice';
import Penagihan from './reuse/Penagihan';
import ItemKanvas from './sales/ItemKanvas';
import HistoryKanvas from './sales/HistoryKanvas';
import CatalogItem from './sales/CatalogItem';
import PrintComponent from './sales/PrintComponent';
import DetailCatalogItem from './sales/DetailCatalogItem';


function App() {
  return (
    <Router>
      <AuthContextProvider>
        <UserContextProvider>
          <Switch>
            <Route path="/spa/login" component={LoginReact} />
            <Route path="/spa/logout" component={LogoutReact} />

            <Route exact path="/lapangan/penagihan" component={Penagihan} />
            <Route exact path="/changepassword" component={ChangePassword} />

            <Route path={["/shipper"]}>
              <Route exact path="/shipper" component={DashboardShipper} />
              <Route exact path="/shipper/profil" component={Profil} />
            </Route>

            <Route path={["/salesman"]}>
              <KeranjangSalesContextProvider>
                <Route exact path="/salesman" component={DashboardSales} />
                <Route exact path="/salesman/trip" component={TripSales} />
                <Route exact path="/salesman/trip/:id" component={TripSales} />
                <Route exact path="/salesman/history" component={HistoryTrip} />
                <Route exact path="/salesman/historyinvoice" component={HistoryInvoice} />
                <Route exact path="/salesman/itemkanvas" component={ItemKanvas} />
                <Route exact path="/salesman/itemkanvas/history" component={HistoryKanvas} />
                <Route exact path="/salesman/catalog/:idCust" component={CatalogItem} />
                <Route exact path="/salesman/historyinvoice/cetak/:idInvoice" component={PrintComponent} />
                <Route exact path="/salesman/detailcatalog/:tipeHarga/:idItem" component={DetailCatalogItem} />

                <Route exact path="/salesman/cetakInvoice/:idInvoice" component={PrintComponent} />

                <HitungStokContextProvider>
                  <Route exact path="/salesman/order/:idCust" component={Pemesanan} />
                  <Route exact path="/salesman/keranjang/:idCust" component={KeranjangSales} />
                </HitungStokContextProvider>
                <Route exact path="/salesman/reimbursement" component={Reimbursement} />
                <Route exact path="/salesman/profil" component={Profil} />
              </KeranjangSalesContextProvider>
            </Route>

            <ReturContextProvider>
              <Route exact path="/lapangan/jadwal" component={JadwalShipper} />
              <Route exact path="/lapangan/retur/:idCust" component={ReturShipper} />
            </ReturContextProvider>

            <Route path="*" component={NotFound} />
          </Switch>
        </UserContextProvider>
      </AuthContextProvider>
    </Router>
  )
}


const fetcher = (url, token) =>
  axios({
    method: "get",
    url: url,
    headers: {
      Accept: "application/json",
      Authorization: "Bearer " + token,
    },
  })
    .then((response) => {
      console.log(response.data.data);
      return response.data.data;
    })

ReactDOM.render(
  <SWRConfig value={{ fetcher }}>
    <App />
  </SWRConfig>, document.getElementById("app"));
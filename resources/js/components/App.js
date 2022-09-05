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


function App() {
  return (
    <Router>
      <AuthContextProvider>
        <UserContextProvider>
          <Switch>
            <Route path="/spa/login" component={LoginReact} />
            <Route path="/spa/logout" component={LogoutReact} />

            <Route exact path="/lapangan/penagihan" component={Penagihan} />

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

            <Route exact path="/changepassword" component={ChangePassword} />

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
  <React.StrictMode>
    <SWRConfig value={{ fetcher }}>
      <App />
    </SWRConfig>
  </React.StrictMode>, document.getElementById("app"));
import React from 'react';
import ReactDOM from "react-dom";
import { SWRConfig } from 'swr';
import { BrowserRouter as Router, Route, Switch } from "react-router-dom";

// Sales Page
import NotFound from './reuse/NotFound';
import DashboardSales from './sales/DashboardSales';
import KeranjangSales from './sales/KeranjangSales';
import TripSales from './sales/TripSales';

// Shipper Page
import DashboardShipper from './pengirim/DashboardShipper';
import LoginReact from './reuse/LoginReact';
import AuthContextProvider from '../contexts/AuthContext';
import Pemesanan from './sales/Pemesanan';
import KeranjangSalesContextProvider from '../contexts/KeranjangSalesContext';
import UserContextProvider from '../contexts/UserContext';

function App() {
  return (
    <Router>
      <AuthContextProvider>
        <UserContextProvider>
          <Switch>
            <Route path="/spa/login" component={LoginReact} />

            <Route exact path="/shipper/dashboard" component={DashboardShipper} />


            <Route path={["/salesman"]}>
              <KeranjangSalesContextProvider>
                <Route exact path="/salesman" component={DashboardSales} />
                <Route exact path="/salesman/trip" component={TripSales} />
                <Route exact path="/salesman/trip/:id" component={TripSales} />
                <Route exact path="/salesman/order/:idCust" component={Pemesanan} />
                <Route exact path="/salesman/keranjang/:idCust" component={KeranjangSales} />
              </KeranjangSalesContextProvider>
            </Route>


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
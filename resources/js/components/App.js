import React from 'react';
import ReactDOM from "react-dom";
import { SWRConfig } from 'swr';
import { BrowserRouter as Router, Route, Switch } from "react-router-dom";

// Sales Page
import NotFound from './reuse/NotFound';
import DashboardSales from './sales/DashboardSales';
import KeranjangSales from './sales/KeranjangSales';
import TripSales from './sales/TripSales';
import TripSalesId from './sales/TripSales';

// Shipper Page
import DashboardShipper from './pengirim/DashboardShipper';
import LoginReact from './reuse/LoginReact';
import AuthContextProvider from '../contexts/AuthContext';
import Pemesanan from './sales/Pemesanan';
import KeranjangSalesContextProvider from '../contexts/KeranjangSalesContext';


function App() {
  return (
    <Router>
      <AuthContextProvider>
        <Switch>
          <Route path="/spa/login" component={LoginReact} />

          <Route exact path="/salesman" component={DashboardSales} />
          <Route exact path="/salesman/trip" component={TripSales} />
          <Route exact path="/salesman/trip/:id" component={TripSalesId} />

          <KeranjangSalesContextProvider>
            <Route exact path={["/salesman/order", "/salesman/order/keranjang"]}>
              <Route exact path="/salesman/order" component={Pemesanan} />
              <Route path="/salesman/order/keranjang" component={KeranjangSales} />
            </Route>
          </KeranjangSalesContextProvider>


          <Route exact path="/shipper" component={DashboardShipper} />

          <Route path="*" component={NotFound} />
        </Switch>
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
      console.log(response.data);
      return response.data.data;
    })

ReactDOM.render(
  <React.StrictMode>
    <SWRConfig value={{ fetcher }}>
      <App />
    </SWRConfig>
  </React.StrictMode>, document.getElementById("app"));
import React from 'react';
import ReactDOM from "react-dom";
import { BrowserRouter as Router, Route, Switch } from "react-router-dom";
import OrderSalesContextProvider from '../contexts/OrderSalesContext';


// Sales Page
import NotFound from './reuse/NotFound';
import DashboardSales from './sales/DashboardSales';
import KeranjangSales from './sales/KeranjangSales';
import OrderSales from './sales/OrderSales';
import TripSales from './sales/TripSales';
import TripSalesId from './sales/TripSales';

// Shipper Page
import DashboardShipper from './pengirim/DashboardShipper';
import LoginReact from './reuse/LoginReact';
import AuthContextProvider from '../contexts/AuthContext';


function App() {
  return (
    <Router>
      <AuthContextProvider>
        <Switch>
          <Route path="/spa/login" component={LoginReact} />

          <Route exact path="/salesman" component={DashboardSales} />
          <Route exact path="/salesman/trip" component={TripSales} />
          <Route exact path="/salesman/trip/:id" component={TripSalesId} />
          <Route exact path={["/salesman/order", "/salesman/order/keranjang"]}>
            <OrderSalesContextProvider>
              <Route exact path="/salesman/order" component={OrderSales} />
              <Route path="/salesman/order/keranjang" component={KeranjangSales} />
            </OrderSalesContextProvider>
          </Route>

          <Route exact path="/shipper" component={DashboardShipper} />

          <Route path="*" component={NotFound} />
        </Switch>
      </AuthContextProvider>
    </Router>
  )
}


ReactDOM.render(
  <React.StrictMode>
    <App />
  </React.StrictMode>, document.getElementById("app"));
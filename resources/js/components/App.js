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

// Shipper Page
import DashboardShipper from './pengirim/DashboardShipper';


function App() {
  return (
    <Router>
      <Switch>
        <Route exact path="/sales/dashboard" component={DashboardSales} />
        <Route exact path="/sales/trip" component={TripSales} />
        <Route exact path={["/sales/order", "/sales/order/keranjang"]}>
          <OrderSalesContextProvider>
            <Route exact path="/sales/order" component={OrderSales} />
            <Route path="/sales/order/keranjang" component={KeranjangSales} />
          </OrderSalesContextProvider>
        </Route>

        <Route exact path="/shipper/dashboard" component={DashboardShipper} />

        <Route path="*" component={NotFound} />
      </Switch>
    </Router>

  )
}


ReactDOM.render(
  <React.StrictMode>
    <App />
  </React.StrictMode>, document.getElementById("app"));
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


function App() {
  return (
    <Router basename='/sales'>
      <Switch>
        <Route exact path="/makan" component={DashboardSales} />
        <Route exact path="/trip" component={TripSales} />
        <Route exact path={["/order", "/order/keranjang"]}>
          <OrderSalesContextProvider>
            <Route exact path="/order" component={OrderSales} />
            <Route path="/order/keranjang" component={KeranjangSales} />
          </OrderSalesContextProvider>
        </Route>

        <Route path="*" component={NotFound} />
      </Switch>
    </Router>
  )
}


ReactDOM.render(
  <React.StrictMode>
    <App />
  </React.StrictMode>, document.getElementById("app"));
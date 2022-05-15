import React from 'react';
import ReactDOM from "react-dom";
import { BrowserRouter as Router, Route, Switch } from "react-router-dom";
import ProdukContextProvider from '../contexts/ProdukContext';

// Sales Page
import NotFound from './reuse/NotFound';
import Produk from './sales/Produk';
import Keranjang from './sales/Keranjang';

function App() {
  return (
    <Router basename='/sales'>
      <Switch>

        <Route exact path={["/produk", "/produk/keranjang"]}>
          <ProdukContextProvider>
            <Route exact path="/produk" component={Produk} />
            <Route path="/produk/keranjang" component={Keranjang} />
          </ProdukContextProvider>
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
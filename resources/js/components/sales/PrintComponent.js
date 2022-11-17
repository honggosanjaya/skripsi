import React, { useRef, useState, useContext, useEffect, Fragment } from "react";
import ReactToPrint from "react-to-print";
import ComponentToPrint from './ComponentToPrint';
import HeaderSales from './HeaderSales';
import { useParams } from 'react-router-dom';
import { UserContext } from '../../contexts/UserContext';

export default function PrintComponent() {
  let componentRef = useRef();
  const { idInvoice } = useParams();
  const [invoice, setInvoice] = useState(null);
  const [isLoading, setIsLoading] = useState(false);
  const { dataUser } = useContext(UserContext);

  useEffect(() => {
    setIsLoading(true);
    axios({
      method: "get",
      url: `${window.location.origin}/api/salesman/getinvoice/${idInvoice}`,
      headers: {
        Accept: "application/json",
      },
    })
      .then(response => {
        // console.log('data inv', response.data.data);
        setInvoice(response.data.data);
        setIsLoading(false);
      })
      .catch(error => {
        console.log(error.message);
        setIsLoading(false);
      });
  }, [])

  return (
    <main className="page_main">
      <HeaderSales title="Cetak Invoice" />

      <div className="page_container pt-4">
        <div className="print-component">
          {!isLoading && invoice &&
            <Fragment>
              <ComponentToPrint ref={(el) => (componentRef = el)} invoice={invoice} userName={dataUser.nama ?? null} />

              <ReactToPrint
                trigger={() => <button className="btn btn-primary mt-4 w-100"><span className="iconify fs-3 me-1" data-icon="bi:printer"></span> Cetak</button>}
                content={() => componentRef}
              />
            </Fragment>
          }
        </div>
      </div>
    </main>
  );
}
import React, { Fragment } from 'react';

class ComponentToPrint extends React.Component {
  render() {
    const { invoice } = this.props;
    let totalSementara = 0;
    const company_name = process.env.MIX_COMPANY_NAME;

    return (
      <div className="print-component border p-2">
        <h1 className="fs-6 text-center mb-4">{invoice.nomor_invoice ?? null}</h1>
        <hr />

        {invoice.link_order.link_order_item.map((data, index) => (
          <div className="d-flex justify-content-between" key={index}>
            <p className="mb-0">{data.link_item.nama ?? null}</p>
            <p className="mb-0">{data.kuantitas ?? null} x {data.harga_satuan ?? null}</p>
          </div>
        ))}

        {invoice.link_order.link_order_item.map((data) => {
          totalSementara += (data.kuantitas ?? 0) * (data.harga_satuan ?? 0)
        })}

        <hr />
        <div className="d-flex justify-content-between">
          <p className='mb-0'>Total</p>
          {<p className="mb-0">Rp{totalSementara.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</p>}
        </div>
        <div className="d-flex justify-content-between">
          <p className='mb-0'>Total Diskon</p>
          <p className="mb-0">Rp{(totalSementara - invoice.harga_total ?? 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</p>
        </div>

        <hr />
        <div className="d-flex justify-content-between">
          <p className='mb-0'>Total Pembayaran</p>
          <p className="mb-0">Rp{(invoice.harga_total ?? 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</p>
        </div>

        <p className='text-center mt-4 mb-0'>Dicetak oleh {`${company_name}`}</p>
      </div>
    );
  }
}

export default ComponentToPrint;
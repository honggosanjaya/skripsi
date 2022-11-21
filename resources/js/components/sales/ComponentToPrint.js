import React, { useEffect, useContext, Fragment } from 'react';
import urlAsset from '../../config';

class ComponentToPrint extends React.Component {
  render() {
    const { invoice, userName } = this.props;
    let totalSementara = 0;
    const company_name = process.env.MIX_COMPANY_NAME;
    const company_address = process.env.MIX_COMPANY_ADDRESS;

    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
    const now = new Date();

    const todayDate = [
      now.getDate(),
      ' ',
      months[now.getMonth()],
      ' ',
      now.getFullYear(),
      ' '
    ].join('');

    const todayTime = [
      now.getHours(),
      ':',
      (now.getMinutes() < 10 ? '0' : '') + now.getMinutes()
    ].join('');


    return (
      <div className="print-component border p-2">
        <img src={`${urlAsset}/images/icon-perusahaan.png`} className="img-fluid d-block mx-auto" width="130px" />
        <h1 className="text-center fs-7 mb-0"><b>{`${company_name}`}</b></h1>
        <p className="text-center fs-7">{`${company_address}`}</p>

        <div className="d-flex justify-content-between align-items-center">
          <p className='mb-0 fs-7'>{todayDate}</p>
          <p className="mb-0 fs-7 text-end">{todayTime}</p>
        </div>
        <div className="d-flex justify-content-between align-items-center">
          <p className='mb-0 fs-7'>Receipt Number</p>
          <p className="mb-0 fs-7 text-end">{invoice.nomor_invoice ?? null}</p>
        </div>
        <div className="d-flex justify-content-between align-items-center">
          <p className='mb-0 fs-7'>Collected By</p>
          <p className="mb-0 fs-7 text-end">{userName}</p>
        </div>
        <span>-----------------------------</span>

        {invoice.link_order.link_order_item.map((data, index) => (
          <div key={index}>
            <p className="mb-0 fs-7 w-75"><b>{data.link_item.nama ?? null}</b></p>
            <div className="row">
              <div className="col">
                <p className="mb-0 fs-7">{data.kuantitas ?? null} x</p>
              </div>
              {data.harga_satuan && <div className="col">
                <p className="mb-0 fs-7">@{(data.harga_satuan).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</p>
              </div>}
              {data.kuantitas && data.harga_satuan && <div className="col">
                <p className="mb-0 fs-7 text-end">{(data.kuantitas * data.harga_satuan).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</p>
              </div>}
            </div>
          </div>
        ))}
        <span>-----------------------------</span>

        {invoice.link_order.link_order_item.map((data) => {
          totalSementara += (data.kuantitas ?? 0) * (data.harga_satuan ?? 0)
        })}

        <div className="d-flex justify-content-between">
          <p className="mb-0 fs-7">Subtotal</p>
          {<p className="mb-0 fs-7">Rp {totalSementara.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</p>}
        </div>
        {invoice.harga_total && <div className="d-flex justify-content-between">
          <p className="mb-0 fs-7">Total Diskon</p>
          <p className="mb-0 fs-7">Rp {(totalSementara - invoice.harga_total).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</p>
        </div>}
        <span>-----------------------------</span>

        <div className="d-flex justify-content-between">
          <p className="mb-0 fs-7"><b>Total:</b></p>
          <p className="mb-0 fs-7">Rp {(invoice.harga_total ?? 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</p>
        </div>
        <span>-----------------------------</span>
      </div>
    );
  }
}

export default ComponentToPrint;
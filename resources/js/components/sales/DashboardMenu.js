import React from 'react';
import { splitCharacter } from '../reuse/HelperFunction';
import { Link } from 'react-router-dom';

const DashboardMenu = ({ handleShowModal }) => {

  return (
    <div className="page_container pt-4">
      <div className="word d-flex justify-content-center">
        {splitCharacter("salesman")}
      </div>

      <div className="object-movement">
        <div className="salesman"><span className="iconify fs-2" data-icon="emojione:person-walking-light-skin-tone"></span></div>
      </div>

      <h1 className='fs-6 fw-bold'>Menu untuk Salesman</h1>
      <button className='btn btn-primary btn-lg w-100' onClick={() => handleShowModal(false)}>
        <span className="iconify fs-4 me-2" data-icon="bx:trip"></span> Trip
      </button>
      <button className='btn btn-success btn-lg w-100 mt-4' onClick={() => handleShowModal(true)}>
        <span className="iconify fs-4 me-2" data-icon="carbon:ibm-watson-orders"></span> Order
      </button>
      <Link to="/salesman/reimbursement" className='btn btn-purple btn-lg w-100 mt-4'>
        <span className="iconify fs-3 me-2" data-icon="mdi:cash-sync"></span> Reimbursement
      </Link>

      <Link to="/salesman/historyinvoice" className='btn btn-danger btn-lg w-100 mt-4'>
        <span className="iconify fs-3 me-2" data-icon="fa-solid:file-invoice-dollar"></span> Riwayat Invoice
      </Link>

      <Link to="/lapangan/penagihan" className='btn btn-info btn-lg w-100 mt-4 text-white'>
        <span className="iconify fs-3 me-2 text-white" data-icon="uil:bill"></span> Penagihan
      </Link>

      <Link to='/lapangan/jadwal' className='btn btn-primary btn-lg w-100 mt-3'>
        <span className="iconify me-2" data-icon="fa-solid:shipping-fast"></span>
        Pengiriman
      </Link>

      <Link to='/salesman/itemkanvas' className='btn btn-success btn-lg w-100 mt-3'>
        <span className="iconify fs-3 me-2" data-icon="fluent:tray-item-remove-24-filled"></span>
        Item Kanvas
      </Link>
    </div>
  );
}

export default DashboardMenu;
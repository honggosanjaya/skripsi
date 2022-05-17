import React, { Component, useState, useEffect } from 'react';
import { splitCharacter } from '../reuse/HelperFunction';
import HeaderSales from './HeaderSales';

const DashboardSales = () => {
  return (
    <main className="page_main">
      <HeaderSales isDashboard={true} />
      <div className="page_container pt-4">
        <div className="word d-flex justify-content-center">
          {splitCharacter("salesman")}
        </div>
        <button className='btn btn-primary w-100' data-bs-toggle="modal" data-bs-target="#cariDataCustomer">Trip</button>
        <button className='btn btn-success w-100 mt-4' data-bs-toggle="modal" data-bs-target="#cariDataCustomer">Order</button>
      </div>

      {/* Modal */}
      <div className="modal fade modal_cariCust" id="cariDataCustomer" tabIndex="-1" aria-labelledby="cariDataCustomerLabel" aria-hidden="true">
        <div className="modal-dialog">
          <div className="modal-content">
            <div className="modal-header">
              <h5 className="modal-title" id="exampleModalLabel">Cari Customer</h5>
              <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div className="modal-body">
              <form>
                <div className="mb-3">
                  <label className="form-label">Nama Customer</label>
                  <input type="text" className="form-control" />
                </div>
                <div className="mb-3">
                  <label className="form-label">Alamat Utama</label>
                  <input type="text" className="form-control" />
                </div>
                <button type="submit" className="btn btn-primary">Search</button>
              </form>
            </div>

          </div>
        </div>
      </div>
    </main>
  );
}

export default DashboardSales;
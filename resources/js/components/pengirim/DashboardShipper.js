import React, { Component, useState, useEffect } from 'react';
import { splitCharacter } from '../reuse/HelperFunction';
import HeaderShipper from './HeaderShipper';

const DashboardShipper = () => {
  return (
    <main className="page_main">
      <HeaderShipper isDashboard={true} />
      <div className="page_container pt-4">
        <div className="word d-flex justify-content-center">
          {splitCharacter("shipper")}
        </div>
        <button className='btn btn-primary w-100'><span className="iconify" data-icon="fa-solid:shipping-fast"></span>Pengiriman</button>
      </div>
    </main>
  );
}

export default DashboardShipper;
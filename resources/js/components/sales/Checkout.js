import React, { Component } from 'react';
import Table from 'react-bootstrap/Table';

const Checkout = () => {
  return (
    <Table striped bordered hover className="mb-btnBottom">
      <thead>
        <tr>
          <th>keterangan</th>
          <th>jumlah</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Total</td>
          <td>{totalHarga}</td>
        </tr>
        <tr>
          <td>potongan {dataCustType.nama ?? null}</td>
          <td> - {totalHarga * (dataCustType.diskon ?? 0) / 100}</td>
        </tr>
        <tr>
          <td>event <span></span></td>
          <td>- {hargaPromo}</td>
        </tr>
        <tr>
          <td>total akhir<span></span></td>
          <td>{totalHarga - (totalHarga * (dataCustType.diskon ?? 0) / 100) - hargaPromo}</td>
        </tr>
      </tbody>
    </Table>
  );
}

export default Checkout;
import React, { Component } from 'react';
import { Button, Modal } from 'react-bootstrap';

const KeluarToko = ({ handleShow, alasanPenolakan, setAlasanPenolakan, handleClose, handleKeluarToko, show }) => {
  return (
    <div className="my-5 d-flex justify-content-between align-items-center">
      <h1 className="fs-6 fw-bold"> Customer tidak jadi pesan ?</h1>
      <Button variant="danger" onClick={handleShow}>
        Keluar
      </Button>

      <Modal show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>Keluar Toko</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <label className="form-label">Alasan Penolakan</label>
          <input type="text" className="form-control"
            value={alasanPenolakan || ''}
            onChange={(e) => setAlasanPenolakan(e.target.value)}
          />
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={handleClose}>
            Batal
          </Button>
          <Button variant="danger" onClick={handleKeluarToko}>
            Keluar
          </Button>
        </Modal.Footer>
      </Modal>
    </div>
  );
}

export default KeluarToko;
import React, { Component } from 'react';
import { Modal } from 'react-bootstrap';

const FilterItem = ({ showFilter, handleCloseFilter, filterBy, handleFilterChange }) => {
  return (
    <Modal show={showFilter} onHide={handleCloseFilter}>
      <Modal.Header closeButton>
        <Modal.Title>Filter Produk</Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <h1 className="fs-6 fw-bold">Urutkan</h1>
        <label>
          <input type="checkbox" className="d-none"
            checked={filterBy == 'hargaterendah' ? true : false}
            onChange={() => handleFilterChange('hargaterendah')}
          />
          <span className={`btn ms-1 ${filterBy == 'hargaterendah' ? 'btn-secondary' : 'btn-outline-secondary'}`}>Harga Terendah</span>
        </label>

        <label>
          <input type="checkbox" className="d-none"
            checked={filterBy == 'hargatertinggi' ? true : false}
            onChange={() => handleFilterChange('hargatertinggi')}
          />
          <span className={`btn ms-1 ${filterBy == 'hargatertinggi' ? 'btn-secondary' : 'btn-outline-secondary'}`}>Harga Tertinggi</span>
        </label>

        <label>
          <input type="checkbox" className="d-none"
            checked={filterBy == 'namaasc' ? true : false}
            onChange={() => handleFilterChange('namaasc')}
          />
          <span className={`btn ms-1 ${filterBy == 'namaasc' ? 'btn-secondary' : 'btn-outline-secondary'}`}>A-Z</span>
        </label>

        <label>
          <input type="checkbox" className="d-none"
            checked={filterBy == 'namadsc' ? true : false}
            onChange={() => handleFilterChange('namadsc')}
          />
          <span className={`btn ms-1 ${filterBy == 'namadsc' ? 'btn-secondary' : 'btn-outline-secondary'}`}>Z-A</span>
        </label>

        <label>
          <input type="checkbox" className="d-none"
            checked={filterBy == null ? true : false}
            onChange={() => handleFilterChange(null)}
          />
          <span className={`btn ms-1 ${filterBy == null ? 'btn-secondary' : 'btn-outline-secondary'}`}>Tanpa Filter</span>
        </label>
      </Modal.Body>
    </Modal>
  );
}

export default FilterItem;
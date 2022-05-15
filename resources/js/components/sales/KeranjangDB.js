import { openDB } from 'idb';

const DATABASE_NAME = 'keranjang-database';
const DATABASE_VERSION = 1;
const OBJECT_STORE_NAME = 'produks';

const dbPromise = openDB(DATABASE_NAME, DATABASE_VERSION, {
  upgrade(database) {
    database.createObjectStore(OBJECT_STORE_NAME, { keyPath: 'id' });
  },
});

const KeranjangDB = {
  async getProduk(id) {
    return (await dbPromise).get(OBJECT_STORE_NAME, id);
  },
  async getAllProduks() {
    return (await dbPromise).getAll(OBJECT_STORE_NAME);
  },
  async putProduk(produk) {
    return (await dbPromise).add(OBJECT_STORE_NAME, produk);
  },
  async updateProduk(produk) {
    return (await dbPromise).put(OBJECT_STORE_NAME, produk);
  },
  async deleteProduk(id) {
    return (await dbPromise).delete(OBJECT_STORE_NAME, id);
  },
};

export default KeranjangDB;
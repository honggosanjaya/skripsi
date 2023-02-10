import { openDB } from 'idb';

const DATABASE_NAME = 'group-item-database';
const DATABASE_VERSION = 3;
const OBJECT_STORE_NAME = 'groupItems';

const dbPromise = openDB(DATABASE_NAME, DATABASE_VERSION, {
  upgrade(database) {
    database.createObjectStore(OBJECT_STORE_NAME, { keyPath: 'id' });
  },
});

const GroupItemDB = {
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

export default GroupItemDB;
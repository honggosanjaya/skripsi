export function convertPrice(price) {
  let convertedPrice = 'Rp. ' + price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  return convertedPrice;
}
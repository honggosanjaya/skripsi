export function convertPrice(price) {
  let convertedPrice = 'Rp. ' + price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  return convertedPrice;
}

export function splitCharacter(string) {
  let myArr = [];
  string.split("").map((char, index) => {
    myArr[index] = <span key={index}>{char}</span>
  });
  return myArr;
}

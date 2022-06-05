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

export function convertDate(datetime) {
  let date = datetime.split(' ');
  let parts = date[0].split('-');
  let jam = date[1];
  let tahun = parts[0];
  let month = parts[1];
  let tanggal = parts[2];

  const monthName = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

  return `${tanggal} ${monthName[month - 1]} ${tahun} pukul ${jam} WIB`;
}
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

  const monthName = ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];

  return `${tanggal} ${monthName[month - 1]} ${tahun}` + '\n' + `pukul ${jam} WIB`;
}

export function dataURLtoFile(dataurl, filename) {
  var arr = dataurl.split(","),
    mime = arr[0].match(/:(.*?);/)[1],
    bstr = atob(arr[1]),
    n = bstr.length,
    u8arr = new Uint8Array(n);

  while (n--) {
    u8arr[n] = bstr.charCodeAt(n);
  }

  return new File([u8arr], filename, { type: mime });
}



export function getTime(date) {
  const myArray = date.split(" ");
  return myArray[1];
}

var kinerjaSalesChart = document.getElementById("kinerjaSalesChart");

var dataKinerja = {
  labels: ["Kunjungan", "Effective Call", "Total Pesanan"],
  datasets: [{
    label: "Sales A",
    backgroundColor: "rgba(200,0,0,0.2)",
    data: [650, 750, 700]
  }, {
    label: "Sales B",
    backgroundColor: "rgba(0,0,200,0.2)",
    data: [54, 65, 60]
  }]
};

var radarChart = new Chart(kinerjaSalesChart, {
  type: 'radar',
  data: dataKinerja
});


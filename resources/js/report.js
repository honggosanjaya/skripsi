// const moment = require("moment");

if($('#report').length>0){

  // window.location.hash = window.location.href.split('?')[0]
  var kinerjaSalesChart = document.getElementById("kinerjaSalesChart");

  var labels = $('#kinerjaSalesChart').data('label');
  var data = {
    labels: labels,
    datasets: [{
      data: $('#kinerjaSalesChart').data('value'),
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        'rgba(255, 205, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(201, 203, 207, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(201, 203, 207, 0.2)'
      ],
      borderColor: [
        'rgb(255, 99, 132)',
        'rgb(255, 159, 64)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(54, 162, 235)',
        'rgb(153, 102, 255)',
        'rgb(201, 203, 207)',
        'rgb(54, 162, 235)',
        'rgb(153, 102, 255)',
        'rgb(201, 203, 207)'
      ],
      borderWidth: 1
    }], 
  };

  const config = new Chart(kinerjaSalesChart,{
    type: 'bar',
    data: data,
    options: {  
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        xAxis: {
          ticks: {
            maxRotation: 90,
            labelOffset:-6,
            padding:0,
          }
        }
      },
      plugins: {
        title: {
            display: true,
            text: 'Produk Terlaris',
        }, 
        legend: {
          display: false
        }
      }
    },
  });

  $( "#report [name=month]" ).on( "change", function() {
    var dateEnd = new Date( $( "#report [name=year]" ).val(), $( "#report [name=month]" ).val(), 0);
    var dateStart = new Date( $( "#report [name=year]" ).val(), $( "#report [name=month]" ).val(), 1);

    let year = dateEnd.getFullYear();
    let month = String(dateEnd.getMonth() + 1).padStart(2, '0');
    let dayEnd = String(dateEnd.getDate()).padStart(2, '0');
    let dayStart = String(dateStart.getDate()).padStart(2, '0');

    dateEnd = [year, month, dayEnd].join('-');
    dateStart = [year, month, dayStart].join('-');

    $( "#report [name=dateStart]" ).val(dateStart)
    $( "#report [name=dateEnd]" ).val(dateEnd)
  });
  $( "#report [name=year]" ).on( "change", function() {
    var dateEnd = new Date( $( "#report [name=year]" ).val(), $( "#report [name=month]" ).val(), 0);
    var dateStart = new Date( $( "#report [name=year]" ).val(), $( "#report [name=month]" ).val(), 1);

    let year = dateEnd.getFullYear();
    let month = String(dateEnd.getMonth() + 1).padStart(2, '0');
    let dayEnd = String(dateEnd.getDate()).padStart(2, '0');
    let dayStart = String(dateStart.getDate()).padStart(2, '0');

    dateEnd = [year, month, dayEnd].join('-');
    dateStart = [year, month, dayStart].join('-');

    $( "#report [name=dateStart]" ).val(dateStart)
    $( "#report [name=dateEnd]" ).val(dateEnd)

  });

  $( "#report [name=dateEnd]" ).on( "change", function() {
    console.log( $(this).val() );
  });

  $(document).on('change', '[name="dateStart"]', function () {
    if ($(this).val() > $('[name="dateEnd"]').val()) {
      $('[name="dateEnd"]').val($(this).val())
    }
    $('[name="dateEnd"]').attr("min", $(this).val());
  });

}



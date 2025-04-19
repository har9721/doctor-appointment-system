// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.font.family = 'Nunito, -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
Chart.defaults.color = '#858796';

// Pie Chart Example
$.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
$.ajax({
    type: "get",
    url: myPieChart,
    data: {},
    success : function(response){


    if(response)
    {
      const specialty_count = response.map(element => {
        return element.total_appointments ? element.total_appointments : 0;
      });

      const name = response.map(element => {
        return element.name ? element.name : null;
      });

      const total = specialty_count.reduce((acc, val) => acc + val, 0);

      var ctx = document.getElementById("myPieChart");
      var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels:name,
          datasets: [{
            data: specialty_count,
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
          }],
        },
        options: {
          maintainAspectRatio: false,
          responsive: true,
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
          },
          plugins: {
            tooltip: {
              callbacks: {
                label: function(context) {
                  const value = context.parsed;
                  const percentage = ((value / total) * 100).toFixed(2);
                  return `${context.label}: ${value} (${percentage}%)`;
                }
              }
            }
          },
          legend: {
            display: false,
            position : 'bottom',
            labels: {
              usePointStyle: true,
            }
          },
          cutoutPercentage: 80,
        },
      });
    }
  }
});

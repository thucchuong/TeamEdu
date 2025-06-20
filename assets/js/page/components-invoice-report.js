"use strict";
var chart = null;
$(document).ready(function () {
  get_invoice();
});
function get_invoice() {
  var month = $("#monthPicker").val();
  var client_id = $("#client_id").val();
  $.ajax({
    type: "GET",
    url: base_url + "report/filter_by_income_invoice",
    data: { month: month, client_id: client_id },
    dataType: 'json',
    success: function (data) {
      var data_count = data["data"];
      var ctx3 = document.getElementById('income-invoices-chart').getContext('2d');
      chart = new Chart(ctx3, {
        type: 'bar',
        data: {
          labels: [label_fully_paid, label_partially_paid, label_draft, label_cancelled, label_due],
          datasets: [{
            label: label_income_invoices,
            backgroundColor: [
              'rgba(255, 99, 132, 0.6)',
              'rgba(255, 159, 64, 0.6)',
              'rgba(75, 192, 192, 0.6)',
              'rgba(54, 162, 235, 0.6)',
              'rgba(153, 102, 255, 0.6)'
            ],
            borderColor: [
              'rgb(255, 99, 132)',
              'rgb(255, 159, 64)',
              'rgb(75, 192, 192)',
              'rgb(54, 162, 235)',
              'rgb(153, 102, 255)'
            ],
            borderRadius: 5,
            borderWidth: 2,
            pointBackgroundColor: '#ffffff',
            pointRadius: 4,
            data: data_count
          }]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true,
              }
            }],
          },
        }
      });
    },
    error: function (error) {
      console.log("Error:", error);
    }
  });
}

$(document).ready(function () {

  $("#applyFilter").on("click", function () {
    if (chart) {
      chart.destroy();
    }
    get_invoice();
  });
});
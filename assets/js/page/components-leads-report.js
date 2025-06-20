"use strict";

var chart_status = null;
$(document).ready(function () {
  get_status();
});
function get_status() {
  var year = $("#yearPicker").val();
  $.ajax({
    type: "GET",
    url: base_url + "report/filter_by_lead_year_status",
    data: { year: year },
    dataType: 'json',
    success: function (data) {
      var labels = data.labels;
      var data_count = data.data;
      var ctx3 = document.getElementById('leads-status-chart').getContext('2d');
      chart_status = new Chart(ctx3, {
        type: 'doughnut',
        data: {
          labels: labels,
          datasets: [{
            label: label_lead_status,
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
            borderWidth: 1,
            pointBackgroundColor: '#ffffff',
            pointRadius: 4,
            data: data_count
          }]
        },
      });
    },
    error: function (error) {
      console.log("Error:", error);
    }
  });
}
$(document).ready(function () {

  $("#leadstatusFilter").on("click", function () {
    if (chart_status) {
      chart_status.destroy();
    }
    get_status();
  });
});

var chart = null;
$(document).ready(function () {
  get_lead();
});
function get_lead() {
  var month = $("#monthPicker").val();
  var user_id = $("#user_id").val();
  $.ajax({
    type: "GET",
    url: base_url + "report/filter_by_lead_assigned_month",
    data: { month: month, user_id: user_id },
    dataType: 'json',
    success: function (data) {
      var labels = data['labels'];
      var data_count = data["data"];
      if (labels.length === 0 || data.length === 0) {
        labels = ['No Data Found'];
        data = [0];
      }
      var ctx3 = document.getElementById('lead-start-month-chart').getContext('2d');
      chart = new Chart(ctx3, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: label_leads_start_month,
            backgroundColor: [
              'rgba(255, 99, 132, 0.7)',
              'rgba(255, 159, 64, 0.7)',
              'rgba(54, 162, 235, 0.7)',
              'rgba(75, 192, 192, 0.7)',
              'rgba(255, 99, 132, 0.7)',
              'rgba(255, 159, 64, 0.7)',
              'rgba(54, 162, 235, 0.7)',
              'rgba(75, 192, 192, 0.7)',
              'rgba(255, 99, 132, 0.7)',
              'rgba(255, 159, 64, 0.7)',
              'rgba(54, 162, 235, 0.7)',
              'rgba(75, 192, 192, 0.7)',
            ],
            borderColor: [
              'rgb(255, 99, 132)',
              'rgb(255, 159, 64)',
              'rgb(54, 162, 235)',
              'rgb(75, 192, 192)',
              'rgb(255, 99, 132)',
              'rgb(255, 159, 64)',
              'rgb(54, 162, 235)',
              'rgb(75, 192, 192)',
              'rgb(255, 99, 132)',
              'rgb(255, 159, 64)',
              'rgb(54, 162, 235)',
              'rgb(75, 192, 192)',
            ],
            borderRadius: 5,
            borderWidth: 1,
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
                  stepSize: 1
              }
          }],
        }
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
    get_lead();
  });
});


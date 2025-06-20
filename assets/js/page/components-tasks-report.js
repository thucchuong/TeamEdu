"use strict";

var chart_status = null;
$(document).ready(function () {
  get_status();
});
function get_status() {
  var year = $("#yearPicker").val();
  $.ajax({
    type: "GET",
    url: base_url + "report/filter_by_task_year_status",
    data: { year: year },
    dataType: 'json',
    success: function (data) {
      var labels = data.labels;
      var data_count = data.data;
      var ctx3 = document.getElementById('tasks-status-chart').getContext('2d');
      chart_status = new Chart(ctx3, {
        type: 'doughnut',
        data: {
          labels: labels,
          datasets: [{
            label: label_tasks_status,
            backgroundColor: [
              'rgba(255, 99, 132, 0.7)',
              'rgba(255, 159, 64, 0.7)',
              'rgba(54, 162, 235, 0.7)',
              'rgba(75, 192, 192, 0.7)'
            ],
            borderColor: [
              'rgb(255, 99, 132)',
              'rgb(255, 159, 64)',
              'rgb(54, 162, 235)',
              'rgb(75, 192, 192)'
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

  $("#statusFilter").on("click", function () {
    if (chart_status) {
      chart_status.destroy();
    }
    get_status();
  });
});
var chart = null;
$(document).ready(function () {
  get_task();
});
function get_task() {
  var month = $("#monthPicker").val();
  var user_id = $("#user_id").val();
  $.ajax({
    type: "GET",
    url: base_url + "report/filter_by_task_month",
    data: { month: month, user_id: user_id },
    dataType: 'json',
    success: function (data) {
      var labels = data['labels'];
      var data_count = data["data"];
      if (labels.length === 0 || data.length === 0) {
        labels = ['No Data Found'];
        data = [0];
      }
      var ctx3 = document.getElementById('task-start-month-chart').getContext('2d');
      chart = new Chart(ctx3, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: label_task_start_month_year,
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
    get_task();
  });
});
var chart_data = null;
$(document).ready(function () {
  get_end_task();
});
function get_end_task() {
  var month = $("#monthPicker1").val();
  var user_id = $("#user_id1").val();
  $.ajax({
    type: "GET",
    url: base_url + "report/filter_by_task_end_month",
    data: { month: month, user_id: user_id },
    dataType: 'json',
    success: function (data) {
      var labels = data['labels'];
      var data_count = data["data"];
      if (labels.length === 0 || data.length === 0) {
        labels = ['No Data Found'];
        data = [0];
      }
      var ctx4 = document.getElementById('task-end-month-chart').getContext('2d');
      chart_data = new Chart(ctx4, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: label_task_end_month_year,
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

  $("#endFilter").on("click", function () {
    if (chart_data) {
      chart_data.destroy();
    }
    get_end_task();
  });
});

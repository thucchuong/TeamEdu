"use strict";
var chart_status = null;
$(document).ready(function () {
  get_status();
});
function get_status() {
  var year = $("#yearPicker").val();
  $.ajax({
    type: "GET",
    url: base_url + "report/filter_by_project_year_status",
    data: { year: year },
    dataType: 'json',
    success: function (data) {
      var labels = data.labels;
      var data_count = data.data;
      var ctx3 = document.getElementById('project-status-chart').getContext('2d');
      chart_status = new Chart(ctx3, {
        type: 'doughnut',
        data: {
          labels: labels,
          datasets: [{
            label: label_projects_status,
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

var chart_priority = null;
$(document).ready(function () {
  get_priority();
});
function get_priority() {
  var year = $("#yearPicker1").val();
  $.ajax({
    type: "GET",
    url: base_url + "report/filter_by_project_year_priority",
    data: { year: year },
    dataType: 'json',
    success: function (data) {
      var labels = data.labels;
      var data_count = data.data;
      var ctx3 = document.getElementById('project-priority-chart').getContext('2d');
      chart_priority = new Chart(ctx3, {
        type: 'polarArea',
        data: {
          labels: labels,
          datasets: [{
            label: label_projects_priority,
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

  $("#priorityFilter").on("click", function () {
    if (chart_priority) {
      chart_priority.destroy();
    }
    get_priority();
  });
});

var chart = null;
$(document).ready(function () {
  get_project();
});
function get_project() {
  var month = $("#monthPicker").val();
  var user_id = $("#user_id").val();
  var client_id = $("#client_id").val();
//  var year = $("#yearmonthPicker").val();
  $.ajax({
    type: "GET",
    url: base_url + "report/filter_by_month",
    data: { month: month, user_id: user_id, client_id: client_id },
    dataType: 'json',
    success: function (data) {
      var labels = data['labels'];
      var data_count = data["data"];
      if (labels.length === 0 || data.length === 0) {
        labels = ['No Data Found'];
        data = [0];
      }
      var ctx3 = document.getElementById('project-start-month-chart').getContext('2d');
      chart = new Chart(ctx3, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: label_project_start_month_year,
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
    get_project();
  });
});

var chart_data = null;
$(document).ready(function () {
  get_end_project();
});
function get_end_project() {
  var month = $("#monthPicker1").val();
  var user_id = $("#user_id1").val();
  var client_id = $("#client_id1").val();
  $.ajax({
    type: "GET",
    url: base_url + "report/filter_by_end_month",
    data: { month: month, user_id: user_id, client_id: client_id },
    dataType: 'json',
    success: function (data) {
      var labels = data['labels'];
      var data_count = data["data"];

      if (labels.length === 0 || data.length === 0) {
        labels = ['No Data Found'];
        data = [0];
      }
      var ctx4 = document.getElementById('project-end-month-chart').getContext('2d');
      chart_data = new Chart(ctx4, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: label_project_end_month_year,
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
    get_end_project();
  });
});
var yearSelect = document.getElementById('yearPicker');
var currentYear = new Date().getFullYear();
var startYear = 2000;
var endYear = 2099;

for (var year = startYear; year <= endYear; year++) {
  var option = document.createElement('option');
  option.value = year;
  option.text = year;

  // Set the 'selected' attribute if the year is the current year
  if (year === currentYear) {
    option.selected = true;
  }

  yearSelect.appendChild(option);
}

var yearSelect1 = document.getElementById('yearPicker1');
var currentYear = new Date().getFullYear();
var startYear = 2000;
var endYear = 2099;

for (var year = startYear; year <= endYear; year++) {
  var option = document.createElement('option');
  option.value = year;
  option.text = year;

  // Set the 'selected' attribute if the year is the current year
  if (year === currentYear) {
    option.selected = true;
  }

  yearSelect1.appendChild(option);
}

var yearSelect2 = document.getElementById('yearmonthPicker');
var currentYear = new Date().getFullYear();
var startYear = 2000;
var endYear = 2099;

for (var year = startYear; year <= endYear; year++) {
  var option = document.createElement('option');
  option.value = year;
  option.text = year;

  // Set the 'selected' attribute if the year is the current year
  if (year === currentYear) {
    option.selected = true;
  }

  yearSelect2.appendChild(option);
}

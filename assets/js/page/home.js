"use strict";
$(document).ready(function () {
  get_status();
});
function get_status() {
  $.ajax({
    type: "GET",
    url: base_url + "home/filter_by_project_status",
    dataType: 'json',
    success: function (data) {             
      var labels = data.labels;
      var data_count = data.data;
      var ctx3 = document.getElementById('project-status-chart').getContext('2d');
      var projectmyChart = new Chart(ctx3, {
        type: 'bar',
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
            borderWidth: 2,
            pointBackgroundColor: '#ffffff',
            pointRadius: 4,
            data: data_count
          }]
        },
        options: {
              cornerRadius: 50,
              legend: {
                display: false
              },
              scales: {
                yAxes: [{
                  gridLines: {
                    drawBorder: false,
                    color: '#f2f2f2',
                  },
                  ticks: {
                    beginAtZero: true,
                  }
                }],
                xAxes: [{
                  gridLines: {
                    display: false
                  }
                }]
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
  get_task_status();
});
function get_task_status() {
  $.ajax({
    type: "GET",
    url: base_url + "home/filter_by_task_status",
    dataType: 'json',
    success: function (data) {
      var labels = data.labels;
      var data_count = data.data;
      var ctx3 = document.getElementById('tasks-status-chart').getContext('2d');
      var tasksmyChart = new Chart(ctx3, {
        type: 'bar',
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
        options: {
          cornerRadius: 50,
          legend: {
            display: false
          },
          scales: {
            yAxes: [{
              gridLines: {
                drawBorder: false,
                color: '#f2f2f2',
              },
              ticks: {
                beginAtZero: true,
              }
            }],
            xAxes: [{
              gridLines: {
                display: false
              }
            }]
          },
        }
      });
    },
    error: function (error) {
      console.log("Error:", error);
    }
  });
}

function queryParams(p) {

  var from = $('#tasks_start_date').val();
  var to = $('#tasks_end_date').val();
  if (from !== '' && to !== '') {
    from = moment(from).format('YYYY-MM-DD');
    to = moment(to).format('YYYY-MM-DD');
    // console.log(from +'-'+ to);
  }
  return {
    "project": $('#projects_name').val(),
    "status": $('#tasks_status').val(),
    "from": from,
    "to": to,
    "workspace_id": home_workspace_id,
    "user_id": home_user_id,
    "is_admin": home_is_super_admin,
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search
  };
}

$('#fillter-tasks').on('click', function (e) {
  e.preventDefault();
  $('#tasks_list').bootstrapTable('refresh');
});

$(function () {

  $('#tasks_between').daterangepicker({
    showDropdowns: true,
    alwaysShowCalendars: true,
    autoUpdateInput: false,
    ranges: {
      'Today': [moment(), moment()],
      'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days': [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month': [moment().startOf('month'), moment().endOf('month')],
      'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate:moment().subtract(29, 'days'),
    endDate:moment(),
    locale: {
      "format": "DD/MM/YYYY",
      "separator": " - ",
      "cancelLabel": 'Clear'
    }
  });

  $('#tasks_between').on('apply.daterangepicker', function (ev, picker) {
    $('#tasks_start_date').val(picker.startDate.format('MM/DD/YYYY'));
    $('#tasks_end_date').val(picker.endDate.format('MM/DD/YYYY'));
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });

  $('#tasks_between').on('cancel.daterangepicker', function (ev, picker) {
    $(this).val('');
    $('#tasks_start_date').val('');
    $('#tasks_end_date').val('');
  });

});

$(".users-carousel").owlCarousel({
  items: 4,
  margin: 20,
  autoplay: true,
  autoplayTimeout: 5000,
  loop: false,
  responsive: {
    0: {
      items: 2
    },
    578: {
      items: 4
    },
    768: {
      items: 4
    }
  }
});
$(".users-carousel1").owlCarousel({
  items: 4,
  margin: 20,
  autoplay: true,
  autoplayTimeout: 5000,
  loop: false,
  responsive: {
    0: {
      items: 2
    },
    578: {
      items: 4
    },
    768: {
      items: 4
    }
  }
});
$(".users-carousel2").owlCarousel({
  items: 4,
  margin: 20,
  autoplay: true,
  autoplayTimeout: 5000,
  loop: false,
  responsive: {
    0: {
      items: 2
    },
    578: {
      items: 4
    },
    768: {
      items: 4
    }
  }
});
$(".users-carousel3").owlCarousel({
  items: 4,
  margin: 20,
  autoplay: true,
  autoplayTimeout: 5000,
  loop: false,
  responsive: {
    0: {
      items: 2
    },
    578: {
      items: 4
    },
    768: {
      items: 4
    }
  }
});$(".users-carousel4").owlCarousel({
  items: 4,
  margin: 20,
  autoplay: true,
  autoplayTimeout: 5000,
  loop: false,
  responsive: {
    0: {
      items: 2
    },
    578: {
      items: 4
    },
    768: {
      items: 4
    }
  }
});
$(".users-carousel5").owlCarousel({
  items: 4,
  margin: 20,
  autoplay: true,
  autoplayTimeout: 5000,
  loop: false,
  responsive: {
    0: {
      items: 2
    },
    578: {
      items: 4
    },
    768: {
      items: 4
    }
  }
});
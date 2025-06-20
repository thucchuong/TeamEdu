"use strict";

$(document).ready(function () {
    get_task_status();
});
function get_task_status() {
    var id = $('#project_id_data').val();
    $.ajax({
        type: "GET",
        data: "project_id=" + id,
        url: base_url + "projects/filter_by_task_status",
        dataType: 'json',
        success: function (data) {
            var labels = data.labels;
            var data_count = data.data;
            var ctx3 = document.getElementById('task-area-chart').getContext('2d');
            var tasksmyChart = new Chart(ctx3, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label_tasks,
                        backgroundColor: "rgb(255, 203, 174)",
                        borderColor: "#f16c20",
                        data: data_count
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{ reverse: !0, gridLines: { color: "rgba(0,0,0,0.05)" } }],
                        yAxes: [{
                            ticks: { stepSize: 10, display: !1 },
                            min: 10,
                            max: 100,
                            display: !0,
                            borderDash: [5, 5],
                            gridLines: { color: "rgba(0,0,0,0)", fontColor: "#fff" }
                        }]
                    },
                    responsive: true,
                    title: {
                        display: false,
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    legend: {
                        display: false
                    }
                }
            });
        },
        error: function (error) {
            console.log("Error:", error);
        }
    });
}


Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("#project-files-dropzone", {
    url: base_url + 'projects/add_project_file',
    autoProcessQueue: true,
    timeout: 100000,
    maxFilesize: 900000,
    dictDefaultMessage: dictDefaultMessage,
    addRemoveLinks: true,
    maxFiles: 2,
    dictMaxFilesExceeded: 'Only 2 files are allow at once',
    dictRemoveFile: 'x',

});

myDropzone.on("addedfile", function (file) {
    var i = 0;
    if (this.files.length) {
        var _i, _len;
        for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) // -1 to exclude current file
        {
            if (this.files[_i].name === file.name && this.files[_i].size === file.size && this.files[_i].lastModifiedDate.toString() === file.lastModifiedDate.toString()) {
                this.removeFile(file);
            } else if (this.files[4] != null) {
                this.removeFile(file);

            }
            i++;
        }
    }
});

myDropzone.on('sending', function (file, xhr, formData) {
    console.log('data' + JSON.stringify(formData));
    formData.append('workspace_id', jQuery('#workspace_id').val());
    formData.append('project_id', jQuery('#project_id_data').val());
    formData.append(csrfName, csrfHash);
});

myDropzone.on("queuecomplete", function (file) {
    location.reload();
});

function queryParams(p) {
    return {
        "project_id": $('#project_id_data').val(),
        "activity": $('#activity').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
$('#filter-activity').on('click', function (e) {
    e.preventDefault();
    $('#activity_log_list').bootstrapTable('refresh');
});
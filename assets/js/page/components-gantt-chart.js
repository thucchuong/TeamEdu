$(document).ready(function () {
    get_project();
});

var titlesArray = [];
var Progress_percentage = '';
var project_title = '';
var task_title = '';
var startDate = '';
var endDate = '';
var project_id = '';
var task_data;
var task_id = '';

tasks = "Tasks";
days = "Days";

date_format_js = "DD-MMM-YYYY";
function get_project() {
    let form_data = {
        [csrfName]: csrfHash,
    };
    $.ajax({
        url: base_url + 'gantt_chart/get_project',
        type: "POST",
        data: form_data,
        beforeSend: function () { },
        success: function (result) {
            var data = JSON.parse(result);

            csrfName = data.csrfName;
            csrfHash = data.csrfHash;
            data.data.projects.forEach(el => {
                el.project_progress.forEach(progress => {
                    Progress_percentage = progress.percentage;
                });
                el.tasks.forEach(task => {
                    task_data = task;
                    task_id = task_data.id;
                    task_title = task_data.title;
                });

                project_id = el.id;
                project_title = el.title;
                startDate = new Date(el.start_date);
                endDate = new Date(el.end_date);
                var timeDifference = endDate - startDate;

                var daysDifference = Math.floor(timeDifference / (1000 * 60 * 60 * 24));

                if (task_data) {
                    var start_task = (task_data.start_date !== "0000-00-00") ? task_data.start_date : el.start_date;
                    var task_array = {
                        "id": task_data.id,
                        "name": "Task: " + task_data.title,
                        "status": task_data.status,
                        "start": start_task,
                        "end": task_data.due_date,
                        "progress": Progress_percentage,
                        "dependencies": task_data.project_id
                    };
                }

                titlesArray.push({
                    "id": project_id,
                    "name": "Project: " + project_title,
                    "status": el.status,
                    "start": startDate,
                    "end": endDate,
                    "progress": Progress_percentage,
                    "dependencies": ""
                }, task_array);

            });
            var tasks = titlesArray;
            if (tasks != '') {
                var gantt = new Gantt("#gantt", tasks, {
                    view_modes: ['Quarter Day', 'Half Day', 'Day', 'Week', 'Month'],
                    view_mode: 'Month',
                    popup_trigger: "mouseover",
                    date_format: date_format_js,
                    on_date_change: function (task, start, end) {
                        var start = moment(start, "YYYY-MM-DD");
                        var end = moment(end, "YYYY-MM-DD");
                        var start_date = start.format(date_format_js);
                        var end_date = end.format(date_format_js);
                        if (task.dependencies == '') {
                            $.ajax({
                                type: "POST",
                                url: base_url + 'gantt_chart/edit_project_gantt',
                                data: "update_id=" + task.id + "&starting_date=" + start_date + "&ending_date=" + end_date + "&csrf_token_name" + '=' + csrfHash,
                                dataType: "json",
                                success: function (result) { }
                            });
                        } else {
                            $.ajax({
                                type: "POST",
                                url: base_url + 'gantt_chart/edit_task_gantt',
                                data: "update_id=" + task.id + "&starting_date=" + start_date + "&ending_date=" + end_date + "&csrf_token_name" + '=' + csrfHash,
                                dataType: "json",
                                success: function (result) { }
                            });
                        }

                    },
                    on_click: function (task) {
                        var id = task.id;
                        if (task.dependencies == '') {
                            window.open(base_url + 'projects/details/' + id, '_blank');
                        } else {
                            window.open(base_url + 'projects/tasks/' + task.dependencies, '_blank');
                        }
                    },
                    custom_popup_html: function (task) {
                        var start = moment(task.start, "YYYY-MM-DD");
                        var end = moment(task.end, "YYYY-MM-DD");
                        var start_date = start.format(date_format_js);
                        var end_date = end.format(date_format_js);
                        var day_count = Math.abs(start.startOf('day').diff(end.startOf('day'), 'days')) + 1;

                        if (day_count === 1) {
                            day_count = day_count + " Day";
                        } else {
                            day_count = day_count + " Days";
                        }

                        return `
                          <div style="width: 240px;">
                            <div class="title"><strong>${task.name}</strong></div>
                            <div class="subtitle">
                              <strong>Starting Date:</strong> ${start_date}<br>
                              <strong>Ending Date:</strong> ${end_date}<br>
                              <strong>Status:</strong> ${task.status}<br>
                              <strong>Total:</strong> ${day_count}<br>
                            </div>
                          </div>
                        `;
                    }
                })

                $(document).on('change', '.gantt_filter', function (e) {
                    var value = $(this).val();
                    gantt.change_view_mode(value);
                });
            }

        },
        error: function (error) {
            console.log(error);
        },
    }).then(() => {

    });
}
// $(document).on('change', '.project_filter', function (e) {
//     e.preventDefault()
//     var projectId = $(this).val();
//     $.ajax({
//         type: "get",
//         url: base_url + "gantt_chart/get_project_by_id/" + projectId,
//         dataType: "json",
//         success: function (result) {
//             window.location.replace('gantt_chart/get_project_by_id/' + projectId); 
//         }
//     });

// });
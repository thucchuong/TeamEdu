"use strict";
var timer = "";
$("#message").on('change keyup paste', function () {
    localStorage.setItem("msg", $(this).val());
});
$("#project_id").on('change', function () {
    localStorage.setItem("project", $(this).val());
});
function open_timer_section() {
    if (is_timer_stopped) {
        $("#pause").attr("disabled", true);
        $("#end").attr("disabled", true);
    }
    $("#message").val(localStorage.getItem("msg"));
    $("#project_id").val(localStorage.getItem("project"));
    timerCycle();
}

var hr = parseInt($("#hour").length > 0 ? $("#hour").val() : 0);
var min = parseInt($("#minute").length > 0 ? $("#minute").val() : 0);
var sec = parseInt($("#second").length > 0 ? $("#second").val() : 0);
var is_timer_stopped = true;
let recorded_id = "00";
if (
    parseInt(localStorage.getItem("Pause")) == 1 &&
    parseInt(localStorage.getItem("recorded_id")) > 0
) {
    is_timer_stopped = true;
    time_tracker_img();
}

function startTimer() {
    if (is_timer_stopped == true) {
        if (localStorage.getItem("Seconds") > "00") {
            is_timer_stopped = false;
            timerCycle();
        } else {
            hr = "00";
            sec = "00";
            min = "00";
            is_timer_stopped = false;
            timerCycle();
            time_tracker_img();
        }
        $("#start").attr("disabled", true);
        time_tracker_img();
        $("#pause").attr("disabled", false);
        $("#end").attr("disabled", false);
        if (
            localStorage.getItem("Pause") == "0" ||
            localStorage.getItem("Pause") == "00"
        ) {
            localStorage.setItem("Pause", "1");
        }
        $('#message').val();
        $('#project_id').val();
        $.ajax({
            url: base_url + "time_tracker/add_start_time",
            dataType: "json",
            beforeSend: function () {
                $("#start").attr("disabled", true);
                time_tracker_img();
            },
            success: function (result) {
                csrfName = result["csrfName"];
                csrfHash = result["csrfHash"];
                console.log(result);
                if (result["error"] == false) {
                    recorded_id = result["record_id"];
                    localStorage.setItem("recorded_id", result["record_id"]);
                    localStorage.setItem("msg", $('#message').val());
                    localStorage.setItem("project", $('#project_id').val());
                } else {
                    form.stopProgress();
                    modal
                        .find(".modal-body")
                        .prepend(
                            '<div class="alert alert-danger">' + result["message"] + "</div>"
                        );
                    modal.find(".alert-danger").delay(4000).fadeOut();
                }
            },
        });
    }
}

function pauseTimer() {
    if (is_timer_stopped == false && $("#second").val() > "00") {
        is_timer_stopped = true;
        window.localStorage.setItem("Pause", "0");

        $("#start").attr("disabled", false);
        $("#pause").attr("disabled", true);
        $("#end").attr("disabled", true);
        timerCycle();
        time_tracker_img();
        var r_id = localStorage.getItem("recorded_id");
        var msg = $("#message").val();
        var project = $("#project_id").val();
        var input_body = {
            [csrfName]: csrfHash,
            record_id: r_id,
            'message': msg,
            'project_id': project,
        };
        $.ajax({
            type: "POST",
            data: input_body,
            url: base_url + "time_tracker/add_end_time",
            dataType: "json",
            success: function (result) {
                if (result["error"] == false) {
                    localStorage.setItem("msg", $('#message').val());
                    localStorage.setItem("project", $('#project_id').val());
                } else {
                    form.stopProgress();
                    modal
                        .find(".modal-body")
                        .prepend(
                            '<div class="alert alert-danger">' + result["message"] + "</div>"
                        );
                    modal.find(".alert-danger").delay(4000).fadeOut();
                }
            },
        });
        window.clearTimeout(timer);
    } else {
        swal({
            text: "Make sure that the timer has started",
            buttons: true,
        });
    }
}

if (
    window.performance.getEntriesByType("navigation")[0]["type"] == "reload" ||
    window.performance.getEntriesByType("navigation")[0]["type"] == "navigate"
) {
    hr = localStorage.getItem("Hour");
    sec = localStorage.getItem("Seconds");
    min = localStorage.getItem("Minutes");
    $("#hour").val("00");
    $("#minute").val("00");
    $("#second").val("00");
    if (hr) {
        $("#hour").val(hr);
    } else {
        $("#hour").val("00");
    }
    if (min) {
        $("#minute").val(min);
    } else {
        $("#minute").val("00");
    }
    if (sec) {
        $("#second").val(sec);
    } else {
        $("#second").val("00");
    }
    if (
        localStorage.getItem("Seconds") > "00" &&
        localStorage.getItem("Pause") != "0"
    ) {
        is_timer_stopped = false;
        timerCycle();
    }
}

function timerCycle() {
    if (is_timer_stopped == false) {
        $("#start").attr("disabled", true);
        time_tracker_img();
        sec = parseInt(sec);
        min = parseInt(min);
        hr = parseInt(hr);
        sec = sec + 1;

        if (sec == 60) {
            min = min + 1;
            sec = 0;
        }
        if (min == 60) {
            hr = hr + 1;
            min = 0;
            sec = 0;
        }

        if (sec < 10 || sec == 0) {
            sec = "0" + sec;
        } else {
            sec = "" + sec;
        }
        if (min < 10 || min == 0) {
            min = "0" + min;
        } else {
            min = "" + min;
        }
        if (hr < 10 || hr == 0) {
            hr = "0" + hr;
        } else {
            hr = "" + hr;
        }
        window.localStorage.setItem("Hour", hr);
        window.localStorage.setItem("Minutes", min);
        window.localStorage.setItem("Seconds", sec);
        $("#hour").val(hr);
        $("#minute").val(min);
        $("#second").val(sec);
        window.clearInterval(timer);
        timer = setTimeout("timerCycle()", 1000);
    }
}

function stopTimer() {
    if (is_timer_stopped == false && $("#second").val() > "00") {
        swal({
            title: "Stop",
            text: "Are you sure , you want to stop the timer",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then(() => {
            is_timer_stopped = true;
            localStorage.removeItem("Minutes");
            localStorage.removeItem("Seconds");
            localStorage.removeItem("Hour");
            localStorage.removeItem("msg");
            var r_id = localStorage.getItem("recorded_id");
            var msg = $('#message').val();
            var project = $('#project_id').val();
            $("#start").attr("disabled", false);
            $("#pause").attr("disabled", true);
            $("#end").attr("disabled", true);

            $("#hour").val("00");
            $("#minute").val("00");
            $("#second").val("00");
            $('#message').val(msg);
            $('#project_id').val(project);
            $('#project_id').trigger('change');
            time_tracker_img();
            var input_body = {
                [csrfName]: csrfHash,
                record_id: r_id,
                'message': msg,
                'project_id': project,
            };
            $.ajax({
                type: "POST",
                data: input_body,
                url: base_url + "time_tracker/add_end_time",
                dataType: "json",
                success: function (result) {
                    csrfName = result["csrfName"];
                    csrfHash = result["csrfHash"];
                    if (result["error"] == false) {
                        iziToast.success({
                            title: result['message'],
                            message: '',
                            position: 'topRight'
                        });
                    } else {
                        form.stopProgress();
                        modal
                            .find(".modal-body")
                            .prepend(
                                '<div class="alert alert-danger">' +
                                result["message"] +
                                "</div>"
                            );
                        modal.find(".alert-danger").delay(4000).fadeOut();
                    }
                },
            });
        });
        window.clearTimeout(timer);
    } else {
        swal({
            text: "Make sure that the timer has started",
            buttons: true,
        });
    }
    $("#start").click(function () {
        if (is_timer_stopped) {
            $("#hour").val("00");
            $("#minute").val("00");
            $("#second").val("00");
            hr = "00";
            sec = "00";
            min = "00";
            startTimer();
        }
    });
}

function fav(id) {
    var test = document.getElementById(id);
    test.classList.add("fas");
    $(test).removeClass("far");
    var input_body = {
        [csrfName]: csrfHash,
        project_id: id,
    };
    $.ajax({
        type: "POST",
        data: input_body,
        url: base_url + "projects/add_favorite",
        dataType: "json",
        success: function (result) {
            csrfName = result["csrfName"];
            csrfHash = result["csrfHash"];
            if (result["error"] == false) {
                location.reload();
            } else {
                modal
                    .find(".modal-body")
                    .prepend(
                        '<div class="alert alert-danger">' + result["message"] + "</div>"
                    );
                modal.find(".alert-danger").delay(4000).fadeOut();
            }
        },
    });
}

function un_fav(id) {
    var test = document.getElementById(id);
    if ($(test).hasClass("fas")) {
        $(test).removeClass("fas");
        var test = document.getElementById(id);
        test.classList.add("far");
        var input_body = {
            [csrfName]: csrfHash,
            project_id: id,
        };
        $.ajax({
            type: "POST",
            data: input_body,
            url: base_url + "projects/remove_favorite",
            dataType: "json",
            success: function (result) {
                csrfName = result["csrfName"];
                csrfHash = result["csrfHash"];
                if (result["error"] == false) {
                    location.reload();
                } else {
                    modal
                        .find(".modal-body")
                        .prepend(
                            '<div class="alert alert-danger">' + result["message"] + "</div>"
                        );
                    modal.find(".alert-danger").delay(4000).fadeOut();
                }
            },
        });
    } else {
        alert(
            "well you can not remove the this project from favorite list as it was not favored"
        );
    }
}

$("#modal-add-ticket-type").fireModal({
    size: "modal-md",
    title: "Add Ticket type",
    body: $("#modal-add-ticket-type-part"),
    footerClass: "bg-whitesmoke",
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result["csrfName"];
                csrfHash = result["csrfHash"];
                if (result["error"] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal
                        .find(".modal-body")
                        .prepend(
                            '<div class="alert alert-danger">' + result["message"] + "</div>"
                        );
                    modal.find(".alert-danger").delay(4000).fadeOut();
                }
            },
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        console.log(form);
    },
    buttons: [{
        text: "Add",
        submit: true,
        class: "btn btn-primary btn-shadow",
        id: "addunitbtn",
        handler: function (modal) { },
    },],
});
$(".modal-edit-ticket-type").fireModal({
    size: "modal-md",
    title: "Edit Ticket Type",
    body: $("#modal-edit-ticket-type-part"),
    footerClass: "bg-whitesmoke",
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result["csrfName"];
                csrfHash = result["csrfHash"];

                if (result["error"] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal
                        .find(".modal-body")
                        .prepend(
                            '<div class="alert alert-danger">' + result["message"] + "</div>"
                        );
                    modal.find(".alert-danger").delay(4000).fadeOut();
                }
            },
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: "Update",
        submit: true,
        class: "btn btn-primary btn-shadow",
        id: "updateunitbtn",
        handler: function (modal) { },
    },],
});
$("#modal-add-ticket").fireModal({
    size: "modal-lg",
    title: "Create Ticket",
    body: $("#modal-add-ticket-part"),
    footerClass: "bg-whitesmoke",
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result["csrfName"];
                csrfHash = result["csrfHash"];
                if (result["error"] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal
                        .find(".modal-body")
                        .prepend(
                            '<div class="alert alert-danger">' + result["message"] + "</div>"
                        );
                    modal.find(".alert-danger").delay(4000).fadeOut();
                }
            },
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        console.log(form);
    },
    buttons: [{
        text: "Create",
        submit: true,
        class: "btn btn-primary btn-shadow",
        id: "addunitbtn",
        handler: function (modal) { },
    },],
});
$(".modal-edit-ticket").fireModal({
    size: "modal-lg",
    title: "Edit Ticket",
    body: $("#modal-edit-ticket-part"),
    footerClass: "bg-whitesmoke",
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result["csrfName"];
                csrfHash = result["csrfHash"];
                if (result["error"] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal
                        .find(".modal-body")
                        .prepend(
                            '<div class="alert alert-danger">' + result["message"] + "</div>"
                        );
                    modal.find(".alert-danger").delay(4000).fadeOut();
                }
            },
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        console.log(form);
    },
    buttons: [{
        text: "Edit",
        submit: true,
        class: "btn btn-primary btn-shadow",
        id: "addunitbtn",
        handler: function (modal) { },
    },],
});
$(".modal-ticket-users").fireModal({
    size: "modal-lg",
    title: "Ticket Users",
    body: $("#modal-ticket-users-part"),
    footerClass: "bg-whitesmoke",
    autoFocus: false,
});
$(".modal-ticket-clients").fireModal({
    size: "modal-lg",
    title: "Ticket Clients",
    body: $("#modal-ticket-clients-part"),
    footerClass: "bg-whitesmoke",
    autoFocus: false,
});

function time_tracker_img() {
    if (!is_timer_stopped) {
        $("#timer-image").attr("src", base_url + "assets/img/94150-clock.gif");
    } else {
        $("#timer-image").attr("src", base_url + "assets/img/94150-clock.png");
    }
}

function time_tracker_query_params(p) {

    var from = $('#start_date').val();
    var to = $('#end_date').val();
    if (from !== '' && to !== '') {
        from = moment(from).format('YYYY-MM-DD');
        to = moment(to).format('YYYY-MM-DD');
    }
    return {
        "from": from,
        "to": to,
        "user_id": $('#user_id').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
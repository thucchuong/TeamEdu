"use strict";

$(document).on('click', '#modal-leave-editors-ajax', function () {
    $.ajax({
        type: "POST",
        url: base_url + 'leaves/get_leave_editor_by_id/',
        data: csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.leave_editors != '' && result.leave_editors != null) {
                var user_ids = result.leave_editors.split(',');
                $('#update_users').val(user_ids);
                $('#update_users').trigger('change');
            }
            $("#modal-leave-editors").trigger("click");
        }
    });
});

$(document).on('click', '.modal-edit-leaves-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + 'leaves/get_leave_by_id/',
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;

            $('#update_id').val(result.id);
            $('#edit_leave_days').val(result.leave_days);
            $('#edit_leaves_between').val(result.leave_from + ' / ' + result.leave_to);
            $('#edit_leave_from').val(result.leave_from);
            $('#edit_leave_to').val(result.leave_to);
            $('#edit_reason').val(result.reason);
            $("#modal-edit-leave").trigger("click");
        }
    });
});


$(document).on('click', '.no-edit-leaves-alert', function (e) {
    e.preventDefault();
    var url = $(this).attr("href");
    swal({
        title: 'Sorry...',
        text: 'Once action has been taken on leave request you can not edit leave request.',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {

        });
});

$(document).on('click', '.leave-action-alert', function (e) {
    e.preventDefault();
    var url = $(this).attr("href");
    swal({
        title: 'Are you sure?',
        text: 'You want to take action on this leave request?',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            }
        });
});

$('#loginpage').on('submit', function (e) {
    e.preventDefault();

    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        beforeSend: function () {
            $('#loginbtn').html('Please Wait..');
            $('#loginbtn').attr('disabled', true);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {

            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];

            if (result['error'] == false) {
                location.reload();
            } else {
                $('#login-result').html(result['message']);
                $('#login-result').removeClass("d-none").delay(6000).queue(function () {
                    $(this).addClass("d-none").dequeue();
                });
                $('#loginbtn').val('Login');
                $('#loginbtn').attr('disabled', false);
            }

            $('#loginbtn').html('Submit');

        }
    });

});

$('#forgot_password_form').on('submit', function (e) {
    e.preventDefault();

    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        beforeSend: function () {
            $('#loginbtn').val('Please Wait..');
            $('#loginbtn').attr('disabled', true);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {

            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];

            if (result['error'] == false) {

                $('#forgot_password_form').hide();
                $('#success-result').html(result['message']);
                $('#success-result').removeClass("d-none");
            } else {
                $('#forgot_password_form').show();
                $('#login-result').html(result['message']);
                $('#login-result').show().delay(6000).fadeOut();
                $('#login-result').removeClass("d-none").delay(6000).queue(function () {
                    $(this).addClass("d-none").dequeue();
                });
                $('#loginbtn').val('Forgot Password');
                $('#loginbtn').attr('disabled', false);
            }

        }
    });
});


$(document).on('click', '.delete-note-alert', function (e) {
    e.preventDefault();
    var note_id = $(this).data("note_id");
    swal({
        title: 'Are you sure?',
        text: 'Note Once deleted, you will not be able to recover that note!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'notes/delete/' + note_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '#modal-edit-note', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + "notes/get_note_by_id",
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {

            csrfName = result.csrfName;
            csrfHash = result.csrfHash;

            $('#delete_note').attr("data-note_id", id);

            $('#update_title').val(result.title);
            $('#update_class').val(result.class);
            $('#update_description').val(result.description);
            $('#update_id').val(result.id);
            $(".modal-edit-note").trigger("click");
        }
    });
});

var canvas = '';
var imageUrl = '';
var context = '';
var cropper = '';
$('#customFile').on('input', function () {
    var mime_type = document.getElementById('customFile').files[0].type;
    if (mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png') {
        canvas = $("#canvas");
        context = canvas.get(0).getContext("2d");
        if (this.files && this.files[0]) {
            // if (this.files[0].type.match(/^image\//)) {
            var reader = new FileReader();
            reader.onload = function (evt) {
                canvas.show();
                var img = new Image();
                img.onload = function () {
                    context.canvas.height = img.height;
                    context.canvas.width = img.width;
                    context.drawImage(img, 0, 0);
                    cropper = canvas.cropper({
                        aspectRatio: 1 / 1,
                        viewMode: 1,
                    });
                };
                img.src = null;
                img.src = evt.target.result;
            };
            reader.readAsDataURL(this.files[0]);
            // } else {
            //     console.log("Invalid file type! Please select an image file.");
            // }
        } else {
            console.log('No file(s) selected.');
        }
    } else {
        iziToast.error({
            title: 'Image type must jpg, jpeg or png',
            message: '',
            position: 'topRight'
        });
        // location.reload();
    }
});

$('#update_user_profile').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    if (canvas !== '') {
        imageUrl = canvas.cropper('getCroppedCanvas').toDataURL("image/jpeg");
        formData.append('profile', imageUrl);
    }
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {
            location.reload();
        }
    });
});


$(document).on('click', '.delete-file-alert', function (e) {
    e.preventDefault();
    var file_id = $(this).data("file_id");
    swal({
        title: 'Are you sure?',
        text: 'File Once deleted, you will not be able to recover that file!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'projects/delete_project_file/' + file_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});


$(document).on('click', '.delete-milestone-alert', function (e) {
    e.preventDefault();
    var milestone_id = $(this).data("milestone_id");
    var project_id = $(this).data("project_id");
    swal({
        title: 'Are you sure?',
        text: 'All Milestone Related Data Will Once deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'projects/delete_milestone/' + milestone_id + '/' + project_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.modal-edit-milestone-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + 'projects/get_milestone_by_id/',
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;

            $('#update_title').val(result.title);
            $('#update_cost').val(result.cost);
            $('#update_description').val(result.description);
            $('#update_id').val(result.id);
            $('#update_status').val(result.status);
            $('#update_status').trigger('change');
            $(".modal-edit-milestone").trigger("click");
        }
    });
});

$(document).on('click', '.delete-project-alert', function (e) {
    e.preventDefault();
    var project_id = $(this).data("project_id");
    swal({
        title: 'Are you sure?',
        text: 'All Project Related Data Will Once deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'projects/delete/' + project_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});


$(document).on('click', '.modal-edit-project-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + "projects/get_project_by_id",
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {

            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.user_id != '' && result.user_id != null) {
                var user_ids = result.user_id.split(',');
                $('#update_users').val(user_ids);
                $('#update_users').trigger('change');
            }
            if (result.client_id != '' && result.client_id != null) {
                var client_ids = result.client_id.split(',');
                $('#update_clients').val(client_ids);
                $('#update_clients').trigger('change');
            }
            $('#update_status').val(result.status);
            $('#update_status').trigger('change');
            $('#update_title').val(result.title);
            $('#update_priority').val(result.priority);
            $('#update_priority').trigger('change');
            $('#update_budget').val(result.budget);
            $('#update_description').val(result.description);
            $('#update_id').val(result.id);
            $('#update_end_date').val(result.end_date);
            $('#update_start_date').val(result.start_date);

            $(".modal-edit-project").trigger("click");
        }
    });
});



// $(document).on('click', '.modal-add-task-ajax', function () {
//     var id = $(this).data("id");

//     $('#task-track-id').val(id)
//     console.log(id);
//     console.log($('#task-track-id'));
// });
$('#php_timezone').on('change', function (e) {
    var gmt = $(this).find(':selected').data('gmt');
    $('#mysql_timezone').val(gmt);
});


$(document).on('click', '.delete-task-alert', function (e) {
    e.preventDefault();
    var task_id = $(this).data("task_id");
    var project_id = $(this).data("project_id");
    swal({
        title: 'Are you sure?',
        text: 'All Task Related Data Will Once deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'projects/delete_task/' + task_id + '/' + project_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.modal-edit-task-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + 'projects/get_task_by_id',
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.user_id != '' && result.user_id != null) {
                var user_ids = result.user_id.split(',');
                $('#update_user_id').val(user_ids);
                $('#update_user_id').trigger('change');
            }
            if (result.milestone_id != 0 && result.milestone_id != null && result.milestone_id != undefined) {
                $('#update_milestone_id').val(result.milestone_id);
                $('#update_milestone_id').trigger('change');
            }
            $('#update_title').val(result.title);
            $('#update_priority').val(result.priority);
            $('#update_priority').trigger('change');
            $('#update_status').val(result.status);
            $('#update_status').trigger('change');
            $('#update_description').val(result.description);
            $('#update_id').val(result.id);
            $('#update_start_date').val(result.start_date);
            $('#update_due_date').val(result.due_date);

            $(".modal-edit-task").trigger("click");
        }
    });
});

$(document).on('click', '.modal-edit-event-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + "calendar/get_event_by_id",
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $('#update_event_title').val(result.title);
            $('#update_event_id').val(result.id);
            $('#update_event_end_date').val(result.to_date);
            $('#update_event_start_date').val(result.from_date);
            $('#update_background_color').val(result.bg_color);
            $('#update_text_color').val(result.text_color);
            $("#customCheck2").prop('checked', false);
            if (result.is_public == 1) {
                $("#customCheck2").prop('checked', true);
            }

            // }
            $(".modal-edit-event").trigger("click");
        }

    });
});

$(document).on('click', '.modal-add-task-details-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + 'projects/get_task_by_id',
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {

            csrfName = result.csrfName;
            csrfHash = result.csrfHash;

            var comments_html = "";
            $.each(result.comments, function (key, value) {
                comments_html += "<li class='media'><img class='mr-3 avatar-sm rounded-circle img-thumbnail' width='60' alt='" + value.commenter_name + "' src='" + base_url + "assets/img/avatar/avatar-1.png'><div class='media-body'><h5 class='mt-0 mb-1 font-weight-bold'>" + value.commenter_name + "</h5>" + value.comment + "<span class='d-flex justify-content-end text-primary'>" + value.date_created + "</span></li>";
            });
            $("#comments_list").html(comments_html);

            var project_media_html = "";
            $.each(result.project_media, function (key, value) {
                project_media_html += "<div class='card mb-1 shadow-none border'><div class='card-body pt-2 pb-2'><div class='row align-items-center'><div class='col-auto'><div class='avatar-sm'><span class='avatar-title rounded text-uppercase'>" + value.file_extension + "</span></div></div><div class='col pl-0'><a download='" + value.original_file_name + "' href='../../assets/project/" + value.file_name + "' class='text-muted font-weight-bold'>" + value.original_file_name + "</a><p class='mb-0'>" + value.file_size + "</p></div><div class='col-auto'><a download='" + value.original_file_name + "' href='../../assets/project/" + value.file_name + "' class='btn btn-link text-muted'><i class='fas fa-download'></i></a></div></div></div></div>";
            });
            $("#project_media_list").html(project_media_html);
            $('.modal-title').html(result.title + " <span class='badge badge-" + result.class + "'>" + result.priority + "</span>");
            $('.task_activity_log_list').bootstrapTable("refreshOptions", {
                url: base_url + 'projects/get_task_activity_logs_list/' + id,
            });
            var profile_html = '';
            $.each(result.task_users, function (key, value) {
                if (value.profile) {
                    profile_html += '<figure class="avatar mr-2 avatar-sm" data-toggle="tooltip" title="' + value.first_name + ' ' + value.last_name + '" data-title="Mithun"><img alt="image" src="' + base_url + 'assets/profiles/' + value.profile + '" class="rounded-circle"> </figure>';
                } else {
                    profile_html += '<figure data-toggle="tooltip" title="' + value.first_name + ' ' + value.last_name + '" data-title="' + value.first_name + ' ' + value.last_name + '" data-initial="' + value.first_name.charAt(0) + '' + value.last_name.charAt(0) + '" class="avatar mr-2 avatar-sm"></figure>';
                }
            });
            $('#asigned_to_name').html(profile_html);

            $('#task_details_milestone').html(result.milestone_name);
            $('#task_details_description').html(result.description);
            $('#task_details_start_date').html(result.start_date);
            $('#task_details_due_date').html(result.due_date);
            $('#workspace_id_details').val(result.workspace_id);
            $('#project_id_details').val(result.project_id);
            $('.task_id_details').val(result.id);
            $('#user_id_details').val(result.user_id);
            $('#task_details_date_created').html(result.date_created);
            $(".modal-add-task-details").trigger("click");
        }
    });
});
$(document).on('click', '.delete-activity-alert', function (e) {
    e.preventDefault();
    var activity_id = $(this).data("activity-id");
    swal({
        title: 'Are you sure?',
        text: 'All Activity Related Data Will Once deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'activity_logs/delete/' + activity_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        if (result.error == false) {
                            location.reload();
                        } else {
                            swal(result.message).then(() => {
                                location.reload();
                            });
                        }

                    }
                });
            } else {
                swal('Your data is safe!!');

            }
        });
});
$(document).on('click', '.deactive-user-alert', function (e) {
    e.preventDefault();
    var user_id = $(this).data("user_id");
    swal({
        title: 'Are you sure?',
        text: 'You want deactive this user?',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'users/deactive/' + user_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                        // csrfName = result['csrfName'];
                        // csrfHash = result['csrfHash'];
                        // $('#users_list').bootstrapTable('refresh');
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.active-user-alert', function (e) {
    e.preventDefault();
    var user_id = $(this).data("user_id");
    swal({
        title: 'Are you sure?',
        text: 'You want to active this user?',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'users/activate/' + user_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                        // csrfName = result['csrfName'];
                        // csrfHash = result['csrfHash'];
                        // $('#users_list').bootstrapTable('refresh');
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.make-user-super-admin-alert', function (e) {
    e.preventDefault();
    var user_id = $(this).data("user_id");
    swal({
        title: 'Are you sure?',
        text: 'You want to make this user super admin?',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'users/make_user_super_admin/' + user_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                        // csrfName = result['csrfName'];
                        // csrfHash = result['csrfHash'];
                        // $('#users_list').bootstrapTable('refresh');
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.make-user-admin-alert', function (e) {
    e.preventDefault();
    var user_id = $(this).data("user_id");
    swal({
        title: 'Are you sure?',
        text: 'You want to make this user admin?',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'users/make-user-admin/' + user_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                        // console.log(result);
                        // csrfName = result['csrfName'];
                        // csrfHash = result['csrfHash'];
                        // $('#users_list').bootstrapTable('refresh');
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.make-team-member-alert', function (e) {
    e.preventDefault();
    var user_id = $(this).data("user_id");
    swal({
        title: 'Are you sure?',
        text: 'This user will be removed from admin.',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'users/remove-user-from-admin/' + user_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                        // csrfName = result['csrfName'];
                        // csrfHash = result['csrfHash'];
                        // $('#users_list').bootstrapTable('refresh');
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.make-client-to-team-member-alert', function (e) {
    e.preventDefault();
    var user_id = $(this).data("user_id");
    swal({
        title: 'Are you sure?',
        text: 'This user will be removed from client.',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'users/remove-user-from-admin/' + user_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                        // csrfName = result['csrfName'];
                        // csrfHash = result['csrfHash'];
                        // $('#users_list').bootstrapTable('refresh');
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.delete-user-alert', function (e) {
    e.preventDefault();
    var user_id = $(this).data("user_id");
    swal({
        title: 'Are you sure?',
        text: 'This user also will be removed from Projects, Tasks and from other related Data, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'users/remove-user-from-workspace/' + user_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.make-announcement-unpinned-alert', function (e) {
    e.preventDefault();
    var announcement_id = $(this).data("announcement-id");
    swal({
        title: 'Are you sure?',
        text: 'You want to un pin this announcement',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'announcements/unpin/' + announcement_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        if (result.error == false) {
                            location.reload();
                        } else {
                            swal(result.message).then(() => {
                                location.reload();
                            });
                        }

                    }
                });
            } else {
                swal('Operation Cancelled!!');

            }
        });
});

$(document).on('click', '.make-announcement-pinned-alert', function (e) {
    e.preventDefault();
    var announcement_id = $(this).data("announcement-id");
    swal({
        title: 'Are you sure?',
        text: 'You want to pin this announcement',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'announcements/pin/' + announcement_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        if (result.error == false) {
                            location.reload();
                        } else {
                            swal(result.message).then(() => {
                                location.reload();
                            });
                        }

                    }
                });
            } else {
                swal('Operation Cancelled!!');

            }
        });
});

$(document).on('click', '.delete-announcement-alert', function (e) {
    e.preventDefault();
    var announcement_id = $(this).data("announcement-id");
    swal({
        title: 'Are you sure?',
        text: 'You want to delete announcement!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'announcements/delete/' + announcement_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        if (result.error == false) {
                            location.reload();
                        } else {
                            swal(result.message).then(() => {
                                location.reload();
                            });
                        }

                    }
                });
            } else {
                swal('Your data is safe!!');

            }
        });
});

$(document).on('click', '.mark-all-as-read-alert', function (e) {
    e.preventDefault();
    var user_id = $(this).data("user-id");
    swal({
        title: 'Are you sure?',
        text: 'You want to mark all notifications as read',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'notifications/mark_all_as_read/' + user_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        if (result.error == false) {
                            location.reload();
                        } else {
                            swal(result.message).then(() => {
                                location.reload();
                            });
                        }

                    }
                });
            } else {
                swal('Operation cancelled!!');

            }
        });
});

$(document).on('click', '.delete-notification-alert', function (e) {
    e.preventDefault();
    var notification_id = $(this).data("notification-id");
    swal({
        title: 'Are you sure?',
        text: 'You want to delete notification!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'notifications/delete/' + notification_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        if (result.error == false) {
                            location.reload();
                        } else {
                            swal(result.message).then(() => {
                                location.reload();
                            });
                        }

                    }
                });
            } else {
                swal('Your data is safe!!');

            }
        });
});
$(document).on('click', '.delete-event-alert', function (e) {
    e.preventDefault();
    var event_id = $(this).data("event-id");
    swal({
        title: 'Are you sure?',
        text: 'All Event Related Data Will Once deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'calendar/delete/' + event_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        if (result.error == false) {
                            location.reload();
                        } else {
                            swal(result.message).then(() => {
                                location.reload();
                            });
                        }

                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.delete-expense-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("expense-id");
    swal({
        title: 'Are you sure?',
        text: 'All Expense Related Data Will Once deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'expenses/delete/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        if (result.error == false) {
                            location.reload();
                        } else {
                            swal(result.message).then(() => {
                                location.reload();
                            });
                        }

                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.delete-expense-type-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("type-id");
    swal({
        title: 'Are you sure?',
        text: 'All Expense Type Related Data Will Once deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'expenses/delete_expense_type/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        if (result.error == false) {
                            location.reload();
                        } else {
                            swal(result.message).then(() => {
                                location.reload();
                            });
                        }

                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.modal-edit-announcement-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + "announcements/get_announcement_by_id",
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;

            $('#update_announcement_title').val(result.title);
            $('#update_announcement_description').val(result.description);
            // tinymce.get('update_announcement_description').setContent(result.description);
            $('#update_announcement_id').val(result.id);
            $(".modal-edit-announcement").trigger("click");
        }
    });
});

$(document).on('click', '.modal-edit-user-btn', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + 'users/get_user_by_id/' + id,
        data: csrfName + '=' + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = csrfName;
            csrfHash = result[0].csrfHash;
            $(".modal-edit-user").trigger("click");
        }
    });
});

$(document).on('click', '.modal-edit-expense-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + "expenses/get_expense_by_id",
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $('#update_id').val(result.id);
            $('#update_title').val(result.title);
            $('#update_expense_type_id').val(result.expense_type_id).trigger('change');
            $('#update_user_id').val(result.user_id).trigger('change');
            $('#update_amount').val(result.amount);
            $('#update_note').val(result.note);
            $('#update_expense_date').val(result.expense_date);

            // }
            $(".modal-edit-expense").trigger("click");
        }

    });

});
$(document).on('click', '.modal-edit-expense-type-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + "expenses/get_expense_type_by_id",
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            console.log(result.csrfName)
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $('#update_id').val(result.id);
            $('#update_title').val(result.title);
            $('#update_description').val(result.description);
            $(".modal-edit-expense-type").trigger("click");
        }

    });
});
$(document).on('click', '.delete-tax-alert', function (e) {
    e.preventDefault();
    var tax_id = $(this).data("tax_id");
    swal({
        title: 'Are you sure?',
        text: 'Tax Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'taxes/delete/' + tax_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.modal-edit-tax-btn', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + 'taxes/get_tax_by_id/' + id,
        data: csrfName + '=' + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $(".modal-edit-tax").trigger("click");
            $('#update_title').val(result.title);
            $('#update_percentage').val(result.percentage);
            $('#update_description').val(result.description);
            $('#update_id').val(result.id);

        }
    });
});
$(document).on('click', '.delete-unit-alert', function (e) {
    e.preventDefault();
    var unit_id = $(this).data("unit_id");
    swal({
        title: 'Are you sure?',
        text: 'Unit Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'units/delete/' + unit_id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.modal-edit-unit-btn', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + 'units/get_unit_by_id/' + id,
        data: csrfName + '=' + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $(".modal-edit-unit").trigger("click");
            $('#update_title').val(result.title);
            $('#update_description').val(result.description);
            $('#update_id').val(result.id);

        }
    });
});
$(document).on('click', '.modal-edit-item-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + 'items/get_item_by_id/' + id,
        data: csrfName + '=' + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $(".modal-edit-item").trigger("click");
            $('#update_title').val(result.title);
            $('#update_description').val(result.description);
            if (result.unit_id == '' || result.unit_id == 0) {
                $('#update_unit').val("");
            } else {
                $('#update_unit').val(result.unit_id);
            }
            $('#update_price').val(result.price);
            $('#update_id').val(result.id);

        }
    });
});
$(document).on('click', '.delete-estimate-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("estimate-id");
    swal({
        title: 'Are you sure?',
        text: 'Estimate Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'estimates/delete/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});
$(document).on('click', '.modal-edit-article-group-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + 'knowledgebase/get_group_by_id/' + id,
        data: csrfName + '=' + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $('#update_id').val(result.id);
            $('#update_title').val(result.title);
            $('#update_description').val(result.description);
            $(".modal-edit-article-group").trigger("click");
        }
    });
});
$(document).on('click', '.delete-invoice-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("invoice-id");
    swal({
        title: 'Are you sure?',
        text: 'Invoice Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'invoices/delete/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});
$(document).on('click', '.delete-article-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("article-id");
    swal({
        title: 'Are you sure?',
        text: 'Article Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'knowledgebase/delete/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});
$(document).on('click', '.modal-edit-payment-mode-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + "payments/get_payment_mode_by_id/" + id,
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $('#update_id').val(result.id);
            $('#update_title').val(result.title);
            $(".modal-edit-payment-mode").trigger("click");
        }

    });

});
$(document).on('click', '.delete-payment-mode-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("payment-mode-id");
    swal({
        title: 'Are you sure?',
        text: 'Payment Mode Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'payments/delete_payment_mode/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});
$(document).on('click', '.delete-payment-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("payment-id");
    swal({
        title: 'Are you sure?',
        text: 'Payment Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'payments/delete_payment/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});
$(document).on('click', '.modal-edit-payment-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + 'payments/get_payment_by_id/' + id,
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            if (result.user_id == 0) {
                $('#update_user_id').val('').trigger('change');
            } else {
                $('#update_user_id').val(result.user_id).trigger('change');
            }
            if (result.invoice_id == 0) {
                $('#update_invoice_id').val('').trigger('change');
            } else {
                $('#update_invoice_id').val(result.invoice_id).trigger('change');
            }
            $('#update_payment_mode_id').val(result.payment_mode_id).trigger('change');
            $('#update_amount').val(result.amount);
            $('#update_note').val(result.note);
            $('#update_payment_date').val(result.payment_date);
            $('#id').val(result.id);
            $(".modal-edit-payment").trigger("click");

        }
    });
});
$(document).on('click', '.delete-item-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    swal({
        title: 'Are you sure?',
        text: 'Item Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'items/delete/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});
$(document).on('click', '.modal-edit-workspace-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + 'workspace/get_workspace_by_id/' + id,
        data: csrfName + '=' + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $(".modal-edit-workspace").trigger("click");
            $('#updt_title').val(result.title);
            if (result.status == 1) {
                $("input[name=status][value=1]").prop('checked', true);
            } else {
                $("input[name=status][value=0]").prop('checked', true);
            }
            $('#workspace_id').val(result.id);

        }
    });
});

$(document).on('click', '.modal-workspace-users-ajax', function () {
    var id = $(this).data("id");
    $("#workspace_id").val(id);
    $(".modal-workspace-users").trigger("click");
    $("#users_list").bootstrapTable('refresh');
});
$(document).on('click', '.modal-workspace-clients-ajax', function () {
    var id = $(this).data("id");
    $("#clients_workspace_id").val(id);
    $(".modal-workspace-clients").trigger("click");
    $("#clients_list").bootstrapTable('refresh');
});

$(document).on('click', '.modal-meeting-users-ajax', function () {
    var id = $(this).data("id");
    var type = 'users';
    $("#type").val(type);
    $("#meeting_id").val(id);
    $(".modal-meeting-users").trigger("click");
    $("#meeting_users_list").bootstrapTable('refresh');
});
$(document).on('click', '.modal-meeting-clients-ajax', function () {
    var id = $(this).data("id");
    var type = 'clients';
    $("#type").val(type);
    $("#meeting_id").val(id);
    $(".modal-meeting-clients").trigger("click");
    $("#meeting_clients_list").bootstrapTable('refresh');
});

$(document).on('click', '.delete-workspace-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    swal({
        title: 'Are you sure?',
        text: 'Workspace Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                swal({
                    title: 'Are you sure?',
                    text: 'Workspace Data Will Be deleted, you will not be able to recover that data!',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then
                swal({
                    title: 'Are you sure?',
                    text: 'We are asking you twice it will delete all workspace related data!',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((willDeleteAgain) => {
                    if (willDeleteAgain) {
                        $.ajax({
                            url: base_url + 'workspace/delete/' + id,
                            type: "POST",
                            data: csrfName + '=' + csrfHash,
                            dataType: 'json',
                            success: function (result) {
                                location.reload();
                            }
                        });
                    } else {
                        swal('Your Data is safe!');
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.delete-mail-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    swal({
        title: 'Are you sure?',
        text: 'Mail Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'send_mail/delete/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});
$(document).on('click', '.retry-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    swal({
        title: 'Are you sure?',
        text: 'You want to resend mail!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'send_mail/send_now/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Operation cancelled!');
            }
        });
});

$(document).on('click', '.send-now-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    swal({
        title: 'Are you sure?',
        text: 'You want to send mail!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'send_mail/send_now/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Operation cancelled!');
            }
        });
});

$(document).on('click', '.delete-leave-request-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    swal({
        title: 'Are you sure?',
        text: 'Leave Request Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'leaves/delete/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});
$('#email').selectize({
    create: true,
    maxItems: 1,
    onChange: function (value) {
        $("#first_name").show();
        $("#last_name").show();
        $("#password_confirm").show();
        $("#password").show();
        $("#address").show();
        $("#city").show();
        $("#state").show();
        $("#country").show();
        $("#zip_code").show();
        $.ajax({
            url: base_url + 'users/get_user_by_id/' + value,
            type: 'POST',
            data: csrfName + '=' + csrfHash,
            dataType: 'json',
            success: function (result) {
                csrfName = csrfName;
                csrfHash = result[0].csrfHash;
                if (result[0].email != '') {
                    $("#first_name").hide();
                    $("#last_name").hide();
                    $("#password_confirm").hide();
                    $("#password").hide();
                    $("#company").hide();
                    $("#phone").hide();
                    $("#address").hide();
                    $("#city").hide();
                    $("#state").hide();
                    $("#country").hide();
                    $("#zip_code").hide();
                } else {
                    $("#first_name").show();
                    $("#last_name").show();
                    $("#password_confirm").show();
                    $("#password").show();
                    $("#address").show();
                    $("#city").show();
                    $("#state").show();
                    $("#country").show();
                    $("#zip_code").show();
                }
            }
        });
    },
    load: function (query, callback) {
        if (!query.length) return callback();
        $.ajax({
            url: base_url + 'users/search_user_by_email/' + query,
            type: 'POST',
            data: csrfName + '=' + csrfHash,
            dataType: 'json',
            success: function (result) {

                csrfName = csrfName;
                csrfHash = result[0].csrfHash;

                var $select = $(document.getElementById('email')).selectize(result);
                var selectize = $select[0].selectize;

                $.each(result, function (index) {

                    var toFind = result[index].id;
                    var filtered = not_in_workspace_user.filter(function (el) {
                        return el.id === toFind;
                    });

                    if (!!filtered && filtered.length > 0) {
                        selectize.addOption({ value: filtered[0].id, text: filtered[0].email });
                    }
                    index++;

                });

                selectize.refreshOptions();
            }
        });
    }

});

$(document).on('click', '.modal-edit-meeting-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + 'meetings/get_meeting_by_id',
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            var start_date = moment(result.start_date).format('DD-MMM-YYYY HH:mm:ss');
            var end_date = moment(result.end_date).format('DD-MMM-YYYY HH:mm:ss');
            if (result.user_ids != '' && result.user_ids != null) {
                var user_ids = result.user_ids.split(',');
                $('#update_users').val(user_ids);
                $('#update_users').trigger('change');
            }
            if (result.client_id != '' && result.client_id != null) {
                var client_ids = result.client_id.split(',');
                $('#update_clients').val(client_ids);
                $('#update_clients').trigger('change');
            }
            $('#update_title').val(result.title);
            $('#update_id').val(result.id);
            $('#update_type').val(result.type).trigger('change');
            $('#update_platform').val(result.platform).trigger('change');
            $('#update_link').val(result.link);
            $('#update_venue').val(result.venue);
            $('#update_start_date').val(start_date);
            $('#update_end_date').val(end_date);

            $(".modal-edit-meeting").trigger("click");
        }
    });
});

$(document).on('click', '.delete-meeting-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    swal({
        title: 'Are you sure?',
        text: 'You want to delete meeting!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'meetings/delete/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.join-meeting-alert', function (e) {
    e.preventDefault();
    var slug = $(this).data("slug");
    swal({
        title: 'Are you sure?',
        text: 'You want to join meeting!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                window.location.replace(base_url + 'meetings/join/' + slug);
            } else {
                swal('Operation cancelled!');
            }
        });
});
$(document).on('click', '.modal-edit-knowledgebase-ajax', function () {
    var id = $(this).data("id");
    $('#id').val(id);
    $.ajax({
        type: "POST",
        url: base_url + 'knowledgebase/edit-article/' + id,
        data: csrfName + '=' + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $(".create_article").trigger("click");
            $('#update_id').val(result.id);
            $('#article_group_id').val(result.group_id);
            $('#title').val(result.title);
            $('#add_article').val(result.description);
            tinymce.get('add_article').setContent(result.description);

        }
    });
});


$(document).on('click', '.modal-edit-contracts-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + "contracts/get_contracts_by_id",
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $('#update_id').val(result.id);
            $('#update_title').val(result.title);
            $('#update_value').val(result.value);
            if (result.users_id != '' && result.users_id != null) {
                var user_ids = result.users_id.split(',');
                $('#update_clients').val(user_ids);
                $('#update_clients').trigger('change');
            }
            if (result.project_id != '' && result.project_id != null) {
                var project_id = result.project_id.split(',');
                $('#update_contract_project_id').val(project_id);
                $('#update_contract_project_id').trigger('change');
            }
            if (result.contract_type_id != '' && result.contract_type_id != null) {
                var contract_type_id = result.contract_type_id.split(',');
                $('#update_contract_type_id').val(contract_type_id);
                $('#update_contract_type_id').trigger('change');
            }
            $('#update_start_date').val(result.start_date);
            $('#update_end_date').val(result.end_date);
            $('#update_description').val(result.description);
            tinyMCE.get('update_description').setContent(result.description);
            $(".modal-edit-contracts").trigger("click");

        }
    });
});
$(document).on('click', '.modal-edit-contracts-type-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + "contracts/get_contracts_type_by_id/" + id,
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $('#update_id').val(result.id);
            $('#update_type').val(result.type);
            $(".modal-edit-contracts-type").trigger("click");
        }

    });

});
$('.provider-sign-data').on('click', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + "contracts/get_contracts_sign_by_id",
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $('#update_id').val(result.id);
            $('#update_provider_first_name').val(result.provider_first_name);
            $('#update_provider_last_name').val(result.provider_last_name);
            $('#signature-pad1').val(result.provider_sign);

        }
    });
});
$('.client-sign-data').on('click', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + "contracts/get_contracts_sign_by_id",
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $('#update_client_id').val(result.id);
            $('#update_client_first_name').val(result.client_first_name);
            $('#update_client_last_name').val(result.client_last_name);
            $('#signature-pad2').val(result.client_sign);

        }
    });
});
$(document).on('click', '.delete-contracts-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    swal({
        title: 'Are you sure?',
        text: 'FAQ Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'contracts/delete/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});
$(document).on('click', '.delete-contracts-type-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    swal({
        title: 'Are you sure?',
        text: 'FAQ Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'contracts/delete_contracts_type/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});
$(document).on('click', '.delete-contracts-provider-sign-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    swal({
        title: 'Are you sure?',
        text: 'Provider sign contracts Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'contracts/delete_provider_sign_contracts/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});
$(document).on('click', '.delete-contracts-client-sign-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    swal({
        title: 'Are you sure?',
        text: 'Client sign contracts Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'contracts/delete_client_sign_contracts/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});
$(document).on('click', '.modal-edit-allowance-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + "payslip/get_allowance_by_id/" + id,
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $('#update_id').val(result.id);
            $('#update_name').val(result.name);
            $('#update_amount').val(result.amount);
            $(".modal-edit-allowance").trigger("click");
        }

    });

});
$(document).on('click', '.delete-allowance-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    swal({
        title: 'Are you sure?',
        text: 'Allowance Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'payslip/delete_allowance/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.modal-edit-deduction-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + "payslip/get_deduction_by_id/" + id,
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $('#update_id').val(result.id);
            $('#update_name').val(result.name);
            $('#update_amount').val(result.amount);
            $('#update_percentage').val(result.percentage);
            $('#update_deduction_type').val(result.deduction_type);
            $('#update_deduction_type').trigger('change');
            $(".modal-edit-deduction").trigger("click");
        }

    });

});

$(document).on('click', '.delete-deduction-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    swal({
        title: 'Are you sure?',
        text: 'Deduction Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'payslip/delete_deductions/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});
$(document).on('click', '#modal-duplicate-note', function () {
    var id = $(this).data("id");
    swal({
        title: "Confirmation",
        text: "Are you sure you want to duplicate?",
        icon: "warning",
        buttons: ["Cancel", "Duplicate"],
        dangerMode: true,
    })
        .then(function (confirm) {
            if (confirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "notes/duplicate_data",
                    data: "id=" + id + "&" + csrfName + "=" + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        csrfName = result.csrfName;
                        csrfHash = result.csrfHash;
                        location.reload();
                        iziToast.success({
                            title: result['message'],
                            message: 'successful',
                            position: 'topRight'
                        });
                    }
                });
            } else {
                swal("Cancelled", "The duplication process has been cancelled.", "info");
            }
        });
});

$(document).on('click', '.modal-duplicate-lead', function () {
    var id = $(this).data("id");
    swal({
        title: "Confirmation",
        text: "Are you sure you want to duplicate?",
        icon: "warning",
        buttons: ["Cancel", "Duplicate"],
        dangerMode: true,
    })
        .then(function (confirm) {
            if (confirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "leads/duplicate_data",
                    data: "id=" + id + "&" + csrfName + "=" + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        csrfName = result.csrfName;
                        csrfHash = result.csrfHash;
                        location.reload();
                        iziToast.success({
                            title: result['message'],
                            message: 'successful',
                            position: 'topRight'
                        });
                    }
                });
            } else {
                swal("Cancelled", "The duplication process has been cancelled.", "info");
            }
        });
});
$(document).on('click', '.modal-duplicate-project-ajax', function () {
    var id = $(this).data("id");
    swal({
        title: "Confirmation",
        text: "Are you sure you want to duplicate?",
        icon: "warning",
        buttons: ["Cancel", "Duplicate"],
        dangerMode: true,
    })
        .then(function (confirm) {
            if (confirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "projects/duplicate_data",
                    data: "id=" + id + "&" + csrfName + "=" + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        csrfName = result.csrfName;
                        csrfHash = result.csrfHash;
                        location.reload();
                        iziToast.success({
                            title: result['message'],
                            message: 'successful',
                            position: 'topRight'
                        });
                    }
                });
            } else {
                swal("Cancelled", "The duplication process has been cancelled.", "info");
            }
        });
});
$(document).on('click', '.modal-duplicate-task-ajax', function () {
    var id = $(this).data("id");
    swal({
        title: "Confirmation",
        text: "Are you sure you want to duplicate?",
        icon: "warning",
        buttons: ["Cancel", "Duplicate"],
        dangerMode: true,
    })
        .then(function (confirm) {
            if (confirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "projects/duplicate_task",
                    data: "id=" + id + "&" + csrfName + "=" + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        csrfName = result.csrfName;
                        csrfHash = result.csrfHash;
                        location.reload();
                        iziToast.success({
                            title: result['message'],
                            message: 'successful',
                            position: 'topRight'
                        });
                    }
                });
            } else {
                swal("Cancelled", "The duplication process has been cancelled.", "info");
            }
        });
});
$(document).on('click', '.modal-duplicate-event-ajax', function () {
    var id = $(this).data("id");
    swal({
        title: "Confirmation",
        text: "Are you sure you want to duplicate?",
        icon: "warning",
        buttons: ["Cancel", "Duplicate"],
        dangerMode: true,
    })
        .then(function (confirm) {
            if (confirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "calendar/duplicate_data",
                    data: "id=" + id + "&" + csrfName + "=" + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        csrfName = result.csrfName;
                        csrfHash = result.csrfHash;
                        location.reload();
                        iziToast.success({
                            title: result['message'],
                            message: 'successful',
                            position: 'topRight'
                        });
                    }
                });
            } else {
                swal("Cancelled", "The duplication process has been cancelled.", "info");
            }
        });
});
$(document).on('click', '.modal-duplicate-Knowledgebase', function () {
    var id = $(this).data("id");
    swal({
        title: "Confirmation",
        text: "Are you sure you want to duplicate?",
        icon: "warning",
        buttons: ["Cancel", "Duplicate"],
        dangerMode: true,
    })
        .then(function (confirm) {
            if (confirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "knowledgebase/duplicate_data",
                    data: "id=" + id + "&" + csrfName + "=" + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        csrfName = result.csrfName;
                        csrfHash = result.csrfHash;
                        location.reload();
                        iziToast.success({
                            title: result['message'],
                            message: 'successful',
                            position: 'topRight'
                        });
                    }
                });
            } else {
                swal("Cancelled", "The duplication process has been cancelled.", "info");
            }
        });
});
$(document).on('click', '.modal-duplicate-contracts', function () {
    var id = $(this).data("id");
    swal({
        title: "Confirmation",
        text: "Are you sure you want to duplicate?",
        icon: "warning",
        buttons: ["Cancel", "Duplicate"],
        dangerMode: true,
    })
        .then(function (confirm) {
            if (confirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "contracts/duplicate_data",
                    data: "id=" + id + "&" + csrfName + "=" + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        csrfName = result.csrfName;
                        csrfHash = result.csrfHash;
                        location.reload();
                        iziToast.success({
                            title: result['message'],
                            message: 'successful',
                            position: 'topRight'
                        });
                    }
                });
            } else {
                swal("Cancelled", "The duplication process has been cancelled.", "info");
            }
        });
});
$(document).on('click', '.modal-duplicate-invoice', function () {
    var id = $(this).data("id");
    swal({
        title: "Confirmation",
        text: "Are you sure you want to duplicate?",
        icon: "warning",
        buttons: ["Cancel", "Duplicate"],
        dangerMode: true,
    })
        .then(function (confirm) {
            if (confirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "invoices/duplicate_data",
                    data: "id=" + id + "&" + csrfName + "=" + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        csrfName = result.csrfName;
                        csrfHash = result.csrfHash;
                        location.reload();
                        iziToast.success({
                            title: result['message'],
                            message: 'successful',
                            position: 'topRight'
                        });
                    }
                });
            } else {
                swal("Cancelled", "The duplication process has been cancelled.", "info");
            }
        });
});
$(document).on('click', '.modal-duplicate-estimates', function () {
    var id = $(this).data("id");
    swal({
        title: "Confirmation",
        text: "Are you sure you want to duplicate?",
        icon: "warning",
        buttons: ["Cancel", "Duplicate"],
        dangerMode: true,
    })
        .then(function (confirm) {
            if (confirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "estimates/duplicate_data",
                    data: "id=" + id + "&" + csrfName + "=" + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        csrfName = result.csrfName;
                        csrfHash = result.csrfHash;
                        location.reload();
                        iziToast.success({
                            title: result['message'],
                            message: 'successful',
                            position: 'topRight'
                        });
                    }
                });
            } else {
                swal("Cancelled", "The duplication process has been cancelled.", "info");
            }
        });
});

$(document).on('click', '.delete-payslip-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("payslip-id");
    swal({
        title: 'Are you sure?',
        text: 'Payslip Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'payslip/delete/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.modal-duplicate-payslip', function () {
    var id = $(this).data("id");
    swal({
        title: "Confirmation",
        text: "Are you sure you want to duplicate?",
        icon: "warning",
        buttons: ["Cancel", "Duplicate"],
        dangerMode: true,
    })
        .then(function (confirm) {
            if (confirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "payslip/duplicate_data",
                    data: "id=" + id + "&" + csrfName + "=" + csrfHash,
                    dataType: "json",
                    success: function (result) {
                        csrfName = result.csrfName;
                        csrfHash = result.csrfHash;
                        location.reload();
                        iziToast.success({
                            title: result['message'],
                            message: 'successful',
                            position: 'topRight'
                        });
                    }
                });
            } else {
                swal("Cancelled", "The duplication process has been cancelled.", "info");
            }
        });
});

$(document).ready(function () {

    // Search input keyup event
    $('#search-results').on('keyup', function (event) {
        performSearch();
    });
});

function performSearch() {
    var searchInput = $('#search-results').val();
    if (!/[a-zA-Z]/.test(searchInput)) {
        $('.search-results').empty().append('<div class="search-header mb-1">Enter at least one letter to search.</div>');
        return;
    }

    var resultsContainer = $('.search-results');
    resultsContainer.empty();

    $.ajax({
        type: 'GET',
        url: base_url + "search_data",
        data: {
            search: searchInput
        },
        dataType: 'json',
        success: function (response) {
            if (!response.error) {
                var searchResults_data = response.searchResults;
                var resultsContainer = $('.search-results');
                // Loop through each table (e.g., "leads", "articles", etc.)
                for (var table in searchResults_data) {
                    if (searchResults_data.hasOwnProperty(table)) {
                        var tableResults = searchResults_data[table];
                        // Check if there are results in the current table
                        if (Array.isArray(tableResults) && tableResults.length > 0) {
                            var resultHtml = '';
                            resultHtml += '<div class="search-header">' + table + '</div>';
                            $.each(tableResults, function (index, result) {
                                if (table === "leads") {
                                    resultHtml += '<div class="search-item"><a href="' + base_url + table + '">' + result.title + '</a></div>';
                                }
                                if (table === "articles") {
                                    var slug = result.slug;
                                    resultHtml += '<div class="search-item"><a href="' + base_url + 'knowledgebase/view-article/' + slug + '">' + result.title + '</a></div>';
                                }
                                if (table === "contracts") {
                                    var id = result.id;
                                    resultHtml += '<div class="search-item"><a href="' + base_url + table + 'contracts_sign/' + id + '">' + result.title + '</a></div>';
                                }
                                if (table === "events") {
                                    resultHtml += '<div class="search-item"><a href="' + base_url + 'calendar">' + result.title + '</a></div>';
                                }
                                if (table === "items") {
                                    resultHtml += '<div class="search-item"><a href="' + base_url + table + '">' + result.title + '</a></div>';
                                }
                                if (table === "meetings") {
                                    resultHtml += '<div class="search-item"><a href="' + base_url + table + '">' + result.title + '</a></div>';
                                }
                                if (table === "milestones") {
                                    var id = result.id;
                                    resultHtml += '<div class="search-item"><a href="' + base_url + 'projects/details/' + id + '">' + result.title + '</a></div>';
                                }
                                if (table === "notes") {
                                    resultHtml += '<div class="search-item"><a href="' + base_url + table + '">' + result.title + '</a></div>';
                                }
                                if (table === "projects") {
                                    var id = result.id;
                                    resultHtml += '<div class="search-item"><a href="' + base_url + 'projects/details/' + id + '">' + result.title + '</a></div>';
                                }
                                if (table === "tasks") {
                                    var id = result.id;
                                    resultHtml += '<div class="search-item"><a href="' + base_url + 'projects/tasks/' + id + '">' + result.title + '</a></div>';
                                }
                                if (table === "taxes") {
                                    resultHtml += '<div class="search-item"><a href="' + base_url + table + '">' + result.title + '</a></div>';
                                }
                                if (table === "unit") {
                                    resultHtml += '<div class="search-item"><a href="' + base_url + 'units">' + result.title + '</a></div>';
                                }
                                if (table === "expenses") {
                                    resultHtml += '<div class="search-item"><a href="' + base_url + table + '">' + result.title + '</a></div>';
                                }
                            });
                            resultsContainer.append(resultHtml);
                        }
                    }
                }
                // If no results were found in any table
                if (resultsContainer.is(':empty')) {
                    resultsContainer.html('<div class="search-header mb-1">No search results found.</div>');
                }
            } else {
                console.log('Response is not an object.');
            }
        },
        error: function (xhr, status, error) {
            console.log('AJAX error occurred: ' + error);
        }
    });

}


function handleAddTaskClick(e) {
    console.log(e);
    var id = $(e).data("id");
    $('#task-track-id').val(id)
}

$(document).on('click', '.modal-edit-statuses-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + "statuses/get_statuses_by_id/" + id,
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $('#update_id').val(result.id);
            $('#update_type').val(result.type);
            $('#update_text_color').val(result.text_color);
            $(".modal-edit-statuses").trigger("click");
        }

    });

});
$(document).on('click', '.delete-statuses-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    swal({
        title: 'Are you sure?',
        text: 'Statuses Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'statuses/delete_statuses/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('click', '.modal-edit-time-tracker-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + 'time_tracker/get_record_by_id/' + id,
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;
            $('#update_id').val(result.id);
            if (result.project_id != '' && result.project_id != null) {
                var project_id = result.project_id.split(',');
                $('#update_project_id').val(project_id);
                $('#update_project_id').trigger('change');
            }
            $('#update_start_date').val(result.start_time);
            $('#update_end_date').val(result.end_time);
            $('#update_message').val(result.message);
            $('#update_date').val(result.date);
            $(".modal-edit-time-tracker").trigger("click");
        }
    });
});
$(document).on('click', '.delete-time-tracker-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("time-tracker-id");
    swal({
        title: 'Are you sure?',
        text: 'Time tracker Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'time_tracker/delete/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});
$(document).on('click', '.delete-payslips-sign-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    swal({
        title: 'Are you sure?',
        text: 'Sign payslips Data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'payslip/delete_sign_payslips/' + id,
                    type: "POST",
                    data: csrfName + '=' + csrfHash,
                    success: function (result) {
                        location.reload();
                    }
                });
            } else {
                swal('Your Data is safe!');
            }
        });
});

$(document).on('submit', '.form-submit-event', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
   
        var error_box = $('#error_box', this);
        var submit_btn = $('#submit_btn');
        var btn_html = $('#submit_btn').html();
        var btn_val = $('#submit_btn').val();
        var button_text = (btn_html != '' || btn_html != 'undefined') ? btn_html : btn_val;
    

    formData.append(csrfName, csrfHash);

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        beforeSend: function () {
            submit_btn.html('Please Wait..');
            submit_btn.attr('disabled', true);
        },
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (result) {

            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];

            if (result['error'] == true) {

                error_box.addClass("msg_error rounded p-3").removeClass('d-none msg_success');
                error_box.show().delay(1000).fadeOut();
                error_box.html(result['message']);
                submit_btn.html(button_text);
                submit_btn.attr('disabled', false);
                iziToast.error({
                    message: result['message'],
                    position: 'topRight'
                });

            } else {

                error_box.addClass("msg_success rounded p-3").removeClass('d-none msg_error');
                error_box.show().delay(1000).fadeOut();
                error_box.html(result['message']);
                submit_btn.html(button_text);
                submit_btn.attr('disabled', false);
               
                iziToast.success({
                    message: result['message'],
                    position: 'topRight'
                });
                $('.form-submit-event')[0].reset();
               
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }
    });
});
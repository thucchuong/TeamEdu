"use strict";

var modal_footer_edit_title = ($('#modal-footer-edit-title')) ? $('#modal-footer-edit-title').html() : "Edit";
var modal_footer_add_title = ($('#modal-footer-add-title')) ? $('#modal-footer-add-title').html() : "Add";
var modal_footer_delete_title = ($('#modal-footer-delete-title')) ? $('#modal-footer-delete-title').html() : "Delete";
var modal_footer_submit_title = ($('#modal-footer-submit-title')) ? $('#modal-footer-submit-title').html() : "Submit";

var font_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Font";
$("#modal-add-fonts").fireModal({
    size: 'modal-lg',
    title: font_modal_title,
    body: $("#modal-add-font-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'formBtn',
        handler: function (modal) { }
    }]
});

$("#modal-leave-editors").fireModal({
    size: 'modal-lg',
    title: 'Leave Request Editors',
    body: $("#modal-leave-editors-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    //   location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: 'Ok',
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'formBtn',
        handler: function (modal) { }
    }]
});

var leave_edit_modal_title = ($('#modal-edit-leave-title')) ? $('#modal-edit-leave-title').html() : "Edit Leave";
$("#modal-edit-leave").fireModal({
    size: 'modal-lg',
    title: leave_edit_modal_title,
    body: $("#modal-edit-leave-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'formBtn',
        handler: function (modal) { }
    }]
});

var request_leave_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Request Leave";
$("#modal-add-leave").fireModal({
    size: 'modal-lg',
    title: request_leave_modal_title,
    body: $("#modal-add-leave-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'formBtn',
        handler: function (modal) { }
    }]
});

var add_language_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Language";
$("#modal-add-language").fireModal({
    size: 'modal-lg',
    title: add_language_modal_title,
    body: $("#modal-add-language-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        // console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }]
});

var search_msg_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Search Message";
$("#modal-search-msg").fireModal({
    size: 'modal-lg',
    title: search_msg_modal_title,
    body: $("#modal-search-msg-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: true,

    onFormSubmit: function (modal, e, form) {

        let form_data = $(e.target).serialize();
        e.preventDefault();

    },
    shown: function (modal, form) { },

});

var edit_workspace_modal_title = ($('#modal-edit-workspace-title')) ? $('#modal-edit-workspace-title').html() : "Edit Workspace";
$(".modal-edit-workspace").fireModal({
    title: edit_workspace_modal_title,
    body: $("#modal-edit-workspace-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }
            }
        });

        e.preventDefault();

    },
    shown: function (modal, form) {
        // console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'workspacebtn',
        handler: function (modal) { }
    }]
});

var workspace_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Workspace";
$("#modal-add-workspace").fireModal({
    title: workspace_modal_title,
    body: $("#modal-add-Workspace-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data

        let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                if (result['error'] == false) {
                    location.reload();
                } else {
                    $('#fire-modal-21').on('hidden.bs.modal', function (e) {
                        $(this).find('form').trigger('reset');
                    });
                    csrfName = result['csrfName'];
                    csrfHash = result['csrfHash'];
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();

                }
            }
        });

        e.preventDefault();

    },
    shown: function (modal, form) {
        // console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'workspacebtn',
        handler: function (modal) { }
    }]
});

$("#modal-info-group").fireModal({
    size: 'modal-lg',
    title: 'Group Information',
    body: $("#modal-info-group-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        e.preventDefault();
    },
    shown: function (modal, form) {
        // console.log(form)
    },
});

var edit_group_modal_title = ($('#modal-title-edit-group')) ? $('#modal-title-edit-group').html() : "Edit Group";
$("#modal-edit-group").fireModal({
    size: 'modal-lg',
    title: edit_group_modal_title,
    body: $("#modal-edit-group-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_delete_title,
        submit: false,
        class: 'btn btn-danger text-white',
        id: 'delete_group',

    },
    {
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }
    ]
});

var add_group_modal_title = ($('#modal-title-group')) ? $('#modal-title-group').html() : "Create Group";
$(".modal-add-group").fireModal({
    size: 'modal-lg',
    title: add_group_modal_title,
    body: $(".modal-add-group-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }]
});

$(".modal-add-task-details").fireModal({
    size: 'modal-lg',
    title: '',
    body: $("#modal-add-task-details-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }
            }
        });
        e.preventDefault();
    },
    shown: function (modal, form) {
        // console.log(form)
    },
    buttons: [{
        text: modal_footer_submit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }]
});

var edit_task_modal_title = ($('#modal-edit-task-title')) ? $('#modal-edit-task-title').html() : "Edit Task";
$(".modal-edit-task").fireModal({
    size: 'modal-lg',
    title: edit_task_modal_title,
    body: $("#modal-edit-task-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }
            }
        });
        e.preventDefault();
    },
    shown: function (modal, form) {
        // console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }]
});

var add_task_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Task";
$("#modal-add-task").fireModal({
    size: 'modal-lg',
    title: add_task_modal_title,
    body: $("#modal-add-task-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();

                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }
            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }]
});

var add_task_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Task";
$("#modal-add-task-list").fireModal({
    size: 'modal-lg',
    title: add_task_modal_title,
    body: $("#modal-add-task-list-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }
            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }]
});


var edit_milestone_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Edit Milestone";
$(".modal-edit-milestone").fireModal({
    size: 'modal-lg',
    title: edit_milestone_modal_title,
    body: $("#modal-edit-milestone-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }
            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        // console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }]
});

var add_milestone_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Milestone";
$("#modal-add-milestone").fireModal({
    size: 'modal-lg',
    title: add_milestone_modal_title,
    body: $("#modal-add-milestone-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }
            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }]
});

var project_edit_modal_title = ($('#modal-edit-project-title')) ? $('#modal-edit-project-title').html() : "Edit Project";
$(".modal-edit-project").fireModal({
    size: 'modal-lg',
    title: project_edit_modal_title,
    body: $("#modal-edit-project-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        // console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }]
});

var project_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Project";
$("#modal-add-project").fireModal({
    size: 'modal-lg',
    title: project_modal_title,
    body: $("#modal-add-project-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }]
});

var edit_note_modal_title = ($('#modal-edit-note-title')) ? $('#modal-edit-note-title').html() : "Edit Note";
$(".modal-edit-note").fireModal({
    title: edit_note_modal_title,
    body: $("#modal-edit-note-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }
            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //  modal.find('.modal-title').text(edit_note_modal_title);

    },
    buttons: [{
        text: modal_footer_delete_title,
        submit: false,
        class: 'btn btn-danger text-white delete-note-alert',
        id: 'delete_note',

    },
    {
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }
    ]
});

var add_note_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Note";
$("#modal-add-note").fireModal({
    title: add_note_modal_title,
    body: $("#modal-add-note-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'addnotebtn',
        handler: function (modal) { }
    }]
});

var user_modal_title = ($('#modal-title').length > 0) ? $('#modal-title').html() : "Add User";
$("#modal-add-user").fireModal({
    size: 'modal-lg',
    title: user_modal_title,
    body: $("#modal-add-user-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'adduserbtn',
        handler: function (modal) { }
    }]
});

$(".modal-edit-user").fireModal({
    size: 'modal-lg',
    title: 'Edit User',
    body: $("#modal-edit-user-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'updateuserbtn',
        handler: function (modal) { }
    }]
});

var add_event_modal_title = ($('#modal-event-title')) ? $('#modal-event-title').html() : "Add Event";
$("#modal-add-event").fireModal({

    title: add_event_modal_title,
    body: $("#modal-add-event-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'addeventbtn',
        handler: function (modal) { }
    }]

});
var edit_event_modal_title = ($('#modal-edit-event-title')) ? $('#modal-edit-event-title').html() : "Edit Event";
$(".modal-edit-event").fireModal({
    title: edit_event_modal_title,
    body: $("#modal-edit-event-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'editeventbtn',
        handler: function (modal) { }
    }]
});

var announcement_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Announcement";
$("#modal-add-announcement").fireModal({
    size: 'modal-lg',
    title: announcement_modal_title,
    body: $("#modal-add-announcement-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.alert-message').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }]
});

$(".modal-edit-announcement").fireModal({
    title: 'Edit Announcement',
    size: 'modal-lg',
    body: $("#modal-edit-announcement-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.alert-message').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'editannouncementtbtn',
        handler: function (modal) { }
    }]
});

var add_expense_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Expense";
$("#modal-add-expense").fireModal({

    size: 'modal-lg',
    title: add_expense_modal_title,
    body: $("#modal-add-expense-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'addexpensebtn',
        handler: function (modal) { }
    }]

});

var edit_expense_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Edit Expense";
$(".modal-edit-expense").fireModal({
    size: 'modal-lg',
    title: edit_expense_modal_title,
    body: $("#modal-edit-expense-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'editexpensebtn',
        handler: function (modal) { }
    }]

});

var edit_expense_type_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Edit Expense Type";
$(".modal-edit-expense-type").fireModal({
    title: edit_expense_type_modal_title,
    body: $("#modal-edit-expense-type-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }
            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'updateexpensetypebtn',
        handler: function (modal) { }
    }]
});

var add_expense_type_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Expense Type";
$("#modal-add-expense-type").fireModal({

    title: add_expense_type_modal_title,
    body: $("#modal-add-expense-type-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        e.preventDefault();
        // Form Data
        var formData = new FormData(this);
        var is_reload = formData.get("is_reload");
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    if (is_reload == 1) {
                        location.reload();
                    } else {
                        $('#expense_type_id').find('option').remove().end();
                        $.ajax({
                            type: 'POST',
                            url: base_url + 'expenses/get_expense_types',
                            data: csrfName + '=' + csrfHash,
                            dataType: "json",
                            // quietMillis: 500,
                            success: function (data) {
                                csrfName = data.csrfName;
                                csrfHash = data.csrfHash;
                                delete data["csrfName"];
                                delete data["csrfHash"];
                                var $newOption = $("<option selected></option>").val('').text('Choose...')
                                $("#expense_type_id").append($newOption).trigger('change');
                                $.each(data, function (i) {
                                    $.each(data[i], function (key, val) {

                                        var $newOption = $("<option></option>").val(val.id).text(val.title)
                                        $("#expense_type_id").append($newOption).trigger('change');
                                    });
                                });
                                $('#expense_type_id').val(result['expense_type_id']).trigger('change');


                            }

                        });
                        $('#update_expense_type_id').find('option').remove().end();
                        $.ajax({
                            type: 'POST',
                            url: base_url + 'expenses/get_expense_types',
                            data: csrfName + '=' + csrfHash,
                            dataType: "json",
                            // quietMillis: 500,
                            success: function (data) {
                                csrfName = data.csrfName;
                                csrfHash = data.csrfHash;
                                delete data["csrfName"];
                                delete data["csrfHash"];
                                var $newOption = $("<option selected></option>").val('').text('Choose...')
                                $("#update_expense_type_id").append($newOption).trigger('change');
                                $.each(data, function (i) {
                                    $.each(data[i], function (key, val) {

                                        var $newOption = $("<option></option>").val(val.id).text(val.title)
                                        $("#update_expense_type_id").append($newOption).trigger('change');
                                    });
                                });
                                $('#update_expense_type_id').val(result['expense_type_id']).trigger('change');


                            }

                        });
                        form.stopProgress();
                        $('#fire-modal-4').modal('hide');
                        $('#fire-modal-5').modal('hide');
                        $('#fire-modal-51').modal('hide');
                        // $('#modal-add-expense').trigger('click');
                        iziToast.success({
                            title: result['message'],
                            message: '',
                            position: 'topRight'
                        });
                    }

                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();

                }
            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'addexpensetypebtn',
        handler: function (modal) { }
    }]
});

var add_item_modal_title = ($('#modal-item-title')) ? $('#modal-item-title').html() : "Add Item";
$("#modal-add-item").fireModal({
    size: 'modal-md',
    title: add_item_modal_title,
    body: $("#modal-add-item-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        // let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        var is_reload = formData.get("is_reload");
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    if (is_reload == 1) {
                        location.reload();
                    } else {
                        $('#item_id').find('option').remove().end();
                        $.ajax({
                            type: 'POST',
                            url: base_url + 'items/get_items',
                            data: csrfName + '=' + csrfHash,
                            dataType: "json",
                            // quietMillis: 500,
                            success: function (data) {
                                csrfName = data["csrfName"];
                                csrfHash = data["csrfHash"];
                                delete data["csrfName"];
                                delete data["csrfHash"];
                                var $newOption = $("<option selected></option>").val('').text('Choose...')

                                $("#item_id").append($newOption).trigger('change');
                                $.each(data, function (i) {
                                    $.each(data[i], function (key, val) {

                                        var $newOption = $("<option></option>").val(val.id).text(val.title)

                                        $("#item_id").append($newOption).trigger('change');
                                    });
                                });
                                $('#item_id').val(result['item_id']).trigger('change');


                            }

                        });

                        form.stopProgress();
                        $('#fire-modal-31').modal('hide');
                        $('#fire-modal-3').modal('hide');
                        iziToast.success({
                            title: result['message'],
                            message: '',
                            position: 'topRight'
                        });
                    }


                } else {
                    form.stopProgress();
                    // $('#fire-modal-4').modal('hide');
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'additembtn',
        handler: function (modal) { }
    }]

});

var edit_item_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Edit Item";
$(".modal-edit-item").fireModal({
    size: 'modal-md',
    title: edit_item_modal_title,
    body: $("#modal-edit-item-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        // let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    $('#item_id').find('option').remove().end();
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'items/get_items',
                        data: csrfName + '=' + csrfHash,
                        dataType: "json",
                        // quietMillis: 500,
                        success: function (data) {
                            csrfName = data["csrfName"];
                            csrfHash = data["csrfHash"];
                            delete data["csrfName"];
                            delete data["csrfHash"];
                            var $newOption = $("<option selected></option>").val('').text('Choose...')

                            $("#item_id").append($newOption).trigger('change');
                            $.each(data, function (i) {
                                $.each(data[i], function (key, val) {

                                    var $newOption = $("<option></option>").val(val.id).text(val.title)

                                    $("#item_id").append($newOption).trigger('change');
                                });
                            });
                            $('#item_id').val(result['item_id']).trigger('change');


                        }

                    });

                    form.stopProgress();
                    $('#fire-modal-41').modal('hide');
                    $('#fire-modal-4').modal('hide');
                    iziToast.success({
                        title: result['message'],
                        message: '',
                        position: 'topRight'
                    });

                } else {
                    form.stopProgress();
                    // $('#fire-modal-4').modal('hide');
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'edititembtn',
        handler: function (modal) { }
    }]

});

var add_tax_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Tax";
$("#modal-add-tax").fireModal({
    size: 'modal-md',
    title: add_tax_modal_title,
    body: $("#modal-add-tax-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'addtaxbtn',
        handler: function (modal) { }
    }]
});

var edit_tax_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Edit Tax";
$(".modal-edit-tax").fireModal({
    size: 'modal-md',
    title: edit_tax_modal_title,
    body: $("#modal-edit-tax-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'updatetaxbtn',
        handler: function (modal) { }
    }]
});

var add_unit_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Unit";
$("#modal-add-unit").fireModal({
    size: 'modal-md',
    title: add_unit_modal_title,
    body: $("#modal-add-unit-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'addunitbtn',
        handler: function (modal) { }
    }]
});

var edit_unit_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Edit Unit";
$(".modal-edit-unit").fireModal({
    size: 'modal-md',
    title: edit_unit_modal_title,
    body: $("#modal-edit-unit-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'updateunitbtn',
        handler: function (modal) { }
    }]
});

var edit_article_group_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Edit Article Group";
$(".modal-edit-article-group").fireModal({
    size: 'modal-md',
    title: edit_article_group_modal_title,
    body: $("#modal-edit-article-group-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'updateunitbtn',
        handler: function (modal) { }
    }]
});

var add_payment_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Announcement";
$("#modal-add-payment").fireModal({

    size: 'modal-lg',
    title: add_payment_modal_title,
    body: $("#modal-add-payment-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'addpaymentbtn',
        handler: function (modal) { }
    }]

});

var edit_payment_mode_modal_title = ($('#modal-edit-payment-mode-title')) ? $('#modal-edit-payment-mode-title').html() : "Edit Payment Mode";
$(".modal-edit-payment-mode").fireModal({

    size: 'modal-md',
    title: edit_payment_mode_modal_title,
    body: $("#modal-edit-payment-mode-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'editpaymentmodebtn',
        handler: function (modal) { }
    }]

});

var add_payment_mode_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Payment Mode";
$("#modal-add-payment-mode").fireModal({

    title: add_payment_mode_modal_title,
    body: $("#modal-add-payment-mode-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    $('#payment_mode_id').find('option').remove().end();
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'payments/get_payment_modes',
                        data: csrfName + '=' + csrfHash,
                        dataType: "json",
                        // quietMillis: 500,
                        success: function (data) {
                            csrfName = data.csrfName;
                            csrfHash = data.csrfHash;
                            delete data["csrfName"];
                            delete data["csrfHash"];
                            var $newOption = $("<option selected></option>").val('').text('Choose...')
                            $("#payment_mode_id").append($newOption).trigger('change');
                            $.each(data, function (i) {
                                $.each(data[i], function (key, val) {

                                    var $newOption = $("<option></option>").val(val.id).text(val.title)
                                    $("#payment_mode_id").append($newOption).trigger('change');
                                });
                            });
                            $('#payment_mode_id').val(result['payment_mode_id']).trigger('change');
                        }
                    });
                    form.stopProgress();
                    $('#fire-modal-41').modal('hide');
                    $('#fire-modal-4').modal('hide');
                    iziToast.success({
                        title: result['message'],
                        message: '',
                        position: 'topRight'
                    });
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();

                }
            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'addpaymentmodebtn2',
        handler: function (modal) { }
    }]
});

var edit_payment_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Edit Payment";
$(".modal-edit-payment").fireModal({
    size: 'modal-lg',
    title: edit_payment_modal_title,
    body: $("#modal-edit-payment-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    // location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'editpaymentbtn',
        handler: function (modal) { }
    }]

});

var add_article_group_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Article Group";
$("#modal-add-article-group").fireModal({
    size: 'modal-md',
    title: add_unit_modal_title,
    body: $("#modal-add-article-group-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'addunitbtn',
        handler: function (modal) { }
    }]
});

$(".modal-workspace-users").fireModal({
    size: 'modal-lg',
    title: 'Workspace Users',
    body: $("#modal-workspace-users-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
});

$(".modal-workspace-clients").fireModal({
    size: 'modal-lg',
    title: 'Workspace Clients',
    body: $("#modal-workspace-clients-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
});

$(".modal-meeting-users").fireModal({
    size: 'modal-lg',
    title: 'Meeting Users',
    body: $("#modal-meeting-users-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
});

$(".modal-meeting-clients").fireModal({
    size: 'modal-lg',
    title: 'Meeting Clients',
    body: $("#modal-meeting-clients-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
});

var add_meeting_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Meeting";
$("#modal-add-meeting").fireModal({
    size: 'modal-lg',
    title: add_meeting_modal_title,
    body: $("#modal-add-meeting-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'addnotebtn',
        handler: function (modal) { }
    }]
});

var edit_meeting_modal_title = ($('#modal-edit-meeting-title')) ? $('#modal-edit-meeting-title').html() : "Edit Meeting";
$(".modal-edit-meeting").fireModal({
    size: 'modal-lg',
    title: edit_meeting_modal_title,
    body: $("#modal-edit-meeting-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'addnotebtn',
        handler: function (modal) { }
    }]
});

$("#modal-add-work").fireModal({
    size: 'modal-lg',
    title: 'Create work',
    body: $("#modal-add-work-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: 'Create',
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'addnotebtn',
        handler: function (modal) { }
    }]
});

var add_lead_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Create Lead";
$("#modal-add-lead").fireModal({
    size: 'modal-lg',
    title: add_lead_modal_title,
    body: $("#modal-add-lead-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'adduserbtn',
        handler: function (modal) { }
    }]
});

var edit_lead_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Edit Lead";
$(".modal-edit-leads").fireModal({
    size: 'modal-lg',
    title: edit_lead_modal_title,
    body: $("#modal-edit-leads-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        // console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }]
});

$('#create_article').on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {
            $('#submit_button').html('Submit');
            $('#submit_button').attr('disabled', false);
            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            if (result['error'] == false) {
                location.reload();
            } else {
                iziToast.error({
                    title: result['message'],
                    message: '',
                    position: 'topRight'
                });
            }
        }
    });
});

$(".modal-edit-client-leads").fireModal({
    size: 'modal-lg',
    title: 'Convert to client',
    body: $("#modal-edit-client-leads-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }
            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        // console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }]
});

// Header colour change
((window, document, Math) => {
    const ctx = document.createElement("canvas").getContext("2d");
    const currentColor = {
        r: 0,
        g: 0,
        b: 0,
        h: 0,
        s: 0,
        v: 0,
        a: 1,
    };
    let picker,
        colorArea,
        colorAreaDims,
        colorMarker,
        colorPreview,
        colorValue,
        clearButton,
        hueSlider,
        hueMarker,
        alphaSlider,
        alphaMarker,
        currentEl,
        currentFormat,
        oldColor;

    // Default settings
    const settings = {
        el: "[data-coloris]",
        parent: null,
        theme: "light",
        wrap: true,
        margin: 2,
        format: "hex",
        formatToggle: false,
        swatches: [],
        alpha: true,
        clearButton: {
            show: false,
            label: "Clear",
        },
        a11y: {
            open: "Open color picker",
            close: "Close color picker",
            marker: "Saturation: {s}. Brightness: {v}.",
            hueSlider: "Hue slider",
            alphaSlider: "Opacity slider",
            input: "Color value field",
            format: "Color format",
            swatch: "Color swatch",
            instruction: "Saturation and brightness selector. Use up, down, left and right arrow keys to select.",
        },
    };

    /**
     * Configure the color picker.
     * @param {object} options Configuration options.
     */
    function configure(options) {
        if (typeof options !== "object") {
            return;
        }

        for (const key in options) {
            switch (key) {
                case "el":
                    bindFields(options.el);
                    if (options.wrap !== false) {
                        wrapFields(options.el);
                    }
                    break;
                case "parent":
                    settings.parent = document.querySelector(options.parent);
                    if (settings.parent) {
                        settings.parent.appendChild(picker);
                    }
                    break;
                case "theme":
                    picker.className = `clr-picker clr-${options.theme
                        .split("-")
                        .join(" clr-")}`;
                    break;
                case "margin":
                    options.margin *= 1;
                    settings.margin = !isNaN(options.margin) ?
                        options.margin :
                        settings.margin;
                    break;
                case "wrap":
                    if (options.el && options.wrap) {
                        wrapFields(options.el);
                    }
                    break;
                case "format":
                    settings.format = options.format;
                    break;
                case "formatToggle":
                    getEl("clr-format").style.display = options.formatToggle ?
                        "block" :
                        "none";
                    if (options.formatToggle) {
                        settings.format = "auto";
                    }
                    break;
                case "swatches":
                    if (Array.isArray(options.swatches)) {
                        const swatches = [];

                        options.swatches.forEach((swatch, i) => {
                            swatches.push(
                                `<button id="clr-swatch-${i}" aria-labelledby="clr-swatch-label clr-swatch-${i}" style="color: ${swatch};">${swatch}</button>`
                            );
                        });

                        if (swatches.length) {
                            getEl("clr-swatches").innerHTML = `<div>${swatches.join(
                                ""
                            )}</div>`;
                        }
                    }
                    break;
                case "alpha":
                    settings.alpha = !!options.alpha;
                    picker.setAttribute("data-alpha", settings.alpha);
                    break;
                case "clearButton":
                    let display = "none";

                    if (options.clearButton.show) {
                        display = "block";
                    }

                    if (options.clearButton.label) {
                        clearButton.innerHTML = options.clearButton.label;
                    }

                    clearButton.style.display = display;
                    break;
                case "a11y":
                    const labels = options.a11y;
                    let update = false;

                    if (typeof labels === "object") {
                        for (const label in labels) {
                            if (labels[label] && settings.a11y[label]) {
                                settings.a11y[label] = labels[label];
                                update = true;
                            }
                        }
                    }

                    if (update) {
                        const openLabel = getEl("clr-open-label");
                        const swatchLabel = getEl("clr-swatch-label");

                        openLabel.innerHTML = settings.a11y.open;
                        swatchLabel.innerHTML = settings.a11y.swatch;
                        colorPreview.setAttribute("aria-label", settings.a11y.close);
                        hueSlider.setAttribute("aria-label", settings.a11y.hueSlider);
                        alphaSlider.setAttribute("aria-label", settings.a11y.alphaSlider);
                        colorValue.setAttribute("aria-label", settings.a11y.input);
                        colorArea.setAttribute("aria-label", settings.a11y.instruction);
                    }
            }
        }
    }

    /**
     * Bind the color picker to input fields that match the selector.
     * @param {string} selector One or more selectors pointing to input fields.
     */
    function bindFields(selector) {
        // Show the color picker on click on the input fields that match the selector
        addListener(document, "click", selector, (event) => {
            const parent = settings.parent;
            const coords = event.target.getBoundingClientRect();
            const scrollY = window.scrollY;
            let reposition = {
                left: false,
                top: false,
            };
            let offset = {
                x: 0,
                y: 0,
            };
            let left = coords.x;
            let top = scrollY + coords.y + coords.height + settings.margin;

            currentEl = event.target;
            oldColor = currentEl.value;
            currentFormat = getColorFormatFromStr(oldColor);
            picker.classList.add("clr-open");

            const pickerWidth = picker.offsetWidth;
            const pickerHeight = picker.offsetHeight;

            // If the color picker is inside a custom container
            // set the position relative to it
            if (parent) {
                const style = window.getComputedStyle(parent);
                const marginTop = parseFloat(style.marginTop);
                const borderTop = parseFloat(style.borderTopWidth);

                offset = parent.getBoundingClientRect();
                offset.y += borderTop + scrollY;
                left -= offset.x;
                top -= offset.y;

                if (left + pickerWidth > parent.clientWidth) {
                    left += coords.width - pickerWidth;
                    reposition.left = true;
                }

                if (top + pickerHeight > parent.clientHeight - marginTop) {
                    top -= coords.height + pickerHeight + settings.margin * 2;
                    reposition.top = true;
                }

                top += parent.scrollTop;

                // Otherwise set the position relative to the whole document
            } else {
                if (left + pickerWidth > document.documentElement.clientWidth) {
                    left += coords.width - pickerWidth;
                    reposition.left = true;
                }

                if (
                    top + pickerHeight - scrollY >
                    document.documentElement.clientHeight
                ) {
                    top = scrollY + coords.y - pickerHeight - settings.margin;
                    reposition.top = true;
                }
            }

            picker.classList.toggle("clr-left", reposition.left);
            picker.classList.toggle("clr-top", reposition.top);
            picker.style.left = `${left}px`;
            picker.style.top = `${top}px`;
            colorAreaDims = {
                width: colorArea.offsetWidth,
                height: colorArea.offsetHeight,
                x: picker.offsetLeft + colorArea.offsetLeft + offset.x,
                y: picker.offsetTop + colorArea.offsetTop + offset.y,
            };

            setColorFromStr(oldColor);
            colorValue.focus({
                preventScroll: true,
            });
        });

        // Update the color preview of the input fields that match the selector
        addListener(document, "input", selector, (event) => {
            const parent = event.target.parentNode;

            // Only update the preview if the field has been previously wrapped
            if (parent.classList.contains("clr-field")) {
                parent.style.color = event.target.value;
            }
        });
    }

    /**
     * Wrap the linked input fields in a div that adds a color preview.
     * @param {string} selector One or more selectors pointing to input fields.
     */
    function wrapFields(selector) {
        document.querySelectorAll(selector).forEach((field) => {
            const parentNode = field.parentNode;

            if (!parentNode.classList.contains("clr-field")) {
                const wrapper = document.createElement("div");

                wrapper.innerHTML = `<button aria-labelledby="clr-open-label"></button>`;
                parentNode.insertBefore(wrapper, field);
                wrapper.setAttribute("class", "clr-field");
                wrapper.style.color = field.value;
                wrapper.appendChild(field);
            }
        });
    }

    /**
     * Close the color picker.
     * @param {boolean} [revert] If true, revert the color to the original value.
     */
    function closePicker(revert) {
        if (currentEl) {
            // Revert the color to the original value if needed
            if (revert && oldColor !== currentEl.value) {
                currentEl.value = oldColor;

                // Trigger an "input" event to force update the thumbnail next to the input field
                currentEl.dispatchEvent(
                    new Event("input", {
                        bubbles: true,
                    })
                );
            }

            if (oldColor !== currentEl.value) {
                currentEl.dispatchEvent(
                    new Event("change", {
                        bubbles: true,
                    })
                );
            }

            picker.classList.remove("clr-open");
            currentEl.focus({
                preventScroll: true,
            });
            currentEl = null;
        }
    }

    /**
     * Set the active color from a string.
     * @param {string} str String representing a color.
     */
    function setColorFromStr(str) {
        const rgba = strToRGBA(str);
        const hsva = RGBAtoHSVA(rgba);

        updateMarkerA11yLabel(hsva.s, hsva.v);
        updateColor(rgba, hsva);

        // Update the UI
        hueSlider.value = hsva.h;
        picker.style.color = `hsl(${hsva.h}, 100%, 50%)`;
        hueMarker.style.left = `${(hsva.h / 360) * 100}%`;

        colorMarker.style.left = `${(colorAreaDims.width * hsva.s) / 100}px`;
        colorMarker.style.top = `${colorAreaDims.height - (colorAreaDims.height * hsva.v) / 100
            }px`;

        alphaSlider.value = hsva.a * 100;
        alphaMarker.style.left = `${hsva.a * 100}%`;
    }

    /**
     * Guess the color format from a string.
     * @param {string} str String representing a color.
     * @return {string} The color format.
     */
    function getColorFormatFromStr(str) {
        const format = str.substring(0, 3).toLowerCase();

        if (format === "rgb" || format === "hsl") {
            return format;
        }

        return "hex";
    }

    /**
     * Copy the active color to the linked input field.
     * @param {number} [color] Color value to override the active color.
     */
    function pickColor(color) {
        if (currentEl) {
            currentEl.value = color !== undefined ? color : colorValue.value;
            currentEl.dispatchEvent(
                new Event("input", {
                    bubbles: true,
                })
            );
        }
    }

    /**
     * Set the active color based on a specific point in the color gradient.
     * @param {number} x Left position.
     * @param {number} y Top position.
     */
    function setColorAtPosition(x, y) {
        const hsva = {
            h: hueSlider.value * 1,
            s: (x / colorAreaDims.width) * 100,
            v: 100 - (y / colorAreaDims.height) * 100,
            a: alphaSlider.value / 100,
        };
        const rgba = HSVAtoRGBA(hsva);

        updateMarkerA11yLabel(hsva.s, hsva.v);
        updateColor(rgba, hsva);
        pickColor();
    }

    /**
     * Update the color marker's accessibility label.
     * @param {number} saturation
     * @param {number} value
     */
    function updateMarkerA11yLabel(saturation, value) {
        let label = settings.a11y.marker;

        saturation = saturation.toFixed(1) * 1;
        value = value.toFixed(1) * 1;
        label = label.replace("{s}", saturation);
        label = label.replace("{v}", value);
        colorMarker.setAttribute("aria-label", label);
    }

    //
    /**
     * Get the pageX and pageY positions of the pointer.
     * @param {object} event The MouseEvent or TouchEvent object.
     * @return {object} The pageX and pageY positions.
     */
    function getPointerPosition(event) {
        return {
            pageX: event.changedTouches ? event.changedTouches[0].pageX : event.pageX,
            pageY: event.changedTouches ? event.changedTouches[0].pageY : event.pageY,
        };
    }

    /**
     * Move the color marker when dragged.
     * @param {object} event The MouseEvent object.
     */
    function moveMarker(event) {
        const pointer = getPointerPosition(event);
        let x = pointer.pageX - colorAreaDims.x;
        let y = pointer.pageY - colorAreaDims.y;

        if (settings.parent) {
            y += settings.parent.scrollTop;
        }

        x = x < 0 ? 0 : x > colorAreaDims.width ? colorAreaDims.width : x;
        y = y < 0 ? 0 : y > colorAreaDims.height ? colorAreaDims.height : y;

        colorMarker.style.left = `${x}px`;
        colorMarker.style.top = `${y}px`;

        setColorAtPosition(x, y);

        // Prevent scrolling while dragging the marker
        event.preventDefault();
        event.stopPropagation();
    }

    /**
     * Move the color marker when the arrow keys are pressed.
     * @param {number} offsetX The horizontal amount to move.
     * * @param {number} offsetY The vertical amount to move.
     */
    function moveMarkerOnKeydown(offsetX, offsetY) {
        const x = colorMarker.style.left.replace("px", "") * 1 + offsetX;
        const y = colorMarker.style.top.replace("px", "") * 1 + offsetY;

        colorMarker.style.left = `${x}px`;
        colorMarker.style.top = `${y}px`;

        setColorAtPosition(x, y);
    }

    /**
     * Update the color picker's input field and preview thumb.
     * @param {Object} rgba Red, green, blue and alpha values.
     * @param {Object} [hsva] Hue, saturation, value and alpha values.
     */
    function updateColor(rgba = {}, hsva = {}) {
        let format = settings.format;

        for (const key in rgba) {
            currentColor[key] = rgba[key];
        }

        for (const key in hsva) {
            currentColor[key] = hsva[key];
        }

        const hex = RGBAToHex(currentColor);
        const opaqueHex = hex.substring(0, 7);

        colorMarker.style.color = opaqueHex;
        alphaMarker.parentNode.style.color = opaqueHex;
        alphaMarker.style.color = hex;
        colorPreview.style.color = hex;

        // Force repaint the color and alpha gradients as a workaround for a Google Chrome bug
        colorArea.style.display = "none";
        colorArea.offsetHeight;
        colorArea.style.display = "";
        alphaMarker.nextElementSibling.style.display = "none";
        alphaMarker.nextElementSibling.offsetHeight;
        alphaMarker.nextElementSibling.style.display = "";

        if (format === "mixed") {
            format = currentColor.a === 1 ? "hex" : "rgb";
        } else if (format === "auto") {
            format = currentFormat;
        }

        switch (format) {
            case "hex":
                colorValue.value = hex;
                break;
            case "rgb":
                colorValue.value = RGBAToStr(currentColor);
                break;
            case "hsl":
                colorValue.value = HSLAToStr(HSVAtoHSLA(currentColor));
                break;
        }

        // Select the current format in the format switcher
        document.querySelector(`.clr-format [value="${format}"]`).checked = true;
    }

    /**
     * Set the hue when its slider is moved.
     */
    function setHue() {
        const hue = hueSlider.value * 1;
        const x = colorMarker.style.left.replace("px", "") * 1;
        const y = colorMarker.style.top.replace("px", "") * 1;

        picker.style.color = `hsl(${hue}, 100%, 50%)`;
        hueMarker.style.left = `${(hue / 360) * 100}%`;

        setColorAtPosition(x, y);
    }

    /**
     * Set the alpha when its slider is moved.
     */
    function setAlpha() {
        const alpha = alphaSlider.value / 100;

        alphaMarker.style.left = `${alpha * 100}%`;
        updateColor({
            a: alpha,
        });
        pickColor();
    }

    /**
     * Convert HSVA to RGBA.
     * @param {object} hsva Hue, saturation, value and alpha values.
     * @return {object} Red, green, blue and alpha values.
     */
    function HSVAtoRGBA(hsva) {
        const saturation = hsva.s / 100;
        const value = hsva.v / 100;
        let chroma = saturation * value;
        let hueBy60 = hsva.h / 60;
        let x = chroma * (1 - Math.abs((hueBy60 % 2) - 1));
        let m = value - chroma;

        chroma = chroma + m;
        x = x + m;
        m = m;

        const index = Math.floor(hueBy60) % 6;
        const red = [chroma, x, m, m, x, chroma][index];
        const green = [x, chroma, chroma, x, m, m][index];
        const blue = [m, m, x, chroma, chroma, x][index];

        return {
            r: Math.round(red * 255),
            g: Math.round(green * 255),
            b: Math.round(blue * 255),
            a: hsva.a,
        };
    }

    /**
     * Convert HSVA to HSLA.
     * @param {object} hsva Hue, saturation, value and alpha values.
     * @return {object} Hue, saturation, lightness and alpha values.
     */
    function HSVAtoHSLA(hsva) {
        const value = hsva.v / 100;
        const lightness = value * (1 - hsva.s / 100 / 2);
        let saturation;

        if (lightness > 0 && lightness < 1) {
            saturation = Math.round(
                ((value - lightness) / Math.min(lightness, 1 - lightness)) * 100
            );
        }

        return {
            h: hsva.h,
            s: saturation || 0,
            l: Math.round(lightness * 100),
            a: hsva.a,
        };
    }

    /**
     * Convert RGBA to HSVA.
     * @param {object} rgba Red, green, blue and alpha values.
     * @return {object} Hue, saturation, value and alpha values.
     */
    function RGBAtoHSVA(rgba) {
        const red = rgba.r / 255;
        const green = rgba.g / 255;
        const blue = rgba.b / 255;
        const xmax = Math.max(red, green, blue);
        const xmin = Math.min(red, green, blue);
        const chroma = xmax - xmin;
        const value = xmax;
        let hue = 0;
        let saturation = 0;

        if (chroma) {
            if (xmax === red) {
                hue = (green - blue) / chroma;
            }
            if (xmax === green) {
                hue = 2 + (blue - red) / chroma;
            }
            if (xmax === blue) {
                hue = 4 + (red - green) / chroma;
            }
            if (xmax) {
                saturation = chroma / xmax;
            }
        }

        hue = Math.floor(hue * 60);

        return {
            h: hue < 0 ? hue + 360 : hue,
            s: Math.round(saturation * 100),
            v: Math.round(value * 100),
            a: rgba.a,
        };
    }

    /**
     * Parse a string to RGBA.
     * @param {string} str String representing a color.
     * @return {object} Red, green, blue and alpha values.
     */
    function strToRGBA(str) {
        const regex =
            /^((rgba)|rgb)[\D]+([\d.]+)[\D]+([\d.]+)[\D]+([\d.]+)[\D]*?([\d.]+|$)/i;
        let match, rgba;

        // Default to black for invalid color strings
        ctx.fillStyle = "#000";

        // Use canvas to convert the string to a valid color string
        ctx.fillStyle = str;
        match = regex.exec(ctx.fillStyle);

        if (match) {
            rgba = {
                r: match[3] * 1,
                g: match[4] * 1,
                b: match[5] * 1,
                a: match[6] * 1,
            };
        } else {
            match = ctx.fillStyle
                .replace("#", "")
                .match(/.{2}/g)
                .map((h) => parseInt(h, 16));
            rgba = {
                r: match[0],
                g: match[1],
                b: match[2],
                a: 1,
            };
        }

        return rgba;
    }

    /**
     * Convert RGBA to Hex.
     * @param {object} rgba Red, green, blue and alpha values.
     * @return {string} Hex color string.
     */
    function RGBAToHex(rgba) {
        let R = rgba.r.toString(16);
        let G = rgba.g.toString(16);
        let B = rgba.b.toString(16);
        let A = "";

        if (rgba.r < 16) {
            R = "0" + R;
        }

        if (rgba.g < 16) {
            G = "0" + G;
        }

        if (rgba.b < 16) {
            B = "0" + B;
        }

        if (settings.alpha && rgba.a < 1) {
            const alpha = (rgba.a * 255) | 0;
            A = alpha.toString(16);

            if (alpha < 16) {
                A = "0" + A;
            }
        }

        return "#" + R + G + B + A;
    }

    /**
     * Convert RGBA values to a CSS rgb/rgba string.
     * @param {object} rgba Red, green, blue and alpha values.
     * @return {string} CSS color string.
     */
    function RGBAToStr(rgba) {
        if (!settings.alpha || rgba.a === 1) {
            return `rgb(${rgba.r}, ${rgba.g}, ${rgba.b})`;
        } else {
            return `rgba(${rgba.r}, ${rgba.g}, ${rgba.b}, ${rgba.a})`;
        }
    }

    /**
     * Convert HSLA values to a CSS hsl/hsla string.
     * @param {object} hsla Hue, saturation, lightness and alpha values.
     * @return {string} CSS color string.
     */
    function HSLAToStr(hsla) {
        if (!settings.alpha || hsla.a === 1) {
            return `hsl(${hsla.h}, ${hsla.s}%, ${hsla.l}%)`;
        } else {
            return `hsla(${hsla.h}, ${hsla.s}%, ${hsla.l}%, ${hsla.a})`;
        }
    }

    /**
     * Init the color picker.
     */
    function init() {
        // Render the UI
        picker = document.createElement("div");
        picker.setAttribute("id", "clr-picker");
        picker.className = "clr-picker";
        picker.innerHTML =
            `<input id="clr-color-value" class="clr-color" type="text" value="" aria-label="${settings.a11y.input}">` +
            `<div id="clr-color-area" class="clr-gradient" role="application" aria-label="${settings.a11y.instruction}">` +
            '<div id="clr-color-marker" class="clr-marker" tabindex="0"></div>' +
            "</div>" +
            '<div class="clr-hue">' +
            `<input id="clr-hue-slider" type="range" min="0" max="360" step="1" aria-label="${settings.a11y.hueSlider}">` +
            '<div id="clr-hue-marker"></div>' +
            "</div>" +
            '<div class="clr-alpha">' +
            `<input id="clr-alpha-slider" type="range" min="0" max="100" step="1" aria-label="${settings.a11y.alphaSlider}">` +
            '<div id="clr-alpha-marker"></div>' +
            "<span></span>" +
            "</div>" +
            '<div id="clr-format" class="clr-format">' +
            '<fieldset class="clr-segmented">' +
            `<legend>${settings.a11y.format}</legend>` +
            '<input id="clr-f1" type="radio" name="clr-format" value="hex">' +
            '<label for="clr-f1">Hex</label>' +
            '<input id="clr-f2" type="radio" name="clr-format" value="rgb">' +
            '<label for="clr-f2">RGB</label>' +
            '<input id="clr-f3" type="radio" name="clr-format" value="hsl">' +
            '<label for="clr-f3">HSL</label>' +
            "<span></span>" +
            "</fieldset>" +
            "</div>" +
            '<div id="clr-swatches" class="clr-swatches"></div>' +
            `<button id="clr-clear" class="clr-clear">${settings.clearButton.label}</button>` +
            `<button id="clr-color-preview" class="clr-preview" aria-label="${settings.a11y.close}"></button>` +
            `<span id="clr-open-label" hidden>${settings.a11y.open}</span>` +
            `<span id="clr-swatch-label" hidden>${settings.a11y.swatch}</span>`;

        // Append the color picker to the DOM
        document.body.appendChild(picker);

        // Reference the UI elements
        colorArea = getEl("clr-color-area");
        colorMarker = getEl("clr-color-marker");
        clearButton = getEl("clr-clear");
        colorPreview = getEl("clr-color-preview");
        colorValue = getEl("clr-color-value");
        hueSlider = getEl("clr-hue-slider");
        hueMarker = getEl("clr-hue-marker");
        alphaSlider = getEl("clr-alpha-slider");
        alphaMarker = getEl("clr-alpha-marker");

        // Bind the picker to the default selector
        bindFields(settings.el);
        wrapFields(settings.el);

        addListener(picker, "mousedown", (event) => {
            picker.classList.remove("clr-keyboard-nav");
            event.stopPropagation();
        });

        addListener(colorArea, "mousedown", (event) => {
            addListener(document, "mousemove", moveMarker);
        });

        addListener(colorArea, "touchstart", (event) => {
            document.addEventListener("touchmove", moveMarker, {
                passive: false,
            });
        });

        addListener(colorMarker, "mousedown", (event) => {
            addListener(document, "mousemove", moveMarker);
        });

        addListener(colorMarker, "touchstart", (event) => {
            document.addEventListener("touchmove", moveMarker, {
                passive: false,
            });
        });

        addListener(colorValue, "change", (event) => {
            setColorFromStr(colorValue.value);
            pickColor();
        });

        addListener(clearButton, "click", (event) => {
            pickColor("");
            closePicker();
        });

        addListener(colorPreview, "click", (event) => {
            pickColor();
            closePicker();
        });

        addListener(document, "click", ".clr-format input", (event) => {
            currentFormat = event.target.value;
            updateColor();
            pickColor();
        });

        addListener(picker, "click", ".clr-swatches button", (event) => {
            setColorFromStr(event.target.textContent);
            pickColor();
        });

        addListener(document, "mouseup", (event) => {
            document.removeEventListener("mousemove", moveMarker);
        });

        addListener(document, "touchend", (event) => {
            document.removeEventListener("touchmove", moveMarker);
        });

        addListener(document, "mousedown", (event) => {
            picker.classList.remove("clr-keyboard-nav");
            closePicker();
        });

        addListener(document, "keydown", (event) => {
            if (event.key === "Escape") {
                closePicker(true);
            } else if (event.key === "Tab") {
                picker.classList.add("clr-keyboard-nav");
            }
        });

        addListener(document, "click", ".clr-field button", (event) => {
            event.target.nextElementSibling.dispatchEvent(
                new Event("click", {
                    bubbles: true,
                })
            );
        });

        addListener(colorMarker, "keydown", (event) => {
            const movements = {
                ArrowUp: [0, -1],
                ArrowDown: [0, 1],
                ArrowLeft: [-1, 0],
                ArrowRight: [1, 0],
            };

            if (Object.keys(movements).indexOf(event.key) !== -1) {
                moveMarkerOnKeydown(...movements[event.key]);
                event.preventDefault();
            }
        });

        addListener(colorArea, "click", moveMarker);
        addListener(hueSlider, "input", setHue);
        addListener(alphaSlider, "input", setAlpha);
    }

    /**
     * Shortcut for getElementById to optimize the minified JS.
     * @param {string} id The element id.
     * @return {object} The DOM element with the provided id.
     */
    function getEl(id) {
        return document.getElementById(id);
    }

    /**
     * Shortcut for addEventListener to optimize the minified JS.
     * @param {object} context The context to which the listener is attached.
     * @param {string} type Event type.
     * @param {(string|function)} selector Event target if delegation is used, event handler if not.
     * @param {function} [fn] Event handler if delegation is used.
     */
    function addListener(context, type, selector, fn) {
        const matches =
            Element.prototype.matches || Element.prototype.msMatchesSelector;

        // Delegate event to the target of the selector
        if (typeof selector === "string") {
            context.addEventListener(type, (event) => {
                if (matches.call(event.target, selector)) {
                    fn.call(event.target, event);
                }
            });

            // If the selector is not a string then it's a function
            // in which case we need regular event listener
        } else {
            fn = selector;
            context.addEventListener(type, fn);
        }
    }

    /**
     * Call a function only when the DOM is ready.
     * @param {function} fn The function to call.
     * @param {array} args Arguments to pass to the function.
     */
    function DOMReady(fn, args) {
        args = args !== undefined ? args : [];

        if (document.readyState !== "loading") {
            fn(...args);
        } else {
            document.addEventListener("DOMContentLoaded", () => {
                fn(...args);
            });
        }
    }

    // Polyfill for Nodelist.forEach
    if (
        NodeList !== undefined &&
        NodeList.prototype &&
        !NodeList.prototype.forEach
    ) {
        NodeList.prototype.forEach = Array.prototype.forEach;
    }

    // Expose the color picker to the global scope
    window.Coloris = (() => {
        const methods = {
            set: configure,
            wrap: wrapFields,
            close: closePicker,
        };

        function Coloris(options) {
            DOMReady(() => {
                if (options) {
                    if (typeof options === "string") {
                        bindFields(options);
                    } else {
                        configure(options);
                    }
                }
            });
        }

        for (const key in methods) {
            Coloris[key] = (...args) => {
                DOMReady(methods[key], args);
            };
        }

        return Coloris;
    })();

    // Init the color picker when the DOM is ready
    DOMReady(init);
})(window, document, Math);
Coloris({
    el: ".coloris",
});

$('#update_members_permissions_form').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#update_members_permissions_form").validate().form()) {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            beforeSend: function () { $('#submit_button').html('Please Wait..'); $('#submit_button').attr('disabled', true); },
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                $('#submit_button').html('Submit'); $('#submit_button').attr('disabled', false);
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    location.reload();
                } else {
                    iziToast.error({
                        title: result['message'],
                        message: '',
                        position: 'topRight'
                    });
                }

            }
        });
    }
})
$('#update_clients_permissions_form').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#update_clients_permissions_form").validate().form()) {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            beforeSend: function () { $('#submit_button_update').html('Please Wait..'); $('#submit_button_update').attr('disabled', true); },
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                $('#submit_button_update').html('Submit'); $('#submit_button_update').attr('disabled', false);
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    location.reload();
                } else {
                    iziToast.error({
                        title: result['message'],
                        message: '',
                        position: 'topRight'
                    });
                }

            }
        });
    }
})
function un_fav(id) {
    var test = document.getElementById(id);
    if ($(test).hasClass('fas')) {
        $(test).removeClass('fas');
        var test = document.getElementById(id);
        test.classList.add('far');
        var input_body = {
            [csrfName]: csrfHash,
            'project_id': id
        };
        $.ajax({
            type: "POST",
            data: input_body,
            url: base_url + 'projects/remove_favorite',
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    location.reload();
                } else {
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }
            }
        });
    } else {
        alert('well you can not remove the this project from favorite list as it was not favored');
    }
}
function fav(id) {
    var test = document.getElementById(id);
    test.classList.add('fas');
    $(test).removeClass('far');
    var input_body = {
        [csrfName]: csrfHash,
        'project_id': id
    };
    $.ajax({
        type: "POST",
        data: input_body,
        url: base_url + '/projects/add_favorite',
        dataType: "json",
        success: function (result) {
            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            if (result['error'] == false) {
                location.reload();
            } else {
                modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                modal.find('.alert-danger').delay(4000).fadeOut();
            }
        }
    });
}

function test_net(e) {
    var id = $(e).attr('data-user_id');
    console.log(id);
    var input_body = {
        [csrfName]: csrfHash,
        'id': id
    };

    $('#id').val(id);

    // e.preventDefault();
    $.ajax({
        type: "POST",
        url: base_url + "users/get_user_permissions",
        data: input_body,
        dataType: "json",
        success: function (response) {
            csrfName = response['csrfName'];
            csrfHash = response['csrfHash'];
            if (response['type'] == 1) {
                var permissions = response['data'].permissions;
                setPermission(permissions)
            } else {
                console.log(response);
                var permissions = '';
                console.log(response['user_type']);
                if (response['user_type'] == 'client') {
                    permissions = response['data'].permissions[2].client_permissions;
                    setPermission(permissions)
                } else {
                    permissions = response['data'].permissions[1].member_permissions;
                    console.log(permissions);
                    setPermission(permissions)

                }

            }

        }
    });
}

function setPermission(permissions) {
    var permissions = JSON.parse(permissions);

    // for titles

    // for values
    $('#exampleModal').on('shown.bs.modal', function (e) {
        Object.entries(permissions).forEach((p) => {
            console.log(p);
            if (p[1].create == 'on') {
                var s = $(`[name='permissions[${p[0]}][create]']`);
                $(s).attr('checked', true);
            } else {
                var s = $(`[name='permissions[${p[0]}][create]']`);
                $(s).attr('checked', false);
            }

            if (p[1].read == 'on') {
                var s = $(`[name='permissions[${p[0]}][read]']`);
                $(s).attr('checked', true);
            } else {
                var s = $(`[name='permissions[${p[0]}][read]']`);
                $(s).attr('checked', false);
            }

            if (p[1].update == 'on') {
                var s = $(`[name='permissions[${p[0]}][update]']`);
                $(s).attr('checked', true);
            } else {
                var s = $(`[name='permissions[${p[0]}][update]']`);
                $(s).attr('checked', false);
            }

            if (p[1].delete == 'on') {
                var s = $(`[name='permissions[${p[0]}][delete]']`);
                $(s).attr('checked', true);
            } else {
                var s = $(`[name='permissions[${p[0]}][delete]']`);
                $(s).attr('checked', false);
            }
        })
    })
}

$('#user_permission_module').submit(function (e) {
    e.preventDefault();
    console.log('test submit');

    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#user_permission_module").validate().form()) {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            beforeSend: function () {
                $('#submit_button_update').html('Please Wait..');
                $('#submit_button_update').attr('disabled', true);
            },
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    iziToast.success({
                        title: result['message'],
                        message: '',
                        position: 'topRight'
                    });
                    $('#submit_button_update').html('Save changes');
                    $('#submit_button_update').attr('disabled', false);
                } else {
                    iziToast.error({
                        title: result['message'],
                        message: '',
                        position: 'topRight'
                    });
                }
            }
        });
    }


});

function setUrlParameter(url, paramName, paramValue) {
    paramName = paramName.replace(/\s+/g, '-');
    if (paramValue == null || paramValue == '') {
        return url.replace(new RegExp('[?&]' + paramName + '=[^&#]*(#.*)?$'), '$1')
            .replace(new RegExp('([?&])' + paramName + '=[^&]*&'), '$1');
    }
    var pattern = new RegExp('\\b(' + paramName + '=).*?(&|#|$)');
    if (url.search(pattern) >= 0) {
        return url.replace(pattern, '$1' + paramValue + '$2');
    }
    url = url.replace(/[?#]$/, '');
    return url + (url.indexOf('?') > 0 ? '&' : '?') + paramName + '=' + paramValue;
}
$('#project_sort_by').on('change', function (e) {
    var sort = $(this).val();
    location.href = setUrlParameter(location.href, 'sort', sort);
});

if ($(window).width() <= '576.92') {
    $('.mobile_chat').addClass('d-none');
    $('.mobile_toggle').removeClass('d-none');
} else {
    $('.mobile_toggle').addClass('d-none');
}

// mobile chat view
"use strict";
$('.js-menu').on('click', () => {
    $('.js-menu').toggleClass('active');
    $('.js-nav').toggleClass('open');
    $('.js-nav__list').toggleClass('show');
});

var contracts_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Contracts";
$("#modal-add-contracts").fireModal({
    size: 'modal-lg',
    title: contracts_modal_title,
    body: $("#modal-add-contracts-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }]
});

var contracts_edit_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Edit Contracts";
$(".modal-edit-contracts").fireModal({
    size: 'modal-lg',
    title: contracts_edit_modal_title,
    body: $("#modal-edit-contracts-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,

    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);
        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        // console.log(form)
    },
    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }]
});
var contracts_type_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Contracts Type";
$("#modal-add-contracts-type").fireModal({
    size: 'modal-md',
    title: contracts_type_modal_title,
    body: $("#modal-add-contracts-type-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        // let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        var is_reload = formData.get("is_reload");
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    if (is_reload == 1) {
                        location.reload();
                    } else {
                        $('#contract_type_id').find('option').remove().end();
                        $.ajax({
                            type: 'POST',
                            url: base_url + 'contracts/get_contracts_type',
                            data: csrfName + '=' + csrfHash,
                            dataType: "json",
                            // quietMillis: 500,
                            success: function (data) {
                                csrfName = data["csrfName"];
                                csrfHash = data["csrfHash"];
                                delete data["csrfName"];
                                delete data["csrfHash"];
                                var $newOption = $("<option selected></option>").val('').text('Choose...')

                                $("#contract_type_id").append($newOption).trigger('change');
                                $.each(data, function (i) {
                                    $.each(data[i], function (key, val) {

                                        var $newOption = $("<option></option>").val(val.id).text(val.title)

                                        $("#contract_type_id").append($newOption).trigger('change');
                                    });
                                });
                                $('#contract_type_id').val(result['contract_type_id']).trigger('change');
                            }

                        });

                        form.stopProgress();
                        iziToast.success({
                            title: result['message'],
                            message: '',
                            position: 'topRight'
                        });
                        location.reload();
                    }
                } else {
                    form.stopProgress();
                    // $('#fire-modal-4').modal('hide');
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'additembtn',
        handler: function (modal) { }
    }]

});
var contracts_edit_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Edit Contracts Type";
$(".modal-edit-contracts-type").fireModal({

    size: 'modal-md',
    title: contracts_edit_modal_title,
    body: $("#modal-edit-contracts-type-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'editcontracttypebtn',
        handler: function (modal) { }
    }]

});

$('#sign_form').on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#sign_form").validate().form()) {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                $('#btnSaveSign').html('Submit');
                $('#btnSaveSign').attr('disabled', false);
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    location.reload();
                } else {
                    iziToast.error({
                        title: result['message'],
                        message: '',
                        position: 'topRight'
                    });
                }
            }
        });
    }
});
$('#client_sign_form').on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#client_sign_form").validate().form()) {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                $('#btnClientSaveSign').html('Submit');
                $('#btnClientSaveSign').attr('disabled', false);
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    location.reload();
                } else {
                    iziToast.error({
                        title: result['message'],
                        message: '',
                        position: 'topRight'
                    });
                }
            }
        });
    }
});

var add_allowance_modal_title = ($('#modal-allowance-title')) ? $('#modal-allowance-title').html() : "Add Allowance";
$("#modal-add-allowance").fireModal({
    size: 'modal-md',
    title: add_allowance_modal_title,
    body: $("#modal-add-allowance-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        var formData = new FormData(this);
        var is_reload = formData.get("is_reload");
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    if (is_reload == 1) {
                        location.reload();
                    } else {
                        $('#allowance').find('option').remove().end();
                        $.ajax({
                            type: 'POST',
                            url: base_url + 'payslip/get_allowance',
                            data: csrfName + '=' + csrfHash,
                            dataType: "json",
                            // quietMillis: 500,
                            success: function (data) {
                                csrfName = data["csrfName"];
                                csrfHash = data["csrfHash"];
                                delete data["csrfName"];
                                delete data["csrfHash"];
                                var $newOption = $("<option selected></option>").val('').text('Choose...')

                                $("#allowance").append($newOption).trigger('change');
                                $.each(data, function (i) {
                                    $.each(data[i], function (key, val) {

                                        var $newOption = $("<option></option>").val(val.id).text(val.name)

                                        $("#allowance").append($newOption).trigger('change');
                                    });
                                });
                                $('#allowance').val(result['allowance']).trigger('change');
                            }
                        });
                        form.stopProgress();
                        location.reload();
                        iziToast.success({
                            title: result['message'],
                            message: '',
                            position: 'topRight'
                        });
                    }

                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
    },

    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'addallowancebtn',
        handler: function (modal) { }
    }]

});

var add_deduction_modal_title = ($('#modal-deduction-title')) ? $('#modal-deduction-title').html() : "Add Deduction";
$("#modal-add-deduction").fireModal({
    size: 'modal-md',
    title: add_deduction_modal_title,
    body: $("#modal-add-deduction-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        var formData = new FormData(this);
        var is_reload = formData.get("is_reload");
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    if (is_reload == 1) {
                        location.reload();
                    } else {
                        $('#deduction_id').find('option').remove().end();
                        $.ajax({
                            type: 'POST',
                            url: base_url + 'payslip/get_deduction',
                            data: csrfName + '=' + csrfHash,
                            dataType: "json",
                            // quietMillis: 500,
                            success: function (data) {
                                csrfName = data["csrfName"];
                                csrfHash = data["csrfHash"];
                                delete data["csrfName"];
                                delete data["csrfHash"];
                                var $newOption = $("<option selected></option>").val('').text('Choose...')

                                $("#deduction_id").append($newOption).trigger('change');
                                $.each(data, function (i) {
                                    $.each(data[i], function (key, val) {

                                        var $newOption = $("<option></option>").val(val.id).text(val.name)

                                        $("#deduction_id").append($newOption).trigger('change');
                                    });
                                });
                                $('#deduction_id').val(result['deduction_id']).trigger('change');
                            }
                        });
                        form.stopProgress();
                        location.reload();
                        $('#fire-modal-41').modal('hide');
                        $('#fire-modal-4').modal('hide');
                        iziToast.success({
                            title: result['message'],
                            message: '',
                            position: 'topRight'
                        });
                    }

                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
    },

    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'adddeductionbtn',
        handler: function (modal) { }
    }]

});

var allowance_edit_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Edit Allowance";
$(".modal-edit-allowance").fireModal({

    size: 'modal-md',
    title: allowance_edit_modal_title,
    body: $("#modal-edit-allowance-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'editallowancebtn',
        handler: function (modal) { }
    }]

});

var deduction_edit_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Edit Deduction";
$(".modal-edit-deduction").fireModal({

    size: 'modal-md',
    title: deduction_edit_modal_title,
    body: $("#modal-edit-deduction-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'editallowancebtn',
        handler: function (modal) { }
    }]

});

$('#create_bulk_project_update').on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {
            $('#submit_button').html('Submit');
            $('#submit_button').attr('disabled', false);
            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            if (result['error'] == false) {
                location.reload();
                iziToast.success({
                    title: result['message'],
                    message: '',
                    position: 'topRight'
                });
            } else {
                iziToast.error({
                    title: result['message'],
                    message: '',
                    position: 'topRight'
                });
            }
        }
    });
});

$('#create_bulk_task_update').on("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {
            $('#submit_button').html('Submit');
            $('#submit_button').attr('disabled', false);
            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            if (result['error'] == false) {
                location.reload();
                iziToast.success({
                    title: result['message'],
                    message: '',
                    position: 'topRight'
                });
            } else {
                iziToast.error({
                    title: result['message'],
                    message: '',
                    position: 'topRight'
                });
            }
        }
    });
});

$('#add_project_task').submit(function (e) {
    e.preventDefault();
    console.log('test submit');

    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#add_project_task").validate().form()) {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            beforeSend: function () {
                $('#submit_button').html('Please Wait..');
                $('#submit_button').attr('disabled', true);
            },
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    iziToast.success({
                        title: result['message'],
                        message: '',
                        position: 'topRight'
                    });
                    $('#submit_button').html('Save');
                    $('#submit_button').attr('disabled', false);
                } else {
                    iziToast.error({
                        title: result['message'],
                        message: '',
                        position: 'topRight'
                    });
                }
            }
        });
    }


});

var projects_status_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Projects Status";
$("#modal-add-projects-status").fireModal({
    size: 'modal-md',
    title: projects_status_modal_title,
    body: $("#modal-add-projects-status-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        // let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        var is_reload = formData.get("is_reload");
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    if (is_reload == 1) {
                        location.reload();
                    } else {
                        $('#status').find('option').remove().end();
                        $.ajax({
                            type: 'POST',
                            url: base_url + 'projects/get_projects_type',
                            data: csrfName + '=' + csrfHash,
                            dataType: "json",
                            // quietMillis: 500,
                            success: function (data) {
                                csrfName = data["csrfName"];
                                csrfHash = data["csrfHash"];
                                delete data["csrfName"];
                                delete data["csrfHash"];
                                var $newOption = $("<option selected></option>").val('').text('Choose...')

                                $("#status").append($newOption).trigger('change');
                                $.each(data, function (i) {
                                    $.each(data[i], function (key, val) {

                                        var $newOption = $("<option></option>").val(val.id).text(val.title)

                                        $("#status").append($newOption).trigger('change');
                                    });
                                });
                                $('#status').val(result['status']).trigger('change');
                            }

                        });

                        form.stopProgress();
                        iziToast.success({
                            title: result['message'],
                            message: '',
                            position: 'topRight'
                        });
                        location.reload();
                    }
                } else {
                    form.stopProgress();
                    // $('#fire-modal-4').modal('hide');
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'additembtn',
        handler: function (modal) { }
    }]

});
var projects_edit_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Edit projects Type";
$(".modal-edit-projects-type").fireModal({

    size: 'modal-md',
    title: projects_edit_modal_title,
    body: $("#modal-edit-projects-type-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'editcontracttypebtn',
        handler: function (modal) { }
    }]

});

var statuses_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Statuses";
$("#modal-add-statuses").fireModal({
    size: 'modal-md',
    title: statuses_modal_title,
    body: $("#modal-add-statuses-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        // let form_data = $(e.target).serialize();

        var formData = new FormData(this);
        var is_reload = formData.get("is_reload");
        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    if (is_reload == 1) {
                        location.reload();
                    } else {
                        if ($('#statuses_task_id').length) {
                            $('#statuses_task_id').find('option').remove().end();
                            $.ajax({
                                type: 'POST',
                                url: base_url + 'statuses/get_statuses_task',
                                data: csrfName + '=' + csrfHash,
                                dataType: "json",
                                success: function (data) {
                                    csrfName = data["csrfName"];
                                    csrfHash = data["csrfHash"];
                                    delete data["csrfName"];
                                    delete data["csrfHash"];

                                    var data_statuses = data.data.statuses;
                                    var $statusesDropdown = $("#statuses_task_id");

                                    $statusesDropdown.empty();
                                    var $defaultOption = $("<option selected></option>").val('').text('Choose...');
                                    $statusesDropdown.append($defaultOption);

                                    $.each(data_statuses, function (key, val) {
                                        var $newOption = $("<option></option>").val(val.status).text(val.status);
                                        $statusesDropdown.append($newOption);
                                    });

                                    $statusesDropdown.val(result['statuses_task_id']).trigger('change');
                                    form.stopProgress();
                                    iziToast.success({
                                        title: result['message'],
                                        message: '',
                                        position: 'topRight'
                                    });
                                    modal.modal('hide');
                                }
                            });
                        } else if ($('#statuses_project_id').length) {
                            $('#statuses_project_id').find('option').remove().end();
                            $.ajax({
                                type: 'POST',
                                url: base_url + 'statuses/get_statuses_project',
                                data: csrfName + '=' + csrfHash,
                                dataType: "json",
                                success: function (data) {
                                    csrfName = data["csrfName"];
                                    csrfHash = data["csrfHash"];
                                    delete data["csrfName"];
                                    delete data["csrfHash"];

                                    var data_statuses = data.data.statuses;
                                    var $statusesDropdown = $("#statuses_project_id");

                                    $statusesDropdown.empty();
                                    var $defaultOption = $("<option selected></option>").val('').text('Choose...');
                                    $statusesDropdown.append($defaultOption);

                                    $.each(data_statuses, function (key, val) {
                                        var $newOption = $("<option></option>").val(val.status).text(val.status);
                                        $statusesDropdown.append($newOption);
                                    });

                                    $statusesDropdown.val(result['statuses_task_id']).trigger('change');
                                    form.stopProgress();
                                    iziToast.success({
                                        title: result['message'],
                                        message: '',
                                        position: 'topRight'
                                    });
                                    modal.modal('hide');
                                }
                            });
                        } else {
                            $('#statuses_id').find('option').remove().end();
                            $.ajax({
                                type: 'POST',
                                url: base_url + 'statuses/get_statuses',
                                data: csrfName + '=' + csrfHash,
                                dataType: "json",
                                // quietMillis: 500,
                                success: function (data) {
                                    csrfName = data["csrfName"];
                                    csrfHash = data["csrfHash"];
                                    delete data["csrfName"];
                                    delete data["csrfHash"];
                                    var $newOption = $("<option selected></option>").val('').text('Choose...')

                                    $("#statuses_id").append($newOption).trigger('change');
                                    $.each(data, function (i) {
                                        $.each(data[i], function (key, val) {

                                            console.log(val);
                                            var $newOption = $("<option></option>").val(val.type).text(val.type)
                                            $("#statuses_id").append($newOption).trigger('change');
                                        });
                                    });
                                    $('#statuses_id').val(result['statuses_id']).trigger('change');
                                    form.stopProgress();
                                    iziToast.success({
                                        title: result['message'],
                                        message: '',
                                        position: 'topRight'
                                    });
                                    location.reload();
                                }

                            });

                        }

                    }
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'additembtn',
        handler: function (modal) { }
    }]

});
var statuses_edit_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Edit Statuses";
$(".modal-edit-statuses").fireModal({

    size: 'modal-md',
    title: statuses_edit_modal_title,
    body: $("#modal-edit-statuses-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,


    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {

        //   console.log(form)
    },

    buttons: [{
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'editcontracttypebtn',
        handler: function (modal) { }
    }]

});
var add_time_tracker_modal_title = ($('#modal-title')) ? $('#modal-title').html() : "Add Time Sheet";
$("#modal-add-time-tracker").fireModal({
    title: add_time_tracker_modal_title,
    body: $("#modal-add-time-tracker-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();

        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }

            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //   console.log(form)
    },
    buttons: [{
        text: modal_footer_add_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'addtimetrackerbtn',
        handler: function (modal) { }
    }]
});

var edit_time_tracker_modal_title = ($('#modal-edit-time-tracker-title')) ? $('#modal-edit-time-tracker-title').html() : "Edit Time Sheet";
$(".modal-edit-time-tracker").fireModal({
    title: edit_time_tracker_modal_title,
    body: $("#modal-edit-time-tracker-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        let form_data = $(e.target).serialize();
        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {

                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];

                if (result['error'] == false) {
                    form.stopProgress();
                    location.reload();
                } else {
                    form.stopProgress();
                    modal.find('.modal-body').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                    modal.find('.alert-danger').delay(4000).fadeOut();
                }
            }
        });

        e.preventDefault();
    },
    shown: function (modal, form) {
        //  modal.find('.modal-title').text(edit_note_modal_title);

    },
    buttons: [
    {
        text: modal_footer_edit_title,
        submit: true,
        class: 'btn btn-primary btn-shadow',
        handler: function (modal) { }
    }
    ]
});
function get_task_milestone_data() {
    var option = "";
    $("#task_id").html(option);
    let form_data = {
        milestone_id: document.getElementById("milestone_id").value,
      [csrfName]: csrfHash,
    };
    console.log(form_data);
    $.ajax({
      url: base_url + 'estimates/get_task_milestone_data',
      type: "POST",
      data: form_data,
      success: function (result) {
        console.log(result);
        var data = JSON.parse(result);
        csrfName = data.csrfName;
        csrfHash = data.csrfHash;
        var task = data['task'];
        task.forEach((tasks) => {
          option =
            option +
            `<option value="` +
            tasks["id"] +
            `">` +
            tasks["title"] +
            `</option>`;
        });
        $("#task_id").html(option)
  
      },
      error: function (error) {
        console.log(error);
      },
    });
}

FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateSize,
    FilePondPluginFileValidateType
    // FilePondPluginMediaPreview
);

$(".filepond").filepond({
    credits: null,
    allowFileSizeValidation: "true",
    maxFileSize: "25MB",
    labelMaxFileSizeExceeded: "File is too large",
    labelMaxFileSize: "Maximum file size is {filesize}",
    allowFileTypeValidation: true,
    acceptedFileTypes: ["image/*", "video/*", "application/pdf"],
    labelFileTypeNotAllowed: "File of invalid type",
    fileValidateTypeLabelExpectedTypes:
        "Expects {allButLastType} or {lastType}",
    storeAsFile: true,
    allowPdfPreview: true,
    pdfPreviewHeight: 320,
    pdfComponentExtraParams: "toolbar=0&navpanes=0&scrollbar=0&view=fitH",
    allowVideoPreview: true, // default true
    allowAudioPreview: true, // default true
    onprocessfile: function (error, file) {
        console.log("Clear the image view area");
        if (!error) {
            // Clear the image view area
            $(".filepond--root .filepond--image-preview").html("");
        }
    },
});

$(document).ready(function () {
    // Show/hide chat iframe on chat button click
    $("#chat-button").on("click", function (e) {
        e.preventDefault();
        $("#chat-iframe").toggle();
        $(this).toggleClass("opened");
        $("#chat-iframe").toggleClass("opened");
    });
    $("#chat-with-button").on("click", function (e) {
        e.preventDefault();
       
        $("#chat-iframe").attr("src", base_url + "chat/floating_chat?user_id=" + $(this).data("id"));
        $("#chat-iframe").toggle();
        $(this).toggleClass("opened");
        $("#chat-iframe").toggleClass("opened");
    });
});
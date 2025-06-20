"use strict";

function queryParams(p) {
    return {
        "email_templates": $('#email_templates').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
$('#create_email_templates_form').validate({
    rules: {
        type: "required",
        subject: "required",
        message: "required"
    }
});
$('#edit_email_templates_form').validate({
    rules: {
        type: "required",
        subject: "required",
        message: "required"
    }
});
$('#create_email_templates_form').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#create_email_templates_form").validate().form()) {
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
    }
});
$('#edit_email_templates_form').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#edit_email_templates_form").validate().form()) {
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
    }
});

$(document).on('change', '.type', function (e) {
    e.preventDefault()

    var sort_type_val = $(this).val();
    if (sort_type_val == 'contact_us' && sort_type_val != ' ') {
        $('.contact_us').removeClass('d-none');
    } else {
        $('.contact_us').addClass('d-none');
    }
    if (sort_type_val == 'projects_deadline_reminder' && sort_type_val != ' ') {
        $('.projects_deadline_reminder').removeClass('d-none');
    } else {
        $('.projects_deadline_reminder').addClass('d-none');
    }
    if (sort_type_val == 'tasks_deadline_reminder' && sort_type_val != ' ') {
        $('.tasks_deadline_reminder').removeClass('d-none');
    } else {
        $('.tasks_deadline_reminder').addClass('d-none');
    }
    if (sort_type_val == 'forgot_password' && sort_type_val != ' ') {
        $('.forgot_password').removeClass('d-none');
    } else {
        $('.forgot_password').addClass('d-none');
    }
    if (sort_type_val == 'reset_password' && sort_type_val != ' ') {
        $('.reset_password').removeClass('d-none');
    } else {
        $('.reset_password').addClass('d-none');
    }
    if (sort_type_val == 'project_create' && sort_type_val != ' ') {
        $('.project_create').removeClass('d-none');
    } else {
        $('.project_create').addClass('d-none');
    }
    if (sort_type_val == 'project_edit' && sort_type_val != ' ') {
        $('.project_edit').removeClass('d-none');
    } else {
        $('.project_edit').addClass('d-none');
    }
    if (sort_type_val == 'project_assigned' && sort_type_val != ' ') {
        $('.project_assigned').removeClass('d-none');
    } else {
        $('.project_assigned').addClass('d-none');
    }
    if (sort_type_val == 'task_create' && sort_type_val != ' ') {
        $('.task_create').removeClass('d-none');
    } else {
        $('.task_create').addClass('d-none');
    }
    if (sort_type_val == 'task_edit' && sort_type_val != ' ') {
        $('.task_edit').removeClass('d-none');
    } else {
        $('.task_edit').addClass('d-none');
    }
    if (sort_type_val == 'task_assigned' && sort_type_val != ' ') {
        $('.task_assigned').removeClass('d-none');
    } else {
        $('.task_assigned').addClass('d-none');
    }
    if (sort_type_val == 'added_new_workspace' && sort_type_val != ' ') {
        $('.added_new_workspace').removeClass('d-none');
    } else {
        $('.added_new_workspace').addClass('d-none');
    }
    if (sort_type_val == 'added_company' && sort_type_val != ' ') {
        $('.added_company').removeClass('d-none');
    } else {
        $('.added_company').addClass('d-none');
    }
});

tinymce.init({
    selector: '#email_message',
    height: 150,
    menubar: false,
    plugins: [
        'autolink lists link charmap print preview anchor textcolor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime table contextmenu paste code help wordcount'
    ],
    toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help ',
    setup: function (editor) {
        editor.on("change keyup", function (e) {
            //tinyMCE.triggerSave(); // updates all instances
            editor.save(); // updates this instance's textarea
            $(editor.getElement()).trigger('change'); // for garlic to detect change
        });
    }
});
tinymce.init({
    selector: '#update_message',
    height: 150,
    menubar: false,
    plugins: [
        'autolink lists link charmap print preview anchor textcolor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime table contextmenu paste code help wordcount'
    ],
    toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help ',
    setup: function (editor) {
        editor.on("change keyup", function (e) {
            //tinyMCE.triggerSave(); // updates all instances
            editor.save(); // updates this instance's textarea
            $(editor.getElement()).trigger('change'); // for garlic to detect change
        });
    }
});
$(".modal-edit-email-templates").fireModal({
    size: 'modal-lg',
    title: 'Edit Email templates',
    body: $("#modal-edit-email-templates-part"),
    footerClass: 'bg-whitesmoke',
    autoFocus: false,
    onFormSubmit: function (modal, e, form) {
        // Form Data
        var message =  $(".update_message").val();
        var formData = new FormData(this);

        formData.append(csrfName, csrfHash);
        // formData.append('update_message', message);

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
    shown: function (modal, form) {},
    buttons: [{
        text: 'Edit',
        submit: true,
        class: 'btn btn-primary btn-shadow',
        id: 'addnotebtn',
        handler: function (modal) {}
    }]
});

$(document).on('click', '.delete-Email-templates-type-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("type-id");
    swal({
            title: 'Are you sure?',
            text: 'All Email templates Type Releted Data Will Once deleted, you will not be able to recover that data!',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'email_templates/delete/' + id,
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

$(document).on('click', '.modal-edit-email-templates-ajax', function () {
    var id = $(this).data("id");
    fetch_email_template(id, "", "modal");
});
$(document).on('change', '.type', function (e) {
    var name = $(".type option:selected").val();

    fetch_email_template("", name, "create_email_templates_form");
});

function fetch_email_template(id = "", name = "", position = "modal") {
    $.ajax({
        type: "POST",
        data: "id=" + id + "&name=" + name + "&" + csrfName + "=" + csrfHash,
        url: base_url + 'email_templates/get_email_templates_by_id',
        dataType: 'JSON',
        success: function (result) {
            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            if (result.error == false) {
                if (position = "modal") {
                    /* from update modal */
                    $('#update_type').val(result.data[0].type).trigger('change');
                    $('#update_id').val(result.data[0].id);
                    $('#update_subject').val(result.data[0].subject);
                    $('#update_message').val(result.data[0].message);
                    tinyMCE.get('update_message').setContent(result.data[0].message);
                    $(".modal-edit-email-templates").trigger("click");
                } else {
                    /* from create form */
                    if (result.data[0].subject && result.data[0].message != "") {
                        $(this).closest("form").attr("action", base_url + "email_templates/edit");
                        $('#update_type_id').val(result.data[0].id);
                        $('#subject').val(result.data[0].subject);
                        tinyMCE.get('message').setContent(result.data[0].subject);
                    } else {
                        $(this).closest("form").attr("action", base_url + "email_templates/create");
                        $('#update_type_id').val("");
                        $('#subject').val("");
                        tinyMCE.get('message').setContent("");
                    }
                }
            }
        }
    });
}
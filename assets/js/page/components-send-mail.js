"use strict";

function queryParams(p) {
    return {
        status: $("#status").val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

$('#send_mail_form').validate({
    ignore: ':hidden:not(textarea, [class~=selectized]),:hidden > .selectized, .selectize-control .selectize-input input',
    rules: {
        to: "required",
        subject: "required",
        message: "required",

    }
});
$('.submit_btn').on('click', function(e) {
    e.preventDefault();
    var is_draft = $(this).data('draft');
    if (is_draft == 'yes') {
        $("#draft").val(1)
        $('#draft_btn').html('Please Wait..');
        $('#draft_btn').attr('disabled', true);
    } else {
        $("#draft").val(0)
        $('#submit_btn').html('Please Wait..');
        $('#submit_btn').attr('disabled', true);
    }
    $('#send_mail_form').submit();
})
$('#send_mail_form').on('submit', function(e) {
    e.preventDefault();

    if ($("#send_mail_form").validate().form()) {
        if (myDropzone.getQueuedFiles().length > 0) {
            myDropzone.processQueue();
        } else {
            myDropzone.uploadFiles([]); //send empty
            var formData = new FormData(this);
            formData.append(csrfName, csrfHash);

            $.ajax({
                type: 'POST',
                url: base_url + 'send_mail/send',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(result) {
                    location.reload();

                }
            });
        }
    } else {
        $('#draft_btn').html('Save as draft');
        $('#draft_btn').attr('disabled', false);
        $('#submit_btn').html('Send mail');
        $('#submit_btn').attr('disabled', false);
    }
});
var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
    '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';

$('#to').selectize({
    plugins: ['remove_button'],
    delimiter: ',',
    persist: true,
    multiple: true,
    maxItems: null,
    valueField: 'email',
    labelField: 'email',
    searchField: ['email'],
    options: to,
    render: {
        item: function(item, escape) {
            return '<div>' +

                (item.email ? '<span class="email">' + escape(item.email) + '</span>' : '') +
                '</div>';
        },
        option: function(item, escape) {
            var label = item.email;
            return '<div>' +
                '<span class="label">' + escape(label) + '</span>' +

                '</div>';
        }
    },
    createFilter: function(input) {
        var match, regex;

        // email@address.com
        regex = new RegExp('^' + REGEX_EMAIL + '$', 'i');
        match = input.match(regex);
        if (match) return !this.options.hasOwnProperty(match[0]);

        // name <email@address.com>
        regex = new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i');
        match = input.match(regex);
        if (match) return !this.options.hasOwnProperty(match[2]);

        return false;
    },
    create: function(input) {
        if ((new RegExp('^' + REGEX_EMAIL + '$', 'i')).test(input)) {
            return { email: input };
        }
        var match = input.match(new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i'));
        if (match) {
            return {
                email: match[2],
                // name: $.trim(match[1])
            };
        }
        alert('Invalid email address.');
        return false;
    }
});

Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("#mail-files-dropzone", {
    url: base_url + 'send_mail/send',
    autoProcessQueue: false,
    parallelUploads: 10,
    uploadMultiple: true,
    dictDefaultMessage: dictDefaultMessage,
    addRemoveLinks: true,
    dictRemoveFile: 'x',

});

myDropzone.on("addedfile", function(file) {
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

myDropzone.on('sending', function(file, xhr, formData) {
    formData.append('to', $('#to').val());
    formData.append('subject', $('#subject').val());
    formData.append('message', $('#message').val());
    formData.append('status', $('#status').val());
    formData.append('status', $('#status').val());
    formData.append('is_draft', $("#draft").val());
    formData.append(csrfName, csrfHash);
});
myDropzone.on("queuecomplete", function(file) {
    location.reload();
});

tinymce.init({
    selector: '#message',
    height: 150,
    menubar: false,
    plugins: [
        'autolink lists link charmap print preview anchor textcolor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime table contextmenu paste code help wordcount'
    ],
    toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help ',
    setup: function(editor) {
        editor.on("change keyup", function(e) {
            //tinyMCE.triggerSave(); // updates all instances
            editor.save(); // updates this instance's textarea
            $(editor.getElement()).trigger('change'); // for garlic to detect change
        });
    }
});
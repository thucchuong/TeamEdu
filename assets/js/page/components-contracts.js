"use strict";
$('#filter-contracts').on('click', function (e) {
    e.preventDefault();

    $('#contracts_list').bootstrapTable('refresh');
});
function queryParams(p) {
    return {
        "user_id": $('#user_id').val(),
        "status": $('#status').val(),
        "project_id": $('#filter_project_id').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
$(document).ready(function () {
    var flg = 0;
    $('#contract_type_id').on("select2:open", function () {
        flg++;
        if (flg == 1) {
            var this_html = jQuery('#wrp').html();
            $(".select2-results").append("<div class='select2-results__option'>" +
                this_html + "</div>");
        }
    });
});
$(document).on('click', '#modal-add-contracts-type', function () {
    $("#contract_type_id").select2("close");


});
tinymce.init({
    selector: '#description',
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
    selector: '#update_description',
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

// Get the canvas element
var canvas1 = document.getElementById('signature-pad1');
var signaturePad1 = new SignaturePad(canvas1);
$("#btnSaveSign").on("click", function (e) {
    var img_data = signaturePad1.toDataURL('image/png');
    $("<input>").attr({
        type: "hidden",
        name: "signatureImage",
        value: img_data
    }).appendTo("#sign_form");
    $("#sign_form").submit();
});


var canvas2 = document.getElementById('signature-pad2');
var signaturePad2 = new SignaturePad(canvas2);

$("#btnClientSaveSign").on("click", function (e) {
    var img_data = signaturePad2.toDataURL('image/png');
    $("<input>").attr({
        type: "hidden",
        name: "clientSignatureImage",
        value: img_data
    }).appendTo("#client_sign_form");
    $("#client_sign_form").submit();
});


function get_projects() {
    var option = "";

    $(".contract_project_id").html(option);
    let form_data = {
        client_id: document.getElementById("client_id").value,
        [csrfName]: csrfHash,
    };
    $.ajax({
        url: base_url + 'contracts/get_project_by_client_id',
        type: "POST",
        data: form_data,
        success: function (result) {
            var data = JSON.parse(result);
           
            csrfName = data.csrfName;
            csrfHash = data.csrfHash;
            var projects = data['projects'];

            projects.forEach((project) => {
                option =
                    option +
                    `<option value="` +
                    project["id"] +
                    `">` +
                    project["title"] +
                    `</option>`;
            });

            $(".contract_project_id").html(option)


        },
        error: function (error) {
            console.log(error);
        },
    });
}
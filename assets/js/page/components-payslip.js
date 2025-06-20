function queryParams(p) {
    return {
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
function queryParams1(p) {
    return {
        "allowances_list": $("#allowances_list").val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
function queryParams2(p) {
    return {
        "deductions_list": $("#deductions_list").val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
$(document).ready(function () {
    var flg = 0;
    $('#allowance_id').on("select2:open", function () {
        flg++;
        if (flg == 1) {
            $this_html = jQuery('#wrp').html();
            $(".select2-results").append("<div class='select2-results__option'>" +
                $this_html + "</div>");
        }
    });
});
$(document).ready(function () {
    var flg = 0;
    $('#deduction_id').on("select2:open", function () {
        flg++;
        if (flg == 1) {
            $this_html = jQuery('#wrp1').html();
            $(".select2-results").append("<div class='select2-results__option'>" +
                $this_html + "</div>");
        }
    });
});
$(document).on('click', '#modal-add-allowance', function () {
    $("#allowance_id").select2("close");
});
$(document).on('click', '#modal-add-deduction', function () {
    $("#deduction_id").select2("close");
});

$(document).on('click', '#modal-edit-allowance', function () {
    $("#allowance_id").select2("close");
});
$(document).on('click', '#modal-edit-deduction', function () {
    $("#deduction_id").select2("close");
});

// $('#fire-modal-3').on('hidden.bs.modal', function (e) {
//     $(this).find('form').trigger('reset');
// });
// $('#fire-modal-31').on('hidden.bs.modal', function (e) {
//     $(this).find('form').trigger('reset');
// });
// $('#fire-modal-4').on('hidden.bs.modal', function (e) {
//     $(this).find('form').trigger('reset');
// });
// $('#fire-modal-41').on('hidden.bs.modal', function (e) {
//     $(this).find('form').trigger('reset');
// });

$(document).on('change', '.deducation_data', function (e) {
    e.preventDefault()

    var sort_type_val = $(this).val();
    if (sort_type_val == 'amount' && sort_type_val != ' ') {
        $('.deducation_data_amount').removeClass('d-none');
    } else {
        $('.deducation_data_amount').addClass('d-none');
    }
    if (sort_type_val == 'percentage' && sort_type_val != ' ') {
        $('.deducation_data_percentage').removeClass('d-none');
    } else {
        $('.deducation_data_percentage').addClass('d-none');
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

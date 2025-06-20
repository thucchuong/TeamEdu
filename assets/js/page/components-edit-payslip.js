// $(document).ready(function () {
//     var id = $("#payslip_id").val();
//     $.ajax({
//         type: 'POST',
//         url: base_url + 'payslip/get_payslip_by_id',
//         data: "id=" + id + "&" + csrfName + "=" + csrfHash,
//         dataType: "json",
//         success: function (result) {
//             console.log(result);
//             csrfName = csrfName;
//             csrfHash = result.csrfHash;
//             $('#user_id').val(result.user_id).trigger('change');
//             $('#allowance_id').val(result.allowance_id);
//             $('#amount').val(result.amount);
//             $('#basic_salary').val(result.basic_salary);
//             $('#bonus').val(result.bonus);
//             $('#deduction_id').val(result.deduction_id);
//             $('#deduction_type').val(result.deduction_type);
//             $('#id').val(result.id);
//             $('#incentives').val(result.incentives);
//             $('#leave_deduction').val(result.leave_deduction);
//             $('#lop_days').val(result.lop_days);
//             $('#net_pay').val(result.net_pay);
//             $('#ot_hours').val(result.ot_hours);
//             $('#ot_payment').val(result.ot_payment);
//             $('#ot_rate').val(result.ot_rate);
//             $('#paid_days').val(result.paid_days);
//             $('#paid_days').val(result.paid_days);
//             $('#payment_date').val(result.payment_date);
//             $('#payment_method').val(result.payment_method).trigger('change');
//             $('#working_days').val(result.working_days);
//             $('#payslip_month').val(result.payslip_month);
//             $('#percentage').val(result.percentage);
//             if (result.status == 1) {
//                 $("#status").prop("checked", true);
//             } else {
//                 $("#status").prop("checked", true);
//             }
//             $('#total_allowance').val(result.total_allowance);
//             $('#total_deductions').val(result.total_deductions);
//             $('#total_earnings').val(result.total_earnings);
//         }
//     });
// });
// $(document).ready(function () {
//     // select2
//     setTimeout(function () {
//         $("#user_id").select2({
//             placeholder: "Select user",
//         });
//     }, 100);
// });

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

function set_lop() {
    let form_data = {
        user_id: document.getElementById("user_id").value,
        [csrfName]: csrfHash,
    };
    $.ajax({
        url: base_url + 'payslip/get_lop',
        type: "POST",
        data: form_data,
        beforeSend: function () { },
        success: function (result) {
            const res = JSON.parse(result);
            csrfName = res.csrfName;
            csrfHash = res.csrfHash;
            const leavse = parseInt(res.leaves);
            const working_days = 30;
            let paid_days = working_days - leavse;
            $("#lop_days").val(parseInt(res.leaves));
            $('#paid_days').val(parseInt(paid_days));
        },
        error: function (error) {
            console.log(error);
        },
    });
}
function update_total_payslip() {
    let lop_days = parseFloat($('#lop_days').val());
    let working_days = 30;
    let paid_days = working_days - lop_days;
    $('#paid_days').val(paid_days);

    let basic_salary = parseFloat($('#basic_salary').val()) ? parseFloat($('#basic_salary').val()) : 0;
    $('#basic_salary').val(basic_salary);

    let leave_deduction = (basic_salary / working_days) * lop_days;
    $('#leave_deduction').val(leave_deduction.toFixed(2));
    let total_basic_salary_deduction = basic_salary - leave_deduction;

    let ot_hours = parseFloat($('#ot_hours').val());
    let ot_rate = parseFloat($('#ot_rate').val());

    let new_amount = (ot_hours * ot_rate) ? (ot_hours * ot_rate) : 0;
    $('#ot_payment').val(new_amount.toFixed(2));

    let total_allowance = parseFloat($('#total_allowance').val()) ? parseFloat($('#total_allowance').val()) : 0;
    let amount = (!isNaN($('#allowance_0_amount').val()) && $('#allowance_0_amount').val() != '') ? parseFloat($('#allowance_0_amount').val()) : 0;
    let new_total_allowance = total_allowance + amount;
    let rounded_total_allowance = new_total_allowance.toFixed(2);
    $('#total_allowance').val(total_allowance.toFixed(2));

    let gross_salary = parseFloat(total_basic_salary_deduction) + parseFloat(rounded_total_allowance);
    let bonus = parseFloat($('#bonus').val());
    let incentives = parseFloat($('#incentives').val());
    let other_earning = parseFloat(new_amount) + parseFloat(bonus) + parseFloat(incentives);

    let total_earnings = (gross_salary + other_earning) ? (gross_salary + other_earning) : 0;
    $('#total_earnings').val(total_earnings.toFixed(2));

    let total_deduction = (!isNaN($('#total_deduction').val()) && $('#total_deduction').val() != '') ? parseFloat($('#total_deduction').val()) : 0;
    $('#total_deduction').val(total_deduction.toFixed(2));

    let net_pay = (total_earnings - total_deduction) ? (total_earnings - total_deduction) : 0;
    let new_net_pay = net_pay.toFixed(2);
    $('#net_pay').val(new_net_pay);
}

$('#allowance_id').on('change', function (e) {
    var allowance_id = $('#allowance_id').val();
    if (allowance_id != '') {
        $.ajax({
            type: 'POST',
            url: base_url + 'payslip/get_allowance_by_id/' + allowance_id,
            data: csrfName + '=' + csrfHash,
            dataType: "json",
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                ($('#allowance_0_allowance_name').val(result.name));
                ($('#allowance_0_amount')).val(result.amount);

            }
        });
    }
});
$('#deduction_id').on('change', function (e) {
    var deduction_id = $('#deduction_id').val();
    if (deduction_id != '') {
        $.ajax({
            type: 'POST',
            url: base_url + 'payslip/get_deduction_by_id/' + deduction_id,
            data: csrfName + '=' + csrfHash,
            dataType: "json",
            success: function (result) {
                var percentage = parseFloat($("#basic_salary").val()) * (result.percentage / 100);
                var amount = percentage ? percentage : (result.amount)
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                ($('#deduction_0_deduction_name').val(result.name));
                ($('#deduction_0_deduction_type')).val(result.deduction_type);
                ($('#deduction_0_amount')).val(amount);
                ($('#deduction_0_percentage')).val(result.percentage);
            }
        });
    }
});

$('#fire-modal-3').on('hidden.bs.modal', function (e) {
    $(this).find('form').trigger('reset');
});
$('#fire-modal-31').on('hidden.bs.modal', function (e) {
    $(this).find('form').trigger('reset');
});
$('#fire-modal-4').on('hidden.bs.modal', function (e) {
    $(this).find('form').trigger('reset');
});
$('#fire-modal-41').on('hidden.bs.modal', function (e) {
    $(this).find('form').trigger('reset');
});
$('#fire-modal-5').on('hidden.bs.modal', function (e) {
    $(this).find('form').trigger('reset');
});
$('#fire-modal-51').on('hidden.bs.modal', function (e) {
    $(this).find('form').trigger('reset');
});
var i = (j > 1) ? (j - 1) : 0;
$(document).on('click', '.add-allowance-payslip', function (e) {
    e.preventDefault();
    allowance_id = $("#allowance_id").val();
    var allowance_name = $("#allowance_0_allowance_name").val();
    var amount = $("#allowance_0_amount").val();

    if (allowance_name != '') {
        $('#allowance_id').val('').trigger('change');
        i++;
        html = '<div class="allowance-payslip py-1">' +
            '<div class="row">' +
            '<input type="hidden" id=allowance_' + i + ' name="allowance[]">' +
            '<input type="hidden" id=allowance_item_' + i + '_id name="allowance_item_ids[]">' +
            '<div class="col-md-6 custom-col">' +
            '<input type="text" class="form-control allowance_name" name="allowance_name[]" id=allowance_' + i + '_allowance_name onchange="update_total_payslip()" placeholder="allowance">' +
            '</div>' +
            '<div class="col-md-4 custom-col">' +
            '<input type="number" class="form-control allowance" min="0" name="amount[]" id="allowance_' + i + '_amount" onchange="update_total_allowance(); update_total_payslip();"  value="0" placeholder=""></input>' +
            '</div>' +
            '<div class="col-md-2 custom-col">' +
            '<a href="#" class="btn btn-icon btn-danger remove-allowance-payslip" onchange="update_total_payslip()" data-id="' + allowance_id + '" data-amount="' + amount + '" data-count=' + i + '><i class="fas fa-trash"></i></a>' +
            '</div></div>' +
            '</div>';

        $('#allowance_payslip').append(html);
        $('#allowance_' + i).val(allowance_id);
        $('#allowance_' + i + '_allowance_name').val(allowance_name);
        $('#allowance_' + i + '_amount').val(amount);


        total_allowance = $('#total_allowance').val();
        new_total_allowance = +total_allowance + +amount;
        rounded_total_allowance = new_total_allowance.toFixed(2);
        $('#total_allowance').val(rounded_total_allowance);

        $("#allowance_0_allowance_name").val('');
        $("#allowance_0_amount").val('');
    } else {
        iziToast.error({
            title: "Please choose Allowance",
            message: '',
            position: 'topRight'
        });
    }


});

// update total allowance
function update_total_allowance() {
    var total = 0;

    document.querySelectorAll(".allowance").forEach(allowance => {
        total += Number(allowance.value);
    });
    var newTotalAllowance =  total;
    var roundedTotalAllowance = newTotalAllowance.toFixed(2);
    $('#total_allowance').val(roundedTotalAllowance);
}


var m = (k > 1) ? (k - 1) : 0;
$(document).on('click', '.add-deduction-payslip', function (e) {
    e.preventDefault();
    deduction_id = $("#deduction_id").val();
    var deduction_name = $("#deduction_0_deduction_name").val();
    var deduction_percentage = parseFloat($("#deduction_0_percentage").val());
    var deduction_amount = parseFloat($("#deduction_0_amount").val());
    var deduction_type = $("#deduction_0_deduction_type").val();
    var basic_salary = parseFloat($('#basic_salary').val());
    var total_deduction = parseFloat($('#total_deduction').val());
    total_deduction = (isNaN(total_deduction)) ? 0 : total_deduction;

    if (deduction_name != '') {
        $('#deduction_id').val('').trigger('change');
        m++;
        html = '<div class="deduction-payslip py-1">' +
            '<div class="row">' +
            '<input type="hidden" id=deduction_' + m + ' name="deduction[]">' +
            '<input type="hidden" id=deduction_item_' + m + '_id name="deduction_item_ids[]">' +
            '<div class="col-md-2 custom-col">' +
            '<input type="text" class="form-control deduction_name" name="deduction_name[]" id=deduction_' + m + '_deduction_name onchange="update_total_payslip()" placeholder="deduction">' +
            '</div>' +
            '<div class="col-md-4 custom-col">' +
            '<select class="form-control deduction_type" name="deduction_type[]" onchange="update_total_payslip()" id=deduction_' + m + '_deduction_type>' +
            deduction_type +
            '<option value="" selected>Choose Deduction Type</option>' +
            '<option value="amount" ' + (deduction_type === "amount" ? "selected" : "") + '>Amount</option>' +
            '<option value="percentage" ' + (deduction_type === "percentage" ? "selected" : "") + '>Percentage</option>' +
            '</select>' +
            '</div>' +
            '<div class="col-md-2 custom-col">' +
            '<input type="number" class="form-control deduction_percentage" min="0" max="0" onchange="update_percentage_deduction(' + m + '); update_total_payslip();" name="deduction_percentage[]" id="deduction_' + m + '_percentage" placeholder=""></input>' +
            '</div>' +
            '<div class="col-md-2 custom-col">' +
            '<input type="number" class="form-control deduction_amount deduction"  min="0" onchange="update_total_deduction(); update_percentage_deduction(' + m + '); update_amount_deduction(' + m + '); update_total_payslip();" value="0" name="deduction_amount[]" id="deduction_' + m + '_amount" placeholder=""></input>' +
            '</div>' +
            '<div class="col-md-2 custom-col">' +
            '<a href="#" class="btn btn-icon btn-danger remove-deduction-payslip" onchange="update_total_payslip()"  data-id=' + deduction_id + ' data-amount=' + deduction_amount + ' data-count=' + m + '><i class="fas fa-trash"></i></a>' +
            '</div></div>' +
            '</div>';

        $('#deduction_payslip').append(html);
        $('#deduction_' + m).val(deduction_id);
        $('#deduction_' + m + '_deduction_name').val(deduction_name);
        $('#deduction_' + m + '_deduction_type').val(deduction_type);
        $('#deduction_' + m + '_percentage').val(deduction_percentage);


        if (deduction_type === "percentage" && !isNaN(deduction_percentage)) {
            calculated_deduction_amount = (deduction_percentage / 100) * basic_salary;
            $('#deduction_' + m + '_amount').val(calculated_deduction_amount.toFixed(2));
            update_percentage_deduction(i);
        } else if (deduction_type === "amount" && !isNaN(deduction_amount)) {
            calculated_deduction_amount = deduction_amount;
            $('#deduction_' + m + '_amount').val(calculated_deduction_amount.toFixed(2));
            update_amount_deduction(i);
        } else {
            calculated_deduction_amount = 0;
            $('#deduction_' + m + '_amount').val('');
        }
        update_total_deduction();


        $("#deduction_0_deduction_name").val('');
        $("#deduction_0_deduction_type").val('');
        $("#deduction_0_percentage").val('');
        $("#deduction_0_amount").val('');
    } else {
        iziToast.error({
            title: "Please choose Deduction",
            message: '',
            position: 'topRight'
        });
    }
});
function update_percentage_deduction(m) {
    var deduction_amount = parseFloat($("#deduction_0_amount").val()) ? parseFloat($("#deduction_0_amount").val()) : 0;
    var basic_salary = parseFloat($('#basic_salary').val());
    total_deduction_percentage = $('#deduction_' + m + '_percentage').val();
    deduction_amount = (total_deduction_percentage / 100) * basic_salary;
    $('#deduction_' + m + '_amount').val(deduction_amount);
    update_total_deduction();
}

function update_amount_deduction(m) {
    var deduction_amount_input = parseFloat($("#deduction_" + m + "_amount").val());
    var deduction_amount = !isNaN(deduction_amount_input) ? deduction_amount_input : 0;

    $('#deduction_' + m + '_amount').val(deduction_amount.toFixed(2));
    update_total_deduction();
}

// update total deduction
function update_total_deduction() {
    var total = 0;
    document.querySelectorAll(".deduction").forEach(deduction => {
        total += parseFloat(deduction.value) || 0;
    });

    var calculated_deduction_amount = total;
    $('#total_deduction').val(calculated_deduction_amount.toFixed(2));
}
function removeA(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax = arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}

$('#edit_payslip_form').validate({
    rules: {
        basic_salary: "required",
    }
});
$('#edit_payslip_form').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#edit_payslip_form").validate().form()) {
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
    }
});

$(document).on('click', '.remove-allowance-payslip', function (e) {
    e.preventDefault();
    count = $(this).data('count');
    allowance_id = $(this).data('id');
    var amount = $(this).data('amount');

    let lop_days = parseFloat($('#lop_days').val());
    let working_days = 30;
    let paid_days = working_days - lop_days;
    $('#paid_days').val(paid_days);

    let basic_salary = parseFloat($('#basic_salary').val()) ? parseFloat($('#basic_salary').val()) : 0;
    $('#basic_salary').val(basic_salary);

    let leave_deduction = (basic_salary / working_days) * lop_days;
    $('#leave_deduction').val(leave_deduction.toFixed(2));

    let ot_hours = parseFloat($('#ot_hours').val());
    let ot_rate = parseFloat($('#ot_rate').val());

    let new_amount = (ot_hours * ot_rate) ? (ot_hours * ot_rate) : 0;
    $('#ot_payment').val(new_amount.toFixed(2));

    let total_allowance = parseFloat($('#total_allowance').val()).toFixed(2);
    var data_amount = (total_allowance - amount).toFixed(2);
    $('#total_allowance').val(data_amount);
    let gross_salary = (basic_salary + parseFloat(data_amount)).toFixed(2);

    let bonus = parseFloat($('#bonus').val());
    let incentives = parseFloat($('#incentives').val());
    let other_earning = parseFloat(new_amount) + parseFloat(bonus) + parseFloat(incentives);

    let total_earnings = (parseFloat(gross_salary) + parseFloat(other_earning)).toFixed(2);
    $('#total_earnings').val(total_earnings);

    let total_deduction = (!isNaN($('#total_deduction').val()) && $('#total_deduction').val() != '') ? parseFloat($('#total_deduction').val()) : 0;
    $('#total_deduction').val(total_deduction.toFixed(2));

    let net_pay = (total_earnings - total_deduction) ? (total_earnings - total_deduction) : 0;
    let new_net_pay = net_pay.toFixed(2);
    $('#net_pay').val(new_net_pay);

    deleted_allowance_item_ids = $("#deleted_allowance_item_ids").val();
    if (deleted_allowance_item_ids == '') {
        deleted_allowance_item_ids = allowance_id;
    } else {
        deleted_allowance_item_ids = deleted_allowance_item_ids + ',' + allowance_id;
    }
    $("#deleted_allowance_item_ids").val(deleted_allowance_item_ids);

    $(this).closest('.allowance-payslip-data').remove();
});

$(document).on('click', '.remove-deduction-payslip', function (e) {
    e.preventDefault();
    count = $(this).data('count');
    deduction_id = $(this).data('id');
    var amount = parseFloat($(this).data('amount'));

    let lop_days = parseFloat($('#lop_days').val());
    let working_days = 30;
    let paid_days = working_days - lop_days;
    $('#paid_days').val(paid_days);

    let basic_salary = parseFloat($('#basic_salary').val()) ? parseFloat($('#basic_salary').val()) : 0;
    $('#basic_salary').val(basic_salary);

    let leave_deduction = (basic_salary / working_days) * lop_days;
    $('#leave_deduction').val(leave_deduction.toFixed(2));
    let total_basic_salary_deduction = basic_salary - leave_deduction;

    let ot_hours = parseFloat($('#ot_hours').val());
    let ot_rate = parseFloat($('#ot_rate').val());

    let new_amount = (ot_hours * ot_rate) ? (ot_hours * ot_rate) : 0;
    $('#ot_payment').val(new_amount.toFixed(2));

    let total_allowance = parseFloat($('#total_allowance').val()) ? parseFloat($('#total_allowance').val()) : 0;

    $('#total_allowance').val(total_allowance.toFixed(2));
    let gross_salary = parseFloat(total_basic_salary_deduction) + parseFloat(total_allowance);

    let bonus = parseFloat($('#bonus').val());
    let incentives = parseFloat($('#incentives').val());
    let other_earning = parseFloat(new_amount) + parseFloat(bonus) + parseFloat(incentives);

    let total_earnings = (gross_salary + other_earning) ? (gross_salary + other_earning) : 0;
    $('#total_earnings').val(total_earnings.toFixed(2));

    let total_deduction = parseFloat($('#total_deduction').val()).toFixed(2);
    var deduction_amount_data = (total_deduction - amount).toFixed(2);
    $('#total_deduction').val(deduction_amount_data);

    let net_pay = (parseFloat(total_earnings) - parseFloat(deduction_amount_data)).toFixed(2);
    let new_net_pay = net_pay.fixed(2);
    $('#net_pay').val(new_net_pay);

    deleted_deduction_item_ids = $("#deleted_deduction_item_ids").val();
    if (deleted_deduction_item_ids == '') {
        deleted_deduction_item_ids = deduction_id;
    } else {
        deleted_deduction_item_ids = deleted_deduction_item_ids + ',' + deduction_id;
    }
    $("#deleted_deduction_item_ids").val(deleted_deduction_item_ids);
    $(this).closest('.deduction-payslip-data').remove();
});


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
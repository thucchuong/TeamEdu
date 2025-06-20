"use strict";

function queryParams(p) {
    var from = $('#start_date').val();
    var to = $('#end_date').val();
    if (from !== '' && to !== '') {
        from = moment(from).format('YYYY-MM-DD');
        to = moment(to).format('YYYY-MM-DD');
    }
    return {
        "from": from,
        "to": to,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

function queryParams1(p) {
    return {
        "meeting_id": $("#meeting_id").val(),
        "type": $("#type").val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
$(document).on('change', '.type', function (e) {
    e.preventDefault()

    var type_val = $(this).val();
    if (type_val == 'physical' && type_val != ' ') {
        $('.physical').removeClass('d-none');
    } else {
        $('.physical').addClass('d-none');
    }
    if (type_val == 'virtual' && type_val != ' ') {
        $('.virtual').removeClass('d-none');
    } else {
        $('.virtual').addClass('d-none');
    }
});
$(document).on('change', '.platform', function (e) {
    e.preventDefault()

    var type_val = $(this).val();
    if (type_val == 'zoom' && type_val != ' ') {
        $('.zoom').removeClass('d-none');
    } else {
        $('.zoom').addClass('d-none');
    }
    if (type_val == 'google_meet' && type_val != ' ') {
        $('.google_meet').removeClass('d-none');
    } else {
        $('.google_meet').addClass('d-none');
    }
    if (type_val == 'microsoft_teams' && type_val != ' ') {
        $('.microsoft_teams').removeClass('d-none');
    } else {
        $('.microsoft_teams').addClass('d-none');
    }
});


$('#fillter-meetings').on('click', function (e) {
    e.preventDefault();
    $('#meeting_list').bootstrapTable('refresh');
});
$(function () {

    $('#meetings_between').daterangepicker({

        showDropdowns: true,
        alwaysShowCalendars: true,
        autoUpdateInput: false,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        locale: {
            "format": "DD/MM/YYYY",
            "separator": " - ",
            "cancelLabel": 'Clear'
        }
    });

    $('#meetings_between').on('apply.daterangepicker', function (ev, picker) {
        $('#start_date').val(picker.startDate.format('MM/DD/YYYY'));
        $('#end_date').val(picker.endDate.format('MM/DD/YYYY'));
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('#meetings_between').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        $('#start_date').val('');
        $('#end_date').val('');
    });

});
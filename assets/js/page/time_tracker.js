"use strict";

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

$('#fillter-time-tracker').on('click', function (e) {
    e.preventDefault();
    $('#time_tracker_list').bootstrapTable('refresh');
});

$(function () {

    $('#time_traker_between').daterangepicker({

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

    $('#time_traker_between').on('apply.daterangepicker', function (ev, picker) {
        $('#start_date').val(picker.startDate.format('MM/DD/YYYY'));
        $('#end_date').val(picker.endDate.format('MM/DD/YYYY'));
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('#time_traker_between').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        $('#start_date').val('');
        $('#end_date').val('');
    });

});

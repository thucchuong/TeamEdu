function queryParams(p) {
var from = $('#leads_start_date').val();
var to = $('#leads_end_date').val();
if (from !== '' && to !== '') {
    from = moment(from).format('YYYY-MM-DD');
    to = moment(to).format('YYYY-MM-DD');
}
return {
    "from": from,
    "to": to,
    "status": $('#leads_status').val(),
    "user_id": $('#user_id').val(),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search
};
}
$(document).on('click', '.modal-edit-leads-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + 'leads/get_leads_by_id',
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
            $('#update_status').val(result.status);
            $('#update_status').trigger('change');
            $('#update_id').val(result.id);
            $('#update_title').val(result.title);
            $('#update_description').val(result.description);
            $('#update_assigned_date').val(result.assigned_date);
            $('#update_email').val(result.email);
            $('#update_phone').val(result.phone);
            $(".modal-edit-leads").trigger("click");
        }
    });
});
$(document).on('click', '.modal-edit-client-leads-ajax', function () {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: base_url + 'leads/convert_to_client_id',
        data: "id=" + id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {

            console.log(result.data[0].id);
            csrfName = result.csrfName;
            csrfHash = result.csrfHash;

            $('#update_id').val(result.data[0].id);
            $('.update_title').val(result.data[0].title);
            $('.update_email').val(result.data[0].email);
            $('.update_phone').val(result.data[0].phone);
            $(".modal-edit-client-leads").trigger("click");
        }
    });
});
$(document).on('click', '.delete-leads-type-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("type-id");
    swal({
            title: 'Are you sure?',
            text: 'All Leads Type Releted Data Will Once deleted, you will not be able to recover that data!',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'leads/delete/' + id,
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
$('#fillter-leads').on('click', function (e) {
    e.preventDefault();
    $('#leads_list').bootstrapTable('refresh');
});
$(function () {

    $('#leads_between').daterangepicker({

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

    $('#leads_between').on('apply.daterangepicker', function (ev, picker) {
        $('#leads_start_date').val(picker.startDate.format('MM/DD/YYYY'));
        $('#leads_end_date').val(picker.endDate.format('MM/DD/YYYY'));
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('#leads_between').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        $('#leads_start_date').val('');
        $('#leads_end_date').val('');
    });

});
!function (a) {
    "use strict";
    var t = function () {
        this.$body = a("body")
    };
    t.prototype.init = function () {
        a('[data-plugin="dragula"]').each(function () {
            var t = a(this).data("containers"), n = [];
            if (t) for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]); else n = [a(this)[0]];
            var r = a(this).data("handleclass");
            r ? dragula(n, {
                moves: function (a, t, n) {
                    return n.classList.contains(r)
                }
            }) : dragula(n).on('drop', function (el, target, source, sibling) {

                var sort = [];
                $("#" + target.id + " > div").each(function () {
                    sort[$(this).index()] = $(this).attr('id');
                });

                var id = el.id;
                var old_status = $("#" + source.id).data('status');
                var new_status = $("#" + target.id).data('status');
                var project_id = '1';

                $("#" + source.id).parent().find('.count').text($("#" + source.id + " > div").length);
                $("#" + target.id).parent().find('.count').text($("#" + target.id + " > div").length);

                // console.log('csrfHash:' +csrfHash);

                $.ajax({
                    url: base_url +'leads/lead_status_update',
                    type: 'POST',
                    data: "id=" + id + "&status=" + new_status + "&" + csrfName + "=" + csrfHash,
                    dataType: "json",
                    success: function (data) {

                        csrfName = data['csrfName'];
                        csrfHash = data['csrfHash'];
                    }
                });


            });


        })
    }, a.Dragula = new t, a.Dragula.Constructor = t
}(window.jQuery), function (a) {
    "use strict";
    a.Dragula.init()
}(window.jQuery);
function queryParams(p) {
    var from = $('#article_start_date').val();
    var to = $('#article_end_date').val();
    if (from !== '' && to !== '') {
        from = moment(from).format('YYYY-MM-DD');
        to = moment(to).format('YYYY-MM-DD');
    }
    return {
        "from": from,
        "to": to,
        "group_id": $('#article_group').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
$('#group_id').on('change', function (e) {

    var group_id = $('#group_id').val();
    if (group_id != '') {
        $.ajax({
            type: 'POST',
            url: base_url + 'knowledgebase/get_group_by_id/',
            data: csrfName + '=' + csrfHash,
            dataType: "json",
            success: function (result) {
                csrfName = csrfName;
                csrfHash = result.csrfHash;
            }
        });
    }
});

$(document).ready(function () {
    var flg = 0;

    $('#article_group_id').on("select2:open", function () {
        flg++;
        if (flg == 1) {
            var $this_html = jQuery('#wrp').html();
            $(".select2-results").append("<div class='select2-results__option'>" + $this_html + "</div>");
        }
    });
});

$(document).on('click', '#modal-add-article-group', function () {
    $("#article_group_id").select2("close");
});

tinymce.init({
    selector: '#add_article',
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
    selector: '#update_article',
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

$('#fillter-articles').on('click', function (e) {
    e.preventDefault();
    $('#articles_list').bootstrapTable('refresh');
});
$(document).on('click', '.delete-article-group-alert', function (e) {
    e.preventDefault();
    var id = $(this).data("typeId");
    swal({
        title: 'Are you sure?',
        text: 'Article Group data Will Be deleted, you will not be able to recover that data!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: base_url + 'knowledgebase/article_group_delete/' + id,
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
$(function () {

    $('#article_between').daterangepicker({

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

    $('#article_between').on('apply.daterangepicker', function (ev, picker) {
        $('#article_start_date').val(picker.startDate.format('MM/DD/YYYY'));
        $('#article_end_date').val(picker.endDate.format('MM/DD/YYYY'));
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('#article_between').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
        $('#article_start_date').val('');
        $('#article_end_date').val('');
    });

});
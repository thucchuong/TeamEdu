"use strict";
function queryParams(p) {
    return {
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
$(document).ready(function () {
    var flg = 0;
    $('#statuses_id').on("select2:open", function () {
        flg++;
        if (flg == 1) {
            var this_html = jQuery('#wrp').html();
            $(".select2-results").append("<div class='select2-results__option'>" +
                this_html + "</div>");
        }
    });
});
$(document).on('click', '#modal-add-statuses', function () {
    $("#statuses_id").select2("close");
});
$(document).ready(function () {
    var flg = 0;
    $('#statuses_task_id').on("select2:open", function () {
        flg++;
        if (flg == 1) {
            var this_html = jQuery('#wrp').html();
            $(".select2-results").append("<div class='select2-results__option'>" +
                this_html + "</div>");
        }
    });
});
$(document).on('click', '#modal-add-statuses', function () {
    $("#statuses_task_id").select2("close");
});
$(document).ready(function () {
    var flg = 0;
    $('#statuses_project_id').on("select2:open", function () {
        flg++;
        if (flg == 1) {
            var this_html = jQuery('#wrp').html();
            $(".select2-results").append("<div class='select2-results__option'>" +
                this_html + "</div>");
        }
    });
});
$(document).on('click', '#modal-add-statuses', function () {
    $("#statuses_project_id").select2("close");
});
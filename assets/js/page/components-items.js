"use strict";

function queryParams(p){
  return {
    limit:p.limit,
    sort:p.sort,
    order:p.order,
    offset:p.offset,
    search:p.search
  };
}

$('#fire-modal-31').on('hidden.bs.modal', function (e) {
      $(this).find('form').trigger('reset');
});
$('#fire-modal-3').on('hidden.bs.modal', function (e) {
  $(this).find('form').trigger('reset');
});

$(document).on('change', '.milestone_task', function (e) {
  e.preventDefault();

  var type_val = $(this).val();
  $('.task, .other').addClass('d-none');
  if (type_val == 'milestone') {
      $('.task').removeClass('d-none');
  } else if (type_val == 'other') {
      $('.other').removeClass('d-none');
  }
});
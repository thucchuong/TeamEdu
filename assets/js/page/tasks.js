"use strict";
if (document.getElementById("user_id") && document.getElementById("client_id")) {

  document.getElementById("user_id").selectedIndex = -1;
  document.getElementById("client_id").selectedIndex = -1;
}
tinymce.init({
  selector: '#example-textarea',
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
$(document).ready(() => {
  //select2
  setTimeout(() => {
    $("#user_id").select2({
      placeholder: "Select user",
    });
    $("#client_id").select2({
      placeholder: "Select Client",
    });

  }, 100);
});
function queryParams2(p) {

  var from = $('#tasks_start_date').val();
  var to = $('#tasks_end_date').val();
  if (from !== '' && to !== '') {
    from = moment(from).format('YYYY-MM-DD');
    to = moment(to).format('YYYY-MM-DD');
  }
  return {
    "user_id": $('#user_id').val(),
    "client_id": $('#client_id').val(),
    "project": $('#projects_name').val(),
    "status": $('#tasks_status').val(),
    "from": from,
    "to": to,
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search
  };
}

function queryParams(p) {

  var from = $('#tasks_start_date').val();
  var to = $('#tasks_end_date').val();
  if (from !== '' && to !== '') {
    from = moment(from).format('YYYY-MM-DD');
    to = moment(to).format('YYYY-MM-DD');
  }
  return {
    "user_id": $('#user_id').val(),
    "client_id": $('#client_id').val(),
    "project": $('#projects_name').val(),
    "status": $('#tasks_status').val(),
    "from": from,
    "to": to,
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search
  };
}

$('#fillter-tasks').on('click', function (e) {
  e.preventDefault();
  $('#tasks_list').bootstrapTable('refresh');
});

$(function () {

  $('#tasks_between').daterangepicker({

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

  $('#tasks_between').on('apply.daterangepicker', function (ev, picker) {
    $('#tasks_start_date').val(picker.startDate.format('MM/DD/YYYY'));
    $('#tasks_end_date').val(picker.endDate.format('MM/DD/YYYY'));
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });

  $('#tasks_between').on('cancel.daterangepicker', function (ev, picker) {
    $(this).val('');
    $('#tasks_start_date').val('');
    $('#tasks_end_date').val('');
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
          url: base_url + 'projects/task_status_update',
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

function queryParams1(p) {
  return {
    "id": $('#xyz').data('id'),
    limit: p.limit,
    sort: p.sort,
    order: p.order,
    offset: p.offset,
    search: p.search
  };
}

function get_milestone_and_user_data() {
  var option = "";
  var user_option = "";
  $("#milestone_id").html(option);
  let form_data = {
    project_id: document.getElementById("project_id_data").value,
    [csrfName]: csrfHash,
  };
  $.ajax({
    url: base_url + 'tasks/get_milestone_and_user_data',
    type: "POST",
    data: form_data,
    success: function (result) {
      var data = JSON.parse(result);
      console.log(data);
      csrfName = data.csrfName;
      csrfHash = data.csrfHash;
      var milestone = data['milestone'];
      var users = data['all_user'];
      milestone.forEach((milestones) => {
        option =
          option +
          `<option value="` +
          milestones["id"] +
          `">` +
          milestones["title"] +
          `</option>`;
      });
      $("#milestone_id").html(option)

      users.forEach((user) => {
        user_option =
          user_option +
          `<option value="` +
          user["id"] +
          `">` +
          user["first_name"] + ' ' + user["last_name"] +
          `</option>`;
      });
      $("#update_user_id").html(user_option)

    },
    error: function (error) {
      console.log(error);
    },
  });
}
//Upload data
$(".todo-list-add").on("click", function(e) {
    e.preventDefault();
    // window.location.reload();
    var todo_text = $("#todo_text").val();
    var id = $('#todo_id').val();

    var data = {
        [csrfName]: csrfHash,
        'id': id,
        'todo_text_in': todo_text,
    };
    $.ajax({
        type: "POST",
        url: base_url + "todo/add_todo_list",
        data: data,
        dataType: "JSON",
        success: function(response) {
            csrfName = response.csrfName;
            csrfHash = response.csrfHash;
            // location.reload();

            if (response.error == true) {
                iziToast.error({
                    title: response.message,
                    message: "",
                    position: "topRight",
                });
            } else {
                if ($('#add').hasClass('btn-success')) {
                    $('#add').addClass('btn-primary');
                    $('#add').removeClass('btn-success');
                    $('#add').text('Add');
                }
                $('.empty-todos').addClass('d-none');

                if (id == '') {
                    var todo = `
                    <div class='form-check list-wrapper' draggable="true" id=" ${response.id}">
                        <div class="row">
                            <div class="col-md-11 mt-2">
                                <div class="row h5">
                                    <input class='checkbox check_test' 
                                    name="${response.id}"                                     
                                    type='checkbox'  onclick='update_status(this)' 
                                    id="${response.id}" 
                                    position="<?= $list['position'] ?>">
                                    <label for="${response.id}">
                                        <?php $status = (isset($list['status']) && $list['status'] == '1') ? '1' : '' ?>
                                        <p class="ml-3 mb-0" id="desc-${response.id}">
                                            ${todo_text} 
                                        </p>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div onclick='remove(this)' id="${response.id}" class="btn btn-danger btn-sm">
                                    <i class='fa fa-trash  text-white'></i>
                                </div>
                                <div onclick='edit_task(this)' data-description="${todo_text}" id="${response.id}" class="btn btn-primary btn-sm">
                                    <i class='fa fa-pen text-white'></i>
                                </div>
                            </div>
                        </div>
                    </div>  
                    `;
                    $("#tolist").append(todo);
                    $('#todo_text').val('');
                    // return;
                } else {
                    // $('#desc-' + id).css('text-decoration', 'line-through');
                    $('#desc-' + id).text(todo_text);
                }

                iziToast.success({
                    title: response.message,
                    message: "",
                    position: "topRight",
                });
            }
        },
    });
});

$.get


// Update status
function update_status(e) {
    var id = e['id'];
    var name = e['name'];
    var status = e['status'];


    var checkbox_test = $('.check_test');


    var is_checked = $('input[name=' + name + ']:checked');
        if (is_checked.length >= 1) {
        var data = {
            [csrfName]: csrfHash,
            'id': id,
            'status': 1,
        };

        $.ajax({
            url: base_url + "todo/status",
            method: "POST",
            dataType: "json",
            data: data,
            success: function(data) {
                $('#desc-' + id).css('text-decoration', 'line-through');
                // console.log($('#desc-' + id));

                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
            }
        })
    } else {
        var data = {
            [csrfName]: csrfHash,
            'id': id,
            'status': 0,
        };

        $.ajax({
            url: base_url + "todo/unchecked",
            method: "POST",
            dataType: "json",
            data: data,
            success: function(data) {
                $('#desc-' + id).css('text-decoration', 'none');
                csrfName = data.csrfName;
                csrfHash = data.csrfHash;
            }
        })
    }
}


// Remove list
function remove(e) {
    var id = e['id'];

    var data = {
        [csrfName]: csrfHash,
        'id': id,
    };


    $.ajax({
        url: base_url + "todo/delete",
        method: "POST",
        dataType: "json",
        data: data,
        success: function(data) {
            csrfName = data.csrfName;
            csrfHash = data.csrfHash;
            location.reload();

        }
    })

}

//Edit
function edit_task(e) {
    var id = e['id'];
    var desc = $(e).data('description');
    $('#todo_text').val(desc);
    $('#todo_id').val(id);
    $('#add').text('Update');
    if (id != '') {
        $('#add').removeClass('btn-primary');
        $('#add').addClass('btn-success');
    }
}

//Sortable
$(document).ready(function() {
    $(".sort").sortable({
        animation: 150,
        onUpdate: function(event, ui) {
            var sequence = "";

            for (var i = 0; i < $('.sort')[0].children.length; i++) {
                sequence += $('.sort')[0].children[i].getAttribute('id') + ", ";
            }

            var data = {
                [csrfName]: csrfHash,
                'sequence': sequence
            };

            $.ajax({
                type: "POST",
                url: "todo/update_position",
                data: data,
                dataType: "JSON",
                success: function(response) {
                    csrfName = response.csrfName;
                    csrfHash = response.csrfHash;
                    if (response.error) {
                        iziToast.error({
                            title: response.message,
                            message: "",
                            position: "topRight",
                        });
                    } else {
                        iziToast.success({
                            title: response.message,
                            message: "",
                            position: "topRight",
                        });
                    }
                }
            });
        }
    });
});
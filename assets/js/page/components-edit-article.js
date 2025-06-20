$(document).ready(function (e) {
    var id = $("#id").val();
    console.log(id);    
    $.ajax({
        type: 'POST',
        url: base_url + 'knowledgebase/get_article_by_id',
        data: "id=" + article_id + "&" + csrfName + "=" + csrfHash,
        dataType: "json",
        success: function (result) {
            csrfName = csrfName;
            csrfHash = result.csrfHash;
            $('article_id').val(result.id);
            $('#title').val(result.title);
            $('#article_group_id').val(result.group_id);
            $('#add_article').val(result.article_description);
        }
    });
});
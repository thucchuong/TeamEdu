"use strict";
function display_articles(articles) {
    var display_articles = '';
    var i;
    var articles = articles.articles;
    display_articles +='<div class="card-body" style="background-color: white; padding: 1.25rem;">';
    for (i = 0; i < articles.length; i++) {
        var description = articles[i].description.replace(/"/g, '');
        description = description.replace(/(\\r\\n|\n|\r)/gm, '');
        description = description.replace(/\u00a0/g, '');
        description = description.replace(/<\/?[^>]+>/gi, '');
        display_articles += '<h4 class="tw-mt-0 tw-mb-2 kb-article-single-heading tw-font-semibold tw-text-xl tw-text-neutral-300"> <a class="ar_list" href="' + base_url + 'knowledgebase/view-article/' + articles[i].slug + '"> ' + articles[i].title + ' </a> </h4>' +
            '<div class="tc-content kb-article-content tw-text-neutral-700 mb-0" style="max-height: 4.5em; overflow: hidden;">' + description + '</div> <hr>';
    }
    display_articles += '</div>';
    $('#get_articles').append(display_articles);
}



function get_articles(limit = 10, offset = 0, search_data = '') {
    $.ajax({
        type: 'GET',
        url: `${base_url}/knowledgebase/search?limit=${limit}&offset=${offset}&search=${search_data}`,
        dataType: 'json',
        beforeSend: function () {
            $("#get_articles").html(`<div class="text-center" style='min-height:250px;'><h4>Please wait...loading articles...</h4></div>`);
        },
        success: function (articles) {
            if (articles.error == false) {
                $('#get_articles').empty();
                $("#search_article").val();
                display_articles(articles);
            } else {
                $('#get_articles').html(articles.message);
                $('#get_articles').empty();
            }
        }
    });
}

$('#search_article').on('keyup', function (e) {
    e.preventDefault();
    var search = $(this).val();
    get_articles(10, 0, search);
});



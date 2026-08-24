jQuery(document).ready(function ($) {

    $.ajax({
        url: ajax.get_news + "?action=get_news",
        type: 'POST',
        data: JSON.stringify({ endpoint: 'top-headlines' }),
        success: function (res, status) {
            console.log(res)
        },
        error: function (error, status) {
            console.log(error)
        }
    })

})
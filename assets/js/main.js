$(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
        $("#goTop").fadeIn();
    } else {
        $("#goTop").fadeOut();
    }
});

$("#goTop").click(function () {
    $("html, body").animate({
        scrollTop: 0
    }, 500);
});
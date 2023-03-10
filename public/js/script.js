$(document).ready(function() {
    $("div.card-trick").slice(0, 6).show();
    $("#load-more-button").on('click', function (e) {
        e.preventDefault();
        $("div.card-trick:hidden").slice(0, 6).slideDown();
        if ($("div.card-trick:hidden").length == 0) {
            $("#load-more-button").hide('slow');
            $("#load-less-button").show('slow');
        }
    });
    $("#load-less-button").on('click', function (e) {
        e.preventDefault();
        $("div.card-trick").slice(6, $("div.card-trick").length).hide();
        $("#load-less-button").hide('slow');
        $("#load-more-button").show('slow');

    });
})
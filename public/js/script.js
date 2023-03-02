$(document).ready(function() {
    $('#load-more-button').click(function() {
        $.ajax({
            url: '/figure',
            method: 'GET',
            success: function(response) {
                console.log(response)
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    });
});
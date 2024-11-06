$(document).ready( function () {
    const prefix = $('#config').data('api-prefix');

    if (isConnected(prefix)){
        location.href = "connection.php"
    }
});

function isConnected(prefix){
    $.ajax({
        url: `${prefix}/backend/auth.php/is-connected`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response) {
                return response.is_connected;
            } else {
                return true;
            };
        },
    });
}

function Disonnect(prefix){
    $.ajax({
        url: `${prefix}/backend/auth.php/delete`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response) {
                location.href = "connection.php";
            } else {
                return true;
            };
        },
    });
}
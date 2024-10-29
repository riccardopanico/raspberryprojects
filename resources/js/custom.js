$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    statusCode: {
        301: function (responseObject, textStatus, errorThrown) {
            console.log(responseObject, textStatus, errorThrown);
        },
        302: function (responseObject, textStatus, errorThrown) {
            window.location.href = responseObject.responseJSON.url_redirect;
        },
        419: function (responseObject, textStatus, errorThrown) {
            // window.location.href = "logout"
        }
    },
    timeout: 0
});

$(document).ready(function () {
    setInterval(function () {
        if (!$('#KioskBoard-VirtualKeyboard').length) {
            window.scrollTo(0, 0);
        }
    }, 1000);
});
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

    KioskBoard.init({
        keysArrayOfObjects: null,
        keysJsonUrl: "build/kioskboard/dist/kioskboard-keys-english.json",
        language: 'it',
        theme: 'material',
        autoScroll: true,
        capsLockActive: true,
        cssAnimations: true,
        cssAnimationsDuration: 360,
        cssAnimationsStyle: 'slide',
        keysSpacebarText: 'Space',
        keysFontFamily: 'sans-serif',
        keysFontWeight: 'bold',
        keysEnterText: '<i class="fas fa-check" style="font-weight: bold;"></i>',
        keysEnterCallback: function() {

        },
        keysEnterCanClose: true
    });
    KioskBoard.run('input');



    // input:focus {
    //     color: #495057 !important;
    //     background-color: #fff !important;
    //     border-color: #80bdff !important;
    //     outline: 0 !important;
    //     box-shadow: inset 0 0 0 transparent !important;
    // }


});

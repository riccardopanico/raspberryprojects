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
            window.location.href = "logout"
        }
    },
    timeout: 0
});

$(document).ready(function () {
    setInterval(function () {
        if (!$('#KioskBoard-VirtualKeyboard').length) {
            window.scrollTo(0, 0);
        }
    }, 500);

    var KioskBoardSelector = 'input:not(#badge,#commessa)';

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

    if($(KioskBoardSelector).length) {
        KioskBoard.run(KioskBoardSelector);
    }

    // input:focus {
    //     color: #495057 !important;
    //     background-color: #fff !important;
    //     border-color: #80bdff !important;
    //     outline: 0 !important;
    //     box-shadow: inset 0 0 0 transparent !important;
    // }

    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch({
            state: $(this).prop('checked'),
            size: 'large'
        });
    })
});

function formatTimeInHoursMinutesSeconds(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const sec = Math.floor(seconds % 60);

    let result = '';
    if (hours > 0) result += `${hours}h `;
    if (minutes > 0) result += `${minutes}m `;
    result += `${sec}s`;

    return result;
}

function formatTimeWithMilliseconds(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const sec = Math.floor(seconds % 60);
    const milliseconds = Math.floor((seconds % 1) * 1000);
    return `${hours}:${minutes}:${sec}.${milliseconds}`;
}

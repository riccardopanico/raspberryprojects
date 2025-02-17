@extends(env('APP_NAME') . '.index')
@section('main')
    <style>
        #badge {
            position: absolute;
            z-index: -999999;
            opacity: 0;
        }
    </style>

    <input id="badge" name="badge">

    <div class="row" id="cronometraggio_page" style="display: inline-flex;">
        <div class="col-12" style="padding: 0; position: fixed; top: 60px; right: 0; bottom: 0; left: 0; background-color: #343a40; color: #fff;">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item" style="zoom: 0.8;">
                    <span style="display: block; text-align: center; padding: 0; padding-top: 63px; font-size: 30px; color: white;">
                        <b id="date">00/00/0000</b>
                    </span>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item" style="zoom: 0.8;">
                    <span style="display: block; text-align: center; padding: 5px 0; font-size: 120px; color: white;">
                        <b id="time">00:00:00</b>
                    </span>
                </li>
            </ul>
        </div>
    </div>
@endsection

@section('script')
    <script>

        var SwalPing = null;
        var autocloseMsgTimer = null;
        var wifiOnTimer = null;
        var pingTimer;
        var pingTimeoutTimer;

        var forzaFocus = true;
        var $badge = $("#badge");

        function registraBadge(badge) {
            if (autocloseMsgTimer === null) {
                queueMessage({action: 'registraBadge', badge: badge});
                sendMessage();
            }
        }

        function gestisciFocus() {
            if (forzaFocus) {
                $badge.focus();
            }
        }

        function updateDisplay() {
            var currentTime = new Date();
            var formattedTime = currentTime.getHours().toString().padStart(2, '0') + ':' +
                currentTime.getMinutes().toString().padStart(2, '0') + ':' +
                currentTime.getSeconds().toString().padStart(2, '0');
            var formattedDate = currentTime.getDate().toString().padStart(2, '0') + '/' +
                (currentTime.getMonth() + 1).toString().padStart(2, '0') + '/' + // +1 perché i mesi partono da 0
                currentTime.getFullYear();
            $('#time').text(formattedTime);
            $('#date').text(formattedDate);
        }

        updateDisplay();
        $(document).ready(function() {
            setInterval(updateDisplay, 1000);
            gestisciFocus();
        });

        $(document).on('click touchend', function(e) {
            gestisciFocus();
        });

        $badge.on('focusout', function(e) {
            if (!$badge.is(":focus") && forzaFocus) {
                gestisciFocus();
            }
        });

        $(document).on('keydown', function(e) {
            if (!$badge.is(":focus") && forzaFocus) {
                gestisciFocus();
            }
        });

        $badge.keypress(function(e) {
            if (e.keyCode === 13) {
                var badge = $(this).val();
                e.preventDefault();
                // console.log(badge);
                // settingsSave('badge', badge);
                registraBadge(badge);
                gestisciFocus();
                $(this).val('');
            }
        });

        function settingsSave(setting, value) {
            if (!value) {
                Swal.fire({
                    icon: "error",
                    title: "Errore!",
                    text: "Nessun valore inserito!",
                    customClass: {
                        popup: 'zoom-swal-popup'
                    }
                });
                return;
            }

            const settingsInfo = {
                'badge': {
                    successTitle: '<strong>Badge</strong> salvato<br>con successo!',
                    errorTitle: '<strong>Badge</strong> non salvato!',
                    onSuccess: () => {
                        $('#badge_view').text(value);
                    }
                }
            };

            const info = settingsInfo[setting];
            const $button = $("[data-state]#btn_" + setting);

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "{{ route('settingsSave') }}",
                data: {
                    setting: setting,
                    value: value
                }
            }).done(function(data) {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: info.successTitle,
                        text: " ",
                        showConfirmButton: false,
                        timer: 1500,
                        customClass: {
                            popup: 'zoom-swal-popup'
                        }
                    });
                    $button.data('state', 1);

                    if (typeof info.onSuccess === 'function') {
                        info.onSuccess();
                    }
                } else {
                    Swal.fire({
                        icon: "error",
                        title: info.errorTitle,
                        text: "Errore: " + data.msg,
                        customClass: {
                            popup: 'zoom-swal-popup'
                        }
                    });
                }
            }).fail(function(jqXHR, textStatus) {
                console.log("Errore generico!");
                $button.data('state', 0);
            });
        }
    </script>
@endsection

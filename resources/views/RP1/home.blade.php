@extends(env('APP_NAME') . '.index')
@section('main')
    <style>
        #badge {
            position: absolute;
            z-index: -999999;
            opacity: 0;
        }
        input.swal2-input {
            opacity: 0;
            height: 0px;
        }
    </style>
 <style id="countdownNavStyle">
    /* ------ STILE COUNTDOWN NAV ------ */
    #countdown-nav {
      display: none; /* Mostrato in startCountdown() */
    }
    #countdown-nav .nav-link {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 45px;
      height: 45px;
      padding: 0;
      margin: 0 5px;
    }
    #countdown-nav svg {
        width: 60px;
        height: 60px;
        overflow: visible; /* per l'effetto glow */
    }
    #countdown-nav .countdown-circle {
      fill: none;
      stroke: #fff; /* cerchio bianco */
      stroke-width: 4;
      filter: drop-shadow(0 0 8px #fff); /* glow */
      transition: stroke-dashoffset 0.2s linear; /* animazione fluida */
    }
    /* Animazione testo: pulsazione (glowPulse) */
    @keyframes glowPulse {
      0%, 100% { text-shadow: 0 0 8px #fff; }
      50%      { text-shadow: 0 0 14px #fff; }
    }
    #countdown-nav .countdown-number {
      position: absolute;
      top: 55%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: #fff;
      font-size: 2.1rem;
      font-weight: 600;
      pointer-events: none;
      animation: glowPulse 2s infinite;
    }
  </style>
    <input id="badge" name="badge">
    <div class="row" id="cronometraggio_page" style="display: inline-flex; background-image: url('{{ asset('background.jpg') }}'); background-size: cover;">
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

        var ultimaChiamata = 0;
        var ultimoBadge = null;
        function registraBadge(badge) {
            var tempoAttuale = new Date().getTime();
            if (tempoAttuale - ultimaChiamata > 5000 || badge !== ultimoBadge) {
                ultimaChiamata = tempoAttuale;
                ultimoBadge = badge;
                if (autocloseMsgTimer === null) {
                    queueMessage({action: 'registraBadge', badge: badge});
                    sendMessage();
                }
            }
        }

        function mostraInfo(badge) {

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
    function richiediBadge() {
        var timerSecondi = 5;
        Swal.fire({
            title: 'Avvicina il badge',
            input: 'text',
            icon: 'info',
            inputAttributes: {
                autocapitalize: 'off',
                style: { opacity: 0 }
            },
            showCancelButton: true,
            showConfirmButton: false,
            confirmButtonText: 'Conferma',
            cancelButtonText: 'Annulla',
            showLoaderOnConfirm: true,
            preConfirm: (badge) => {
                return $.get(`{{ route('getInfoCartellino') }}?badge=${badge}`).then(data => {
                        console.log(data);
                        if (!data.success) { throw new Error(data.error) }
                        return data
                    }).catch(error => {
                        Swal.showValidationMessage( `Richiesta fallita: ${error}` )
                    })
            },
            allowOutsideClick: () => !Swal.isLoading(),
            willClose: () => {
                clearCountdown();
                forzaFocus = true;
                gestisciFocus();
            },
            timer: timerSecondi * 1000,
            timerProgressBar: true,
            didOpen: () => {
                startCountdown(timerSecondi);
                forzaFocus = false;
                Swal.getInput().focus();
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                var secondi = 5;
                let table = `
                    <div style="max-height:250px;overflow-y:auto;">
                        <table class="table table-striped table-sm">
                            <thead class="table-dark">
                                <tr><th scope="col">Orario timbratura</th></tr>
                            </thead>
                            <tbody>
                `;
                if (result.value.data.length > 0) {
                    result.value.data.forEach(item => {
                        table += `<tr><td>${item.created_at}</td></tr>`;
                    });
                } else {
                    table += `<tr><td>Nessuna timbratura</td></tr>`;
                }
                table += `
                            </tbody>
                        </table>
                    </div>
                `;
                Swal.fire({
                    html: table,
                    icon: 'success',
                    showCloseButton: false, // Modificato per non mostrare la croce di chiusura
                    confirmButtonText: 'Chiudi',
                    timer: secondi * 1000, // Timer separato per il Swal interno
                    timerProgressBar: true,
                    willClose: () => {
                        clearCountdown();
                        forzaFocus = true;
                        gestisciFocus();
                    },
                    didOpen: () => {
                        startCountdown(secondi);
                        forzaFocus = false;
                    }
                });
            }
        });
    }
    </script>
@endsection

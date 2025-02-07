@extends(env('APP_NAME') . '.index')
@section('main')
    <div class="card mt-2 mb-1">
        <div class="card-body p-0">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td class="custom-cell">
                            <div class="header-section">Commessa</div>
                            <div class="value-section" id="commessa_view">{{ $commessa }}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <input id="commessa" name="commessa">

    <div class="row">
        <div class="col-sm-4 col-md-2 color-palette-set mt-3">
            <button type="button" data-state="{{ $richiesta_filato ? '1' : '0' }}"
                class="btn btn-block btn-primary btn-lg custom-button" style="font-weight: bold;" id="btn_richiesta_filato"
                onclick="openModal('richiesta_filato')">RICHIESTA FILATO</button>
        </div>

        <div class="col-sm-4 col-md-2 color-palette-set mt-3">
            <button type="button" class="btn btn-block btn-warning btn-lg custom-button" style="font-weight: bold;"
                id="btn_data_cambio_spola" onclick="openModal('data_cambio_spola')">CAMBIO SPOLA</button>
        </div>

        <div class="col-sm-4 col-md-2 color-palette-set mt-3">
            <button type="button" data-state="{{ $richiesta_intervento ? '1' : '0' }}"
                class="btn btn-block btn-lg custom-button" style="font-weight: bold; background-color: #aa404b; color: #fff"
                id="btn_richiesta_intervento" onclick="openModal('richiesta_intervento')">RICHIESTA INTERVENTO</button>
        </div>

        <div class="col-sm-4 col-md-2 color-palette-set mt-3 mb-3">
            <button type="button" class="btn btn-block btn-secondary btn-lg custom-button" style="font-weight: bold;"
            id="btn_commessa" onclick="openModal('commessa')">SCANSIONE MANUALE</button>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var forzaFocus = true;
        var $commessa = $("#commessa");

        function gestisciFocus() {
            if (forzaFocus) {
                $commessa.focus();
            }
        }

        $(document).ready(function() {
            gestisciFocus();
            stateButton();
        });

        $(document).on('click touchend', function(e) {
            gestisciFocus();
        });

        $commessa.on('focusout', function(e) {
            if (!$commessa.is(":focus") && forzaFocus) {
                gestisciFocus();
            }
        });

        $(document).on('keydown', function(e) {
            if (!$commessa.is(":focus") && forzaFocus) {
                gestisciFocus();
            }
        });

        $commessa.keypress(function(e) {
            if (e.keyCode === 13) {
                var commessa = $(this).val();
                e.preventDefault();
                console.log(commessa);
                settingsSave('commessa', commessa);
                gestisciFocus();
                $(this).val('');
            }
        });

        function stateButton() {
            $("button[data-state]").each(function() {
                var state = $(this).data("state");
                $(this).closest('div').removeClass('flashing');
                $(this).prop('disabled', false);

                if (state === 1) {
                    setTimeout(() => {
                        $(this).closest('div').addClass('flashing');
                        $(this).prop('disabled', true);
                    }, 50);
                }
            });
        }

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
                'data_cambio_spola': {
                    successTitle: '<strong>Cambio spola</strong><br>effettuato<br>con successo!',
                    errorTitle: '<strong>Cambio spola</strong><br>non effettuato!'
                },
                'last_barcode': {
                    successTitle: '<strong>Scansione</strong><br>effettuata<br>con successo!',
                    errorTitle: '<strong>Scansione</strong><br>non effettuata!'
                },
                'richiesta_filato': {
                    successTitle: '<strong>Richiesta filato</strong><br>effettuata<br>con successo!',
                    errorTitle: '<strong>Richiesta filato</strong><br>non effettuata!'
                },
                'richiesta_intervento': {
                    successTitle: '<strong>Richiesta intervento</strong><br>effettuata<br>con successo!',
                    errorTitle: '<strong>Richiesta intervento</strong><br>non effettuata!'
                },
                'commessa': {
                    successTitle: '<strong>Commessa</strong><br>salvata<br>con successo!',
                    errorTitle: '<strong>Commessa</strong><br>non salvata!',
                    onSuccess: () => {
                        $('#commessa_view').text(value);
                    }
                }
            };

            const info = settingsInfo[setting];
            const $button = $("[data-state]#btn_" + setting);

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "{{ route('settingsSave') }}",
                data: { setting: setting, value: value }
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
                    stateButton();

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
                stateButton();
            });
        }
    </script>
@endsection

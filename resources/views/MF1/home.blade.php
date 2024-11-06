@extends('MF1.index')
@section('main')
    <div class="card mt-2 mb-1">
        <div class="card-body p-0">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td class="custom-cell">
                            <div class="header-section">Commessa</div>
                            <div class="value-section">{{ $commessa }}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <input type="hidden" id="commessa" name="commessa">

    <div class="row">
        <div class="col-sm-4 col-md-2 color-palette-set mt-3">
            <button type="button" data-state="{{ $richiesta_filato ? '1' : '0' }}"
                class="btn btn-block btn-primary btn-lg custom-button" style="font-weight: bold;" id="filato"
                data-toggle="modal" onclick="openModal('filato')">RICHIESTA
                FILATO</button>
        </div>

        <div class="col-sm-4 col-md-2 color-palette-set mt-3">
            <button type="button" class="btn btn-block btn-warning btn-lg custom-button" style="font-weight: bold;"
                id="spola" data-toggle="modal" onclick="openModal('spola')">CAMBIO
                SPOLA</button>
        </div>

        <div class="col-sm-4 col-md-2 color-palette-set mt-3">
            <button type="button" data-state="{{ $richiesta_intervento ? '1' : '0' }}"
                class="btn btn-block btn-lg custom-button" style="font-weight: bold; background-color: #aa404b; color: #fff"
                id="intervento" data-toggle="modal" onclick="openModal('intervento')">RICHIESTA
                INTERVENTO</button>
        </div>

        <div class="col-sm-4 col-md-2 color-palette-set mt-3 mb-3">
            <button type="button" class="btn btn-block btn-secondary btn-lg custom-button" style="font-weight: bold;"
                id="barcode" data-toggle="modal" onclick="openModal('barcode')">SCANSIONE MANUALE</button>
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

            var title;
            var idButton = setting.split('_')[1];
            var $button = $("#" + idButton);
            var errorTitle;

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "{{ route('settingsSave') }}",
                data: {
                    setting: setting,
                    value: value
                    /* ,
                     _token: '{{ csrf_token() }}' */
                }
            }).done(function(data) {
                if (data.success) {
                    $modal.modal('hide');

                    if (idButton === 'cambio') {
                        title = "<strong>Cambio spola</strong><br>effettuato<br>con successo!";
                    } else if (idButton === 'barcode') {
                        title = "<strong>Scansione</strong><br>effettuata<br>con successo!";
                    } else {
                        title = "<strong>Richiesta " + idButton + "</strong><br>effettuata<br>con successo!";
                    }

                    Swal.fire({
                        icon: "success",
                        title: title,
                        text: " ",
                        showConfirmButton: false,
                        timer: 1500,
                        customClass: {
                            popup: 'zoom-swal-popup'
                        }
                    });

                    $button.data('state', 1);
                    stateButton();
                } else {
                    if (idButton === 'cambio') {
                        errorTitle = "<strong>Cambio spola</strong><br>non effettuato!";
                    } else if (idButton === 'barcode') {
                        errorTitle = "<strong>Scansione</strong><br>non effettuata!";
                    } else {
                        errorTitle = "<strong>Richiesta " + idButton + "</strong><br>non effettuata!";
                    }

                    Swal.fire({
                        icon: "error",
                        title: errorTitle,
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

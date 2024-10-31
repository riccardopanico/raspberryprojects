@extends('MF1.index')
@section('main')
    {{-- <div class="input-group input-group-lg mb-2 mt-2">
        <div class="input-group-prepend">
            <span class="input-group-text no-border fixed-width-home"><i class="fas fa-id-card"></i></span>
        </div>
        <input type="text" class="font-lg form-control" placeholder="Operatore" disabled>
        <div class="input-group-append">
            <span class="input-group-text no-border" style="color: #000">0010452223</span>
        </div>
    </div> --}}
    <div class="input-group input-group-lg mt-2 mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text no-border fixed-width-home"><i class="fas fa-barcode"></i></span>
        </div>
        <input type="text" class="font-lg form-control" placeholder="Commessa" disabled>
        <div class="input-group-append">
            <span class="input-group-text no-border" style="color: #000">50</span>
        </div>
    </div>
    <input type="hidden" id="commessa" name="commessa">

    <div class="row">
        <div class="col-sm-4 col-md-2 {{ $richiesta_filato ? 'flashing' : '' }}">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button" style="font-weight: bold;"
                    id="filato" data-toggle="modal" onclick="openModal('filato')"
                    {{ $richiesta_filato ? 'disabled' : '' }}>RICHIESTA
                    FILATO</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-warning btn-lg custom-button" style="font-weight: bold;"
                    id="spola" data-toggle="modal" onclick="openModal('spola')">CAMBIO
                    SPOLA</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2 {{ $richiesta_intervento ? 'flashing' : '' }}">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-danger btn-lg custom-button" style="font-weight: bold;"
                    id="intervento" data-toggle="modal" onclick="openModal('intervento')"
                    {{ $richiesta_intervento ? 'disabled' : '' }}>RICHIESTA
                    INTERVENTO</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3 mb-3">
                <button type="button" class="btn btn-block btn-secondary btn-lg custom-button" style="font-weight: bold;"
                    id="barcode" data-toggle="modal" onclick="openModal('barcode')">SCANSIONE MANUALE</button>
            </div>
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
            var errorTitle;

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "{{ route('settingsSave') }}",
                data: {
                    setting: setting,
                    value: value,
                    _token: '{{ csrf_token() }}'
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
            });

        }
    </script>
@endsection

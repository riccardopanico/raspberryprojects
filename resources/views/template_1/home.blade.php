@extends('template_1.index')
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
    <input type="hidden" id="badge" name="badge">

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
                    id="spola" data-toggle="modal" onclick="openModal('spola')"
                    {{ $cambio_spola ? 'disabled' : '' }}>CAMBIO
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
                    id="scansiona" data-toggle="modal" onclick="openModal('scansiona')">SCANSIONE MANUALE</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var forzaFocus = true;
        var $badge = $("#badge");

        function gestisciFocus() {
            if (forzaFocus) {
                $badge.focus();
            }
        }

        $(document).ready(function() {
            gestisciFocus();
        });

        $(document).on('click touchend', function(e) {
            gestisciFocus();
        });

        // $badge.on('focusout', function(e) {
        //     if (!$badge.is(":focus") && forzaFocus) {
        //         gestisciFocus();
        //     }
        // });

        $(document).on('keydown', function(e) {
            if (!$badge.is(":focus") && forzaFocus) {
                gestisciFocus();
            }
        });

        $badge.keypress(function(e) {
            if (e.keyCode === 13) {
                var badge = $(this).val();
                e.preventDefault();
                gestisciFocus();
                $(this).val('');
            }
        });

        KioskBoard.init({
            keysArrayOfObjects: null,
            keysJsonUrl: "build/kioskboard/dist/kioskboard-keys-english.json",
            // keysNumpadArrayOfNumbers: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
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

        var $modal;

        function openModal(action) {
            $modal = $('#modal-xl').clone();
            $modal.removeAttr('id');

            const modalContent = {
                'filato': {
                    title: '<strong>Richiesta Filato</strong>',
                    body: '<div class="text-center">Confermi di voler inoltrare la richiesta?</div>',
                    footerClass: 'modal-footer justify-content-between',
                    action: () => settingsSave('richiesta_filato', 1),
                    // alert_title: '<strong>Richiesta filato</strong><br>effettuata<br>con successo!',
                    // text: '<strong>Richiesta filato</strong><br>non effettuata!',
                },
                'spola': {
                    title: '<strong>Cambio Spola</strong>',
                    body: '<div class="text-center">Confermi di aver effettuato il cambio?</div>',
                    footerClass: 'modal-footer justify-content-between',
                    action: () => settingsSave('cambio_spola', 1),
                    // alert_title: '<strong>Cambio spola</strong><br>effettuato<br>con successo!',
                    // text: '<strong>Cambio spola</strong><br>non effettuato!',
                },
                'intervento': {
                    title: '<strong>Richiesta Intervento</strong>',
                    body: '<div class="text-center">Confermi di voler inoltrare la richiesta?</div>',
                    footerClass: 'modal-footer justify-content-between',
                    action: () => settingsSave('richiesta_intervento', 1),
                    // alert_title: '<strong>Richiesta intervento</strong><br>effettuata<br>con successo!',
                    // text: '<strong>Richiesta intervento</strong><br>non effettuata!',
                },
                'scansiona': {
                    title: '<strong>Scansiona</strong>',
                    body: '<input id="last_barcode" name="last_barcode" type="text" placeholder="Inserisci un valore..." class="form-control" data-kioskboard-type="numpad">',
                    footerClass: 'modal-footer justify-content-between',
                    action: () => settingsSave('last_barcode', $('#last_barcode').val()),
                    // alert_title: '<strong>Scansione</strong><br>effettuata<br>con successo!',
                    // text: '<strong>Scansione</strong><br>non effettuata!',
                },
                'modalSpola': {
                    title: '<div class="text-center"><i class="fas fa-exclamation-triangle"></i><strong> ATTENZIONE </strong><i class="fas fa-exclamation-triangle"></i></div>',
                    body: '<div class="text-center" style="font-size: 1.8rem;"><strong>Spola in esaurimento</strong></div>',
                    footerClass: 'modal-footer justify-content-center bg-danger',
                    action: () => $modal.modal('hide'),
                    // alert_title: '',
                    // text: '',
                },
                'arresta': {
                    title: '<div class="text-center" style="font-weight: bold;">Spegnimento</div>',
                    body: `<div class="text-center" style="font-size: 1.5rem;">Sicuro di voler arrestare il dispositivo?</div>
                           <div class="d-flex justify-content-center mt-3">
                           <button type="button" class="btn btn-default btn-flat" style="background-color: #e9ecef; color: black;" data-dismiss="modal">Annulla</button>
                           </div>`,
                    footerClass: 'modal-footer justify-content-between',
                    // alert_title: '',
                    // text: '',
                }
            };

            const content = modalContent[action];
            const footer = createFooter(content.action, action);

            const modalConfig = {
                'scansiona': {
                    modalHeaderVisible: true,
                    modalHeaderClass: '',
                    modalTitle: content.title,
                    modalPositionClass: 'modal-xl',
                    modalBodyClass: '',
                    footerClass: content.footerClass,
                },
                'modalSpola': {
                    modalHeaderVisible: true,
                    modalHeaderClass: 'd-flex align-items-center justify-content-center bg-danger text-white',
                    modalTitle: content.title,
                    modalPositionClass: 'modal-fullscreen modal-dialog-centered bg-danger',
                    modalBodyClass: 'd-flex align-items-center justify-content-center bg-danger text-white',
                    footerClass: content.footerClass,
                },
                default: {
                    modalHeaderVisible: true,
                    modalHeaderClass: '',
                    modalTitle: content.title,
                    modalPositionClass: 'modal-xl modal-dialog-centered',
                    modalBodyClass: '',
                    footerClass: content.footerClass,
                }
            };

            const config = modalConfig[action] || modalConfig.default;

            $modal.find('.modal-header').toggle(config.modalHeaderVisible);
            $modal.find('.modal-header').attr('class', `modal-header ${config.modalHeaderClass}`);
            $modal.find('.modal-title').html(config.modalTitle || '');
            $modal.find('.modal-dialog').attr('class', `modal-dialog ${config.modalPositionClass}`);
            $modal.find('.modal-body').attr('class', `modal-body ${config.modalBodyClass}`).html(content.body);
            $modal.find('.modal-footer').attr('class', config.footerClass).html(footer);

            $modal.modal('show');

            $modal.on('shown.bs.modal', function() {
                if (action === 'scansiona') {
                    KioskBoard.run('#last_barcode');
                    $('#last_barcode').focus();
                } else {
                    gestisciFocus();
                }
            });

            $modal.on('hidden.bs.modal', function() {
                $modal.remove();
            });
        }

        function createFooter(action, type) {
            var footerButtons;

            if (type === 'arresta') {
                footerButtons =
                    `<button type="button" class="btn btn-warning btn-flat" style="font-weight: bold;" onclick="reboot()">Riavvia</button>
                <button type="button" class="btn btn-danger btn-flat" style="font-weight: bold;" onclick="shutdown()">Spegni</button>`;
            } else {
                const confirmButton = type === 'modalSpola' ?
                    `<button type="button" class="btn btn-light btn-flat" data-dismiss="modal">OK</button>` :
                    `<button type="button" class="btn btn-primary btn-flat" onclick="(${action})()">Conferma</button>`;

                const cancelButton =
                    '<button type="button" class="btn btn-default btn-flat" style="background: red; color: white;" data-dismiss="modal">Annulla</button>';

                footerButtons = type === 'modalSpola' ? confirmButton : cancelButton + confirmButton;
            }

            return footerButtons;
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

                    var idButton = setting.split('_')[1];

                    Swal.fire({
                        icon: "success",
                        title: idButton == 'spola' ?
                            "<strong>Cambio spola</strong><br>effettuato<br>con successo!" :
                            "<strong>Richiesta " + idButton + "</strong><br>effettuata<br>con successo!",
                        text: " ",
                        showConfirmButton: false,
                        timer: 1500,
                        customClass: {
                            popup: 'zoom-swal-popup'
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: idButton == 'spola' ?
                            "<strong>Cambio spola</strong><br>non effettuato!" : "<strong>Richiesta " +
                            idButton + "</strong><br>non effettuata!",
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

        function reboot() {
            $.ajax({
                type: 'POST',
                url: "{{ route('reboot') }}",
                data: {
                    _token: '{{ csrf_token() }}'
                }
            }).done(function(data) {}).fail(function() {
                Swal.fire({
                    icon: "error",
                    title: "Errore!",
                    text: "Impossibile riavviare il dispositivo!"
                });
            });
        }

        function shutdown() {
            $.ajax({
                type: 'POST',
                url: "{{ route('shutdown') }}",
                data: {
                    _token: '{{ csrf_token() }}'
                }
            }).done(function(data) {}).fail(function() {
                Swal.fire({
                    icon: "error",
                    title: "Errore!",
                    text: "Impossibile spegnere il dispositivo!"
                });
            });
        }
    </script>
@endsection
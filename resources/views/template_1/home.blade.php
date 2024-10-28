@extends('template_1.index')
@section('main')
    <div class="input-group input-group-lg mb-2 mt-2">
        <div class="input-group-prepend">
            <span class="input-group-text no-border fixed-width-home"><i class="fas fa-id-card"></i></span>
        </div>
        <input type="text" class="font-lg form-control" placeholder="Operatore" disabled>
        <div class="input-group-append">
            <span class="input-group-text no-border" style="background-color: #fff; color: #000">0010452223</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text no-border fixed-width-home"><i class="fas fa-barcode"></i></span>
        </div>
        <input type="text" class="font-lg form-control" placeholder="Commessa" disabled>
        <div class="input-group-append">
            <span class="input-group-text no-border" style="background-color: #fff; color: #000">50</span>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-2 {{ $richiesta_filato ? 'flashing' : '' }}">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button" style="font-weight: bold;"
                    id="filato" data-toggle="modal" data-target="#modal-xl" onclick="openModal('filato')"
                    {{ $richiesta_filato ? 'disabled' : '' }}>RICHIESTA
                    FILATO</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-warning btn-lg custom-button" style="font-weight: bold;"
                    id="spola" data-toggle="modal" data-target="#modal-xl" onclick="openModal('spola')"
                    {{ $cambio_spola ? 'disabled' : '' }}>CAMBIO
                    SPOLA</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2 {{ $richiesta_intervento ? 'flashing' : '' }}">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-danger btn-lg custom-button" style="font-weight: bold;"
                    id="intervento" data-toggle="modal" data-target="#modal-xl" onclick="openModal('intervento')"
                    {{ $richiesta_intervento ? 'disabled' : '' }}>RICHIESTA
                    INTERVENTO</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3 mb-3">
                <button type="button" class="btn btn-block btn-secondary btn-lg custom-button" style="font-weight: bold;"
                    id="scansiona" data-toggle="modal" data-target="#modal-xl"
                    onclick="openModal('scansiona')">SCANSIONA</button>
            </div>
        </div>

        {{-- <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-xl">
            Launch Extra Large Modal
        </button> --}}
    </div>
@endsection

@section('script')
    <script>
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
                window.scrollTo(0, 0);
            },
            keysEnterCanClose: true
        });
        KioskBoard.run('input');

        function openModal(action) {
            const modalContent = {
                'filato': {
                    title: '<strong>Richiesta Filato</strong>',
                    body: 'Confermi di voler inoltrare la richiesta?',
                    footerClass: 'modal-footer justify-content-between',
                    action: () => settingsSave('richiesta_filato', 1),
                },
                'spola': {
                    title: '<strong>Cambio Spola</strong>',
                    body: 'Confermi di aver effettuato il cambio?',
                    footerClass: 'modal-footer justify-content-between',
                    action: () => settingsSave('cambio_spola', 1),
                },
                'intervento': {
                    title: '<strong>Richiesta Intervento</strong>',
                    body: 'Confermi di voler inoltrare la richiesta?',
                    footerClass: 'modal-footer justify-content-between',
                    action: () => settingsSave('richiesta_intervento', 1),
                },
                'scansiona': {
                    title: '<strong>Scansiona</strong>',
                    body: '<input id="last_barcode" name="last_barcode" type="text" placeholder="Inserisci un valore..." class="form-control" data-kioskboard-type="numpad">',
                    footerClass: 'modal-footer justify-content-between',
                    action: () => settingsSave('last_barcode', $('#last_barcode').val()),
                },
            };

            const content = modalContent[action];
            const footer = createFooter(content.action);

            const modalConfig = {
                'scansiona': {
                    modalHeaderVisible: true,
                    modalHeaderClass: '',
                    modalTitle: content.title,
                    modalPositionClass: 'modal-xl',
                    modalBodyClass: '',
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

            $('#modal-xl').find('.modal-header').toggle(config.modalHeaderVisible);
            $('#modal-xl').find('.modal-title').html(config.modalTitle || '');
            $('#modal-position').attr('class', `modal-dialog ${config.modalPositionClass}`);
            $('#modal-xl').find('.modal-body').attr('class', `modal-body ${config.modalBodyClass}`).html(content.body);
            $('#modal-xl').find('.modal-footer').attr('class', config.footerClass).html(footer);

            $('#modal-xl').modal('show');

            $('#modal-xl').on('shown.bs.modal', function() {
                if (action === 'scansiona') {
                    KioskBoard.run('#last_barcode');
                    $('#last_barcode').focus();
                }
            });
        }

        function createFooter(action) {
            var confirmButton;
            if (action === 'scansiona') {
                confirmButton =
                    `<button type="button" class="btn btn-primary btn-flat" id="salva_barcode">Conferma</button>`
            } else {
                confirmButton =
                    `<button type="button" class="btn btn-primary btn-flat" onclick="(${action})()">Conferma</button>`
            }
            const cancelButton =
                '<button type="button" class="btn btn-default btn-flat" style="background: red; color: white;" data-dismiss="modal">Annulla</button>';
            // const closeButton =
            //     '<button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Chiudi</button>';

            return cancelButton + confirmButton;
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
                window.scrollTo(0, 0);
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
                    $('#modal-xl').modal('hide');

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
                    window.scrollTo(0, 0);
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
                    window.scrollTo(0, 0);
                }
            }).fail(function(jqXHR, textStatus) {
                window.scrollTo(0, 0);
                console.log("Errore generico!");
            });
        }
    </script>
@endsection

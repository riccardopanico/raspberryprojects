@extends('template_1.index')
@section('main')
    <div class="input-group input-group-lg mb-2 mt-2">
        <div class="input-group-prepend">
            <span class="input-group-text fixed-width-home">ID</span>
        </div>
        <input type="text" class="font-lg form-control" placeholder="ID Macchina" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">{{ $id_macchina }}</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2">
        <div class="input-group-prepend">
            <span class="input-group-text fixed-width-home"><i class="fas fa-id-card"></i></span>
        </div>
        <input type="text" class="font-lg form-control" placeholder="ID Operatore" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">0010452223</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-1">
        <div class="input-group-prepend">
            <span class="input-group-text fixed-width-home"><i class="fas fa-barcode"></i></span>
        </div>
        <input type="text" class="font-lg form-control" placeholder="Commessa" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">50</span>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-2 {{ $richiesta_filato ? 'blink' : '' }}">
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

        <div class="col-sm-4 col-md-2 {{ $richiesta_intervento ? 'blink' : '' }}">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-danger btn-lg custom-button" style="font-weight: bold;"
                    id="intervento" data-toggle="modal" data-target="#modal-xl" onclick="openModal('intervento')"
                    {{ $richiesta_intervento ? 'disabled' : '' }}>RICHIESTA
                    INTERVENTO</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-info btn-lg custom-button" style="font-weight: bold;"
                    id="manuale" data-toggle="modal" data-target="#modal-xl" onclick="openModal('manuale')">MANUALE
                    D'USO</button>
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
        function openModal(action) {
            const modalContent = {
                'filato': {
                    title: '<strong>Richiesta FILATO</strong>',
                    body: 'Confermi di voler inoltrare la <br><strong><i>richiesta di FILATO</i></strong>?',
                    footerClass: 'modal-footer justify-content-between',
                    action: () => settingsSave('richiesta_filato', 1),
                },
                'spola': {
                    title: '<strong>Cambio SPOLA</strong>',
                    body: 'Confermi di aver effettuato il<br><strong><i>cambio SPOLA</i></strong>?',
                    footerClass: 'modal-footer justify-content-between',
                    action: () => settingsSave('cambio_spola', 1),
                },
                'intervento': {
                    title: '<strong>Richiesta INTERVENTO</strong>',
                    body: 'Confermi di voler inoltrare la <br><strong><i>richiesta di INTERVENTO</i></strong>?',
                    footerClass: 'modal-footer justify-content-between',
                    action: () => settingsSave('richiesta_intervento', 1),
                },
                'manuale': {
                    title: '<strong>MANUALE D\'USO</strong>',
                    body: `<iframe src="{{ asset('pdf/manuale_uso.pdf') }}" width="100%" height="425px"></iframe>`,
                    footerClass: 'modal-footer',
                    action: null,
                },
                'scansiona': {
                    title: '<strong>SCANSIONA</strong>',
                    body: 'Scansiona...',
                    footerClass: 'modal-footer',
                    action: null,
                },
            };

            const content = modalContent[action];
            const footer = createFooter(content.action);

            $('#modal-xl').find('.modal-title').html(content.title);
            $('#modal-xl').find('.modal-body').html(content.body);
            $('#modal-xl').find('.modal-footer').attr('class', content.footerClass).html(footer);

            $('#modal-xl').modal('show');
        }

        function createFooter(action) {
            const confirmButton = `<button type="button" class="btn btn-primary" onclick="(${action})()">Conferma</button>`
            const cancelButton = '<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>';
            const closeButton = '<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>';

            return action ? cancelButton + confirmButton : closeButton;
        }

        function settingsSave(setting, value) {
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
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: idButton == 'spola' ?
                            "<strong>Cambio spola</strong><br>non effettuato!" : "<strong>Richiesta " +
                            idButton + "</strong><br>non effettuata!",
                        text: "Errore: " + data.msg
                    });
                }
            }).fail(function(jqXHR, textStatus) {
                console.log("Errore generico!");
            });
        }
    </script>
@endsection
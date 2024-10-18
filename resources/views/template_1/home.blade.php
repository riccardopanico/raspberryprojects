@extends('template_1.index')
@section('main')
    <div class="input-group input-group-lg mb-2 mt-2">
        <div class="input-group-prepend">
            <span class="input-group-text fixed-width-home">ID</span>
        </div>
        <input type="text" class="font-lg form-control" placeholder="ID Macchina" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">100</span>
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
        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button" style="font-weight: bold;"
                    id="filato" data-toggle="modal" data-target="#modal-xl" onclick="openModal('filato')">RICHIESTA
                    FILATO</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-warning btn-lg custom-button" style="font-weight: bold;"
                    id="spola" data-toggle="modal" data-target="#modal-xl" onclick="openModal('spola')">CAMBIO
                    SPOLA</button>
            </div>
        </div>

        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3">
                <button type="button" class="btn btn-block btn-danger btn-lg custom-button" style="font-weight: bold;"
                    id="intervento" data-toggle="modal" data-target="#modal-xl" onclick="openModal('intervento')">RICHIESTA
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
            var title = '';
            var body = '';
            var footer = '';
            var footerClass = '';

            switch (action) {
                case 'filato':
                    title = '<strong>Richiesta FILATO</strong>';
                    body = 'Confermi di voler inoltrare la <br><strong><i>richiesta di FILATO</i></strong>?';
                    footer =
                        '<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button><button type="button" class="btn btn-primary">Conferma</button>';
                    footerClass = 'modal-footer justify-content-between';
                    break;
                case 'spola':
                    title = '<strong>Cambio SPOLA</strong>';
                    body = 'Confermi di voler inoltrare la richiesta di <br><strong><i>cambio SPOLA</i></strong>?';
                    footer =
                        '<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button><button type="button" class="btn btn-primary">Conferma</button>';
                    footerClass = 'modal-footer justify-content-between';
                    break;
                case 'intervento':
                    title = '<strong>Richiesta INTERVENTO</strong>';
                    body = 'Confermi di voler inoltrare la <br><strong><i>richiesta di INTERVENTO</i></strong>?';
                    footer =
                        `<button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button><button type="button" class="btn btn-primary"
                    onclick="settingsSave('richiesta_intervento', 1)">Conferma</button>`;
                    footerClass = 'modal-footer justify-content-between';
                    break;
                case 'manuale':
                    title = '<strong>MANUALE D\'USO</strong>';
                    body =
                        `<iframe src="{{ asset('pdf/manuale_uso.pdf') }}" width="100%"></iframe>`;
                    footer = '<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>';
                    footerClass = 'modal-footer';
                    break;
                case 'scansiona':
                    title = '<strong>SCANSIONA</strong>';
                    body = 'Scansiona...';
                    footer = '<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>';
                    footerClass = 'modal-footer';
                    break;
            }

            $('#modal-xl').find('.modal-title').html(title);
            $('#modal-xl').find('.modal-body').html(body);
            $('#modal-xl').find('.modal-footer').html(footer).attr('class', footerClass);

            $('#modal-xl').modal('show');
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
                    Swal.fire({
                        icon: "success",
                        title: "<strong>Richiesta intervento</strong><br>effettuata<br>con successo!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    console.log("Errore invio richiesta: " + data.msg);
                }
            }).fail(function(jqXHR, textStatus) {
                console.log("Errore generico!");
            });
        }
    </script>
@endsection

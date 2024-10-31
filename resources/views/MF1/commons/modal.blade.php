<div class="modal fade" id="modal-xl" data-backdrop="static" data-keyboard="false" style="zoom: 1.5;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="zoom: 1.3;">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="modal-body text-center" style="font-size: 20px"></div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script>
    function getFormattedDate() {
        return moment().format('DD/MM/YYYY HH:mm:ss');
    }

    var $modal;

    function openModal(action) {
        $modal = $('#modal-xl').clone();
        $modal.removeAttr('id');

        const modalSettingsDefault = {
            header: '',
            headerVisible: true,
            headerClass: 'modal-header',
            dialogClass: 'modal-dialog modal-xl',
            body: '',
            bodyVisible: true,
            bodyClass: 'modal-body',
            footer: '',
            footerVisible: true,
            footerClass: '',
            title: '',
            forzaFocus: false,
            onShow: () => {},
            onClose: () => {
                $modal.remove()
            },
        };

        const modalSettings = {
            'filato': {
                title: '<strong>Richiesta Filato</strong>',
                dialogClass: 'modal-dialog modal-xl modal-dialog-centered',
                body: '<div class="text-center">Confermi di voler inoltrare la richiesta?</div>',
                footerClass: 'modal-footer justify-content-between',
                footer: `<button type="button" class="btn btn-default btn-flat" style="background: red; color: white;" data-dismiss="modal">Annulla</button>
                            <button type="button" class="btn btn-primary btn-flat" onclick="settingsSave('richiesta_filato', 1)">Conferma</button>`,
            },
            'spola': {
                title: '<strong>Cambio Spola</strong>',
                dialogClass: 'modal-dialog modal-xl modal-dialog-centered',
                body: '<div class="text-center">Confermi di aver effettuato il cambio?</div>',
                footerClass: 'modal-footer justify-content-between',
                footer: `<button type="button" class="btn btn-default btn-flat" style="background: red; color: white;" data-dismiss="modal">Annulla</button>
                            <button type="button" class="btn btn-primary btn-flat" onclick="settingsSave('data_cambio_spola', getFormattedDate())">Conferma</button>`,
            },
            'intervento': {
                title: '<strong>Richiesta Intervento</strong>',
                dialogClass: 'modal-dialog modal-xl modal-dialog-centered',
                body: '<div class="text-center">Confermi di voler inoltrare la richiesta?</div>',
                footerClass: 'modal-footer justify-content-between',
                footer: `<button type="button" class="btn btn-default btn-flat" style="background: red; color: white;" data-dismiss="modal">Annulla</button>
                            <button type="button" class="btn btn-primary btn-flat" onclick="settingsSave('richiesta_intervento', 1)">Conferma</button>`,
            },
            'barcode': {
                title: '<strong>Scansiona</strong>',
                body: '<input id="last_barcode" name="last_barcode" type="text" placeholder="Inserisci un valore..." class="form-control" data-kioskboard-type="numpad">',
                footerClass: 'modal-footer justify-content-between',
                footer: `<button type="button" class="btn btn-default btn-flat" style="background: red; color: white;" data-dismiss="modal">Annulla</button>
                            <button type="button" class="btn btn-primary btn-flat" onclick="settingsSave('last_barcode', $('#last_barcode').val())">Conferma</button>`,
                onShow: () => {
                    KioskBoard.run('#last_barcode');
                    $('#last_barcode').focus();
                },
            },
            'alert_spola': {
                headerClass: 'd-flex align-items-center justify-content-center bg-danger text-white',
                title: '<div class="text-center"><i class="fas fa-exclamation-triangle"></i><strong> ATTENZIONE </strong><i class="fas fa-exclamation-triangle"></i></div>',
                dialogClass: 'modal-fullscreen modal-dialog-centered bg-danger',
                bodyClass: 'd-flex align-items-center justify-content-center bg-danger text-white',
                body: '<div class="text-center" style="font-size: 1.8rem;"><strong>SPOLA IN ESAURIMENTO</strong></div>',
                footerClass: 'modal-footer justify-content-center bg-danger',
                footer: `<button type="button" class="btn btn-light btn-flat" data-dismiss="modal">OK</button>`,
            },
            'arresta': {
                title: '<div class="text-center" style="font-weight: bold;">Spegnimento</div>',
                dialogClass: 'modal-dialog modal-xl modal-dialog-centered',
                body: `<div class="text-center" style="font-size: 1.5rem;">Sicuro di voler arrestare il dispositivo?</div>`,
                footerClass: 'modal-footer justify-content-between',
                footer: `
                    <div class="modal-footer justify-content-between" style="margin: 0; padding: 0;">
                        <button type="button" onclick="queueMessage({ action: 'reboot' });" class="btn btn-warning btn-flat" style="font-weight: bold; width: 43.3%;">Riavvia</button>
                        <button type="button" onclick="queueMessage({ action: 'shutdown' });" class="btn btn-danger btn-flat" style="font-weight: bold; width: 43.3%;">Spegni</button>
                        <button type="button" class="btn btn-default btn-flat" style="background-color: #e9ecef; color: black; width: 100%;" data-dismiss="modal">Annulla</button>
                    </div>`,
            }
        };

        // Merge default settings with specific modal settings
        const finalModalSettings = {
            ...modalSettingsDefault,
            ...modalSettings[action]
        };

        $modal.find('.modal-header').toggle(finalModalSettings.headerVisible).addClass(finalModalSettings.headerClass);
        $modal.find('.modal-title').html(finalModalSettings.title);
        $modal.find('.modal-dialog').addClass(finalModalSettings.dialogClass);
        $modal.find('.modal-body').addClass(finalModalSettings.bodyClass).html(finalModalSettings.body);
        $modal.find('.modal-footer').addClass(finalModalSettings.footerClass).html(finalModalSettings.footer);

        $modal.modal('show');

        $modal.on('shown.bs.modal', function() {
            if (typeof finalModalSettings.onShow === 'function') {
                finalModalSettings.onShow();
            }
        });

        $modal.on('hidden.bs.modal', function() {
            if (typeof finalModalSettings.onClose === 'function') {
                finalModalSettings.onClose();
            }
        });

        forzaFocus = finalModalSettings.forzaFocus;
    }
</script>

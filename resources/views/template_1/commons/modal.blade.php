<div class="modal fade" id="modal-xl" data-backdrop="static" data-keyboard="false" style="zoom: 1.5;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
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

    function openModal(action) {
            var $modal = $('#modal-xl').clone();
            $modal.removeAttr('id');

            const modalSettingsDefault = {
                header: '',
                headerVisible: true,
                headerClass: 'modal-header',
                dialogClass: 'modal-dialog',
                body: '',
                bodyVisible: true,
                bodyClass: 'modal-body',
                footer: '',
                footerVisible: true,
                footerClass: '',
                title: '',
                positionClass: 'modal-xl modal-dialog-centered',
                forzaFocus: false,
                action: () => {},
                onShow: () => {},
                onClose: () => {},
            };

            const modalSettings = {
                'filato': {
                    title: '<strong>Richiesta Filato</strong>',
                    body: '<div class="text-center">Confermi di voler inoltrare la richiesta?</div>',
                    footerClass: 'modal-footer justify-content-between',
                    footer: `<button type="button" class="btn btn-default btn-flat" style="background: red; color: white;" data-dismiss="modal">Annulla</button>
                            <button type="button" class="btn btn-primary btn-flat" onclick="(${action})()">Conferma</button>`,
                    action: () => settingsSave('richiesta_filato', 1),
                    onClose: () => $modal.remove(),
                },
                'spola': {
                    title: '<strong>Cambio Spola</strong>',
                    body: '<div class="text-center">Confermi di aver effettuato il cambio?</div>',
                    footerClass: 'modal-footer justify-content-between',
                    footer: `<button type="button" class="btn btn-default btn-flat" style="background: red; color: white;" data-dismiss="modal">Annulla</button>
                            <button type="button" class="btn btn-primary btn-flat" onclick="(${action})()">Conferma</button>`,
                    action: () => settingsSave('cambio_spola', 1),
                    onClose: () => $modal.remove(),
                },
                'intervento': {
                    title: '<strong>Richiesta Intervento</strong>',
                    body: '<div class="text-center">Confermi di voler inoltrare la richiesta?</div>',
                    footerClass: 'modal-footer justify-content-between',
                    footer: `<button type="button" class="btn btn-default btn-flat" style="background: red; color: white;" data-dismiss="modal">Annulla</button>
                            <button type="button" class="btn btn-primary btn-flat" onclick="(${action})()">Conferma</button>`,
                    action: () => settingsSave('richiesta_intervento', 1),
                    onClose: () => $modal.remove(),
                },
                'scansiona': {
                    title: '<strong>Scansiona</strong>',
                    body: '<input id="last_barcode" name="last_barcode" type="text" placeholder="Inserisci un valore..." class="form-control" data-kioskboard-type="numpad">',
                    footerClass: 'modal-footer justify-content-between',
                    footer: `<button type="button" class="btn btn-default btn-flat" style="background: red; color: white;" data-dismiss="modal">Annulla</button>
                            <button type="button" class="btn btn-primary btn-flat" onclick="(${action})()">Conferma</button>`,
                    action: () => settingsSave('last_barcode', $('#last_barcode').val()),
                    onShow: () => {
                        KioskBoard.run('#last_barcode');
                        $('#last_barcode').focus();
                    },
                    onClose: () => $modal.remove(),
                },
                'modalSpola': {
                    title: '<div class="text-center"><i class="fas fa-exclamation-triangle"></i><strong> ATTENZIONE </strong><i class="fas fa-exclamation-triangle"></i></div>',
                    body: '<div class="text-center" style="font-size: 1.8rem;"><strong>Spola in esaurimento</strong></div>',
                    footerClass: 'modal-footer justify-content-center bg-danger',
                    action: () => $modal.modal('hide'),
                    footer: `<button type="button" class="btn btn-light btn-flat" data-dismiss="modal">OK</button>`,
                    onClose: () => $modal.remove(),
                },
                'arresta': {
                    title: '<div class="text-center" style="font-weight: bold;">Spegnimento</div>',
                    body: `<div class="text-center" style="font-size: 1.5rem;">Sicuro di voler arrestare il dispositivo?</div>
                        <div class="d-flex justify-content-center mt-3">
                        <button type="button" class="btn btn-default btn-flat" style="background-color: #e9ecef; color: black;" data-dismiss="modal">Annulla</button>
                        </div>`,
                    footerClass: 'modal-footer justify-content-between',
                    footer: `
                    <div class="modal-footer justify-content-between">
                        <button type="button" onclick="queueMessage({ action: 'reboot' });" class="btn btn-warning btn-flat" style="font-weight: bold;width: 46.5%;">Riavvia</button>
                        <button type="button" onclick="queueMessage({ action: 'shutdown' });" class="btn btn-danger btn-flat" style="font-weight: bold;width: 46.5%;">Spegni</button>
                        <button type="button" class="btn btn-default btn-flat" style="background-color: #e9ecef;color: black;width: 100%;" data-dismiss="modal">Annulla</button>
                    </div>`,
                    onClose: () => $modal.remove(),
                }
            };

            // Merge default settings with specific modal settings
            const finalModalSettings = { ...modalSettingsDefault, ...modalSettings[action] };

            $modal.find('.modal-header').toggle(finalModalSettings.headerVisible).addClass(finalModalSettings.headerClass);
            $modal.find('.modal-title').html(finalModalSettings.title);
            $modal.find('.modal-dialog').addClass(finalModalSettings.positionClass);
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

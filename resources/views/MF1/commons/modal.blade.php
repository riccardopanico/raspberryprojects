<div class="modal" id="modal-xl" data-backdrop="static" data-keyboard="false" style="zoom: 1.5;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="zoom: 1.3;">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
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

    function openModal(setting) {
        if ($modal) {
            $modal.modal('hide');
            $modal.remove();
        }
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
            onShow: () => {},
            onClose: () => {},
        };

        const modalSettings = {
            'richiesta_filato': {
                title: '<strong>Richiesta Filato</strong>',
                dialogClass: 'modal-dialog modal-xl modal-dialog-centered',
                body: '<div class="text-center">Confermi di voler inoltrare la richiesta?</div>',
                footerClass: 'modal-footer justify-content-between',
                footer: `<button type="button" class="btn btn-default btn-flat" style="background: red; color: white;" data-dismiss="modal">Annulla</button>
                            <button type="button" class="btn btn-primary btn-flat" onclick="settingsSave('richiesta_filato', 1)" data-dismiss="modal">Conferma</button>`,
            },
            'data_cambio_spola': {
                title: '<strong>Cambio Spola</strong>',
                dialogClass: 'modal-dialog modal-xl modal-dialog-centered',
                body: '<div class="text-center">Confermi di aver effettuato il cambio?</div>',
                footerClass: 'modal-footer justify-content-between',
                footer: `<button type="button" class="btn btn-default btn-flat" style="background: red; color: white;" data-dismiss="modal">Annulla</button>
                            <button type="button" class="btn btn-primary btn-flat" onclick="settingsSave('data_cambio_spola', getFormattedDate())" data-dismiss="modal">Conferma</button>`,
            },
            'richiesta_intervento': {
                title: '<strong>Richiesta Intervento</strong>',
                dialogClass: 'modal-dialog modal-xl modal-dialog-centered',
                body: '<div class="text-center">Confermi di voler inoltrare la richiesta?</div>',
                footerClass: 'modal-footer justify-content-between',
                footer: `<button type="button" class="btn btn-default btn-flat" style="background: red; color: white;" data-dismiss="modal">Annulla</button>
                            <button type="button" class="btn btn-primary btn-flat" onclick="settingsSave('richiesta_intervento', 1)" data-dismiss="modal">Conferma</button>`,
            },
            'commessa': {
                title: '<strong>Scansiona</strong>',
                body: '<input id="commessa_manuale" name="commessa_manuale" type="text" placeholder="Inserisci un valore..." class="form-control" data-kioskboard-type="numpad">',
                footerClass: 'modal-footer justify-content-between',
                footer: `<button type="button" class="btn btn-default btn-flat" style="background: red; color: white;" data-dismiss="modal">Annulla</button>
                            <button type="button" class="btn btn-primary btn-flat" onclick="settingsSave('commessa', $('#commessa_manuale').val())" data-dismiss="modal">Conferma</button>`,
                onShow: () => {
                    KioskBoard.run('#commessa_manuale');
                    setTimeout(() => {
                        $('#commessa_manuale').focus();
                    }, 500);
                },
            },
            'alert_spola': {
                headerClass: 'd-flex align-items-center justify-content-center bg-danger text-white',
                title: '<div class="text-center"><i class="fas fa-exclamation-triangle"></i><strong> ATTENZIONE </strong><i class="fas fa-exclamation-triangle"></i></div>',
                dialogClass: 'modal-fullscreen modal-dialog-centered bg-danger',
                bodyClass: 'd-flex align-items-center justify-content-center bg-danger text-white',
                body: '<div class="text-center" style="font-size: 1.8rem;"><strong>SPOLA IN ESAURIMENTO</strong></div>',
                footerClass: 'modal-footer justify-content-center bg-danger',
                footer: `<button type="button" class="btn btn-light btn-flat" data-dismiss="modal" style="font-weight: bold;">OK</button>`,
            },
            'alert_olio': {
                headerClass: 'd-flex align-items-center justify-content-center bg-danger text-white',
                title: '<div class="text-center"><i class="fas fa-exclamation-triangle"></i><strong> ATTENZIONE </strong><i class="fas fa-exclamation-triangle"></i></div>',
                dialogClass: 'modal-fullscreen modal-dialog-centered bg-danger',
                bodyClass: 'd-flex align-items-center justify-content-center bg-danger text-white',
                body: '<div class="text-center" style="font-size: 1.8rem;"><strong>OLIO IN ESAURIMENTO</strong></div>',
                footerClass: 'modal-footer justify-content-center bg-danger',
                footer: `<button type="button" class="btn btn-light btn-flat" onclick="settingsSave('data_cambio_olio', getFormattedDate())" data-dismiss="modal" style="font-weight: bold;">OK</button>`,
            },
            'arresta': {
                title: '<div class="text-center" style="font-weight: bold;">Spegnimento</div>',
                dialogClass: 'modal-dialog modal-xl modal-dialog-centered',
                body: `<div class="text-center" style="font-size: 1.5rem;">Sicuro di voler arrestare il dispositivo?</div>`,
                footerClass: 'modal-footer justify-content-between',
                footer: `
                    <div class="modal-footer justify-content-between" style="margin: 0; padding: 0;">
                        <button type="button" onclick="queueMessage({ action: 'reboot' });" class="btn btn-warning btn-flat" style="font-weight: bold; width: 43.3%;">Riavvia</button>
                        <button type="button" onclick="queueMessage({ action: 'poweroff' });" class="btn btn-danger btn-flat" style="font-weight: bold; width: 43.3%;">Spegni</button>
                        <button type="button" class="btn btn-default btn-flat" style="background-color: #e9ecef; color: black; width: 100%;" data-dismiss="modal">Annulla</button>
                    </div>`,
            }
        };

        // Merge default settings with specific modal settings
        const finalModalSettings = {
            ...modalSettingsDefault,
            ...modalSettings[setting]
        };

        $modal.find('.modal-header').toggle(finalModalSettings.headerVisible).addClass(finalModalSettings.headerClass);
        $modal.find('.modal-title').html(finalModalSettings.title);
        $modal.find('.modal-dialog').addClass(finalModalSettings.dialogClass);
        $modal.find('.modal-body').addClass(finalModalSettings.bodyClass).html(finalModalSettings.body);
        $modal.find('.modal-footer').addClass(finalModalSettings.footerClass).html(finalModalSettings.footer)

        $modal.on('shown.bs.modal', function() {
            forzaFocus = false;
            if (typeof finalModalSettings.onShow === 'function') {
                finalModalSettings.onShow();
            }
        });

        $modal.on('hidden.bs.modal', function() {
            forzaFocus = true;
            if (typeof finalModalSettings.onClose === 'function') {
                finalModalSettings.onClose();
            }
            $modal.remove()
        });
        $modal.modal('show');
    }
</script>

<div class="modal" id="modal-xl" data-backdrop="static" data-keyboard="false" style="zoom: 1.5;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="zoom: 1.3;">
            <div class="modal-header" style="padding: 13px 16px; background-color: #454d55; color: #ffffff; border-bottom-color: transparent;">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body text-center" style="font-size: 20px; background-color: #515a63!important; color: #ffffff"></div>
            <div class="modal-footer" style="background-color: #454d55; color: #ffffff; border-top-color: transparent;">
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
            'timbratura_badge': {
                title: '<strong>Timbratura Badge</strong>',
                dialogClass: 'modal-dialog modal-xl modal-dialog-centered',
                body: '<div class="text-center">Confermi di voler timbrare il badge?</div>',
                footerClass: 'modal-footer justify-content-between',
                footer: `<button type="button" class="btn btn-default btn-flat" style="background: red; color: white;" data-dismiss="modal">Annulla</button>
                            <button type="button" class="btn btn-primary btn-flat" onclick="settingsSave('timbratura_badge', 1)" data-dismiss="modal">Conferma</button>`,
            },
            'arresta': {
                title: '<div class="text-center" style="font-weight: bold; font-size: 18px;">Riavvio</div>',
                dialogClass: 'modal-dialog modal-xl modal-dialog-centered',
                body: `<div class="text-center" style="font-size: 1.0rem;">Sicuro di voler riavviare il dispositivo?</div>`,
                footerClass: 'modal-footer justify-content-between',
                footer: `
                    <div class="modal-footer" style="margin: 0; padding: 0; border: 0; width: 100%;">
                        <button type="button" class="btn btn-default btn-flat" style="background-color: #8e98a2; border-color: #8e98a2; color: black; font-weight: 700px; width: 31%;" data-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-danger btn-flat" style="opacity: 0; font-weight: bold; width: 31%;">Spegni</button>
                        <button type="button" onclick="queueMessage({ action: 'reboot' });" class="btn btn-warning btn-flat" style="font-weight: bold; width: 31%;">Riavvia</button>
                        </div>`,
                        // <button type="button" onclick="queueMessage({ action: 'poweroff' });" class="btn btn-danger btn-flat" style="font-weight: bold; width: 31%;">Spegni</button>
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

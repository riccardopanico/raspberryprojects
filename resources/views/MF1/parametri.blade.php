@extends(env('APP_NAME') . '.index')
@section('main')
<style>
    .kiosk-keyboard {
        display: none;
        position: fixed;
        bottom: -100%;
        left: 0;
        width: 100%;
        padding: 10px;
        background: #222;
        border-radius: 10px 10px 0 0;
        display: flex;
        flex-direction: column;
        gap: 5px;
        box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.3);
        z-index: 10000;
        transition: bottom 0.3s ease-in-out;
        zoom: 0.95;
    }
    .kiosk-keyboard-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #333;
        padding: 10px;
        border-radius: 10px 10px 0 0;
    }
    .kiosk-keyboard-input {
        flex-grow: 1;
        padding: 10px;
        font-size: 24px;
        text-align: center;
        background: #444;
        color: #fff;
        border: none;
        border-radius: 5px;
        margin-right: 10px;
        z-index: 10001;
    }
    .confirm-key {
        padding: 10px;
        background: #28a745;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .confirm-key i {
        font-size: 22px;
    }
    .kiosk-keys {
        display: grid;
        gap: 5px;
        padding: 10px 0;
    }
    @media (max-width: 400px) {
        .kiosk-keys {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (min-width: 401px) and (max-width: 800px) {
        .kiosk-keys {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    @media (min-width: 801px) {
        .kiosk-keys {
            grid-template-columns: repeat(6, 1fr);
        }
    }
    .kiosk-key {
        padding: 15px;
        font-size: 22px;
        text-align: center;
        background: #444;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .kiosk-key:hover {
        background: #555;
    }
</style>
    <form id="form_parametri">
        <label for="parametro_olio" class="font-lg" style="color: #000;">Parametro Olio</label>
        <div class="input-group input-group-lg mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text no-border p-0" style="color: #000;">
                    <input type="checkbox" id="parametro_olio_attivo" name="settings[parametro_olio_attivo]" {{ $parametro_olio_attivo ? 'checked' : '' }} data-bootstrap-switch>
                </span>
            </div>
            <input type="text" value="{{ $parametro_olio }}" id="parametro_olio" name="settings[parametro_olio]" id="parametro_olio"
                class="kiosk-input font-lg form-control no-border" style="text-align: right;" data-kioskboard-type="numpad">
            <div class="input-group-append">
                <span class="input-group-text no-border" style="color: #000;">t</span>
            </div>
        </div>
        <label for="parametro_spola" class="font-lg" style="color: #000;">Parametro Spola</label>
        <div class="input-group input-group-lg mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text no-border p-0" style="color: #000;">
                    <input type="checkbox" id="parametro_spola_attivo" name="settings[parametro_spola_attivo]" {{ $parametro_spola_attivo ? 'checked' : '' }} data-bootstrap-switch>
                </span>
            </div>
            <input type="text" value="{{ $parametro_spola }}" id="parametro_spola" name="settings[parametro_spola]" id="parametro_spola"
                class="kiosk-input font-lg form-control no-border" style="text-align: right;" data-kioskboard-type="numpad">
            <div class="input-group-append">
                <span class="input-group-text no-border" style="color: #000;">m</span>
            </div>
        </div>
        <label for="parametro_olio" class="font-lg" style="color: #000;">Fattore Taratura</label>
        <div class="input-group input-group-lg mb-2">
            <input type="text" value="{{ $fattore_taratura }}" id="fattore_taratura" name="settings[fattore_taratura]" id="fattore_taratura"
                class="kiosk-input font-lg form-control no-border" style="text-align: right;" data-kioskboard-type="numpad">
        </div>
        <div class="row">
            <div class="col-sm-4 col-md-2">
                <div class="color-palette-set mt-3 mb-3">
                    <button type="button" class="btn btn-block btn-primary btn-lg custom-button" style="font-weight: bold;"
                        id="salva_impostazioni" onclick="settingsSaveAll()">SALVA IMPOSTAZIONI</button>
                </div>
            </div>
        </div>
    </form>
    <style>
        .bootstrap-switch .bootstrap-switch-handle-on,
        .bootstrap-switch .bootstrap-switch-handle-off,
        .bootstrap-switch .bootstrap-switch-label {
            height: 46px;
            border: none !important;
            box-shadow: none !important;
        }

        .bootstrap-switch {
            border: none !important;
            box-shadow: none !important;
        }
    </style>
<div class="kiosk-keyboard">
    <div class="kiosk-keyboard-top">
        <input type="text" class="kiosk-keyboard-input" readonly>
        <button class="confirm-key"><i class="fas fa-check"></i></button>
    </div>
    <div class="kiosk-keys">
        <button class="kiosk-key">1</button>
        <button class="kiosk-key">2</button>
        <button class="kiosk-key">3</button>
        <button class="kiosk-key">4</button>
        <button class="kiosk-key">5</button>
        <button class="kiosk-key">6</button>
        <button class="kiosk-key">7</button>
        <button class="kiosk-key">8</button>
        <button class="kiosk-key">9</button>
        <button class="kiosk-key">0</button>
        <button class="kiosk-key">.</button>
        <button class="kiosk-key" id="kiosk-backspace">←</button>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        let keyboard = $('.kiosk-keyboard');
        let keyboardInput = $('.kiosk-keyboard-input');
        let activeInput = null;

        $('.kiosk-input').on('focus', function() {
            activeInput = $(this);
            keyboard.css('bottom', '0');
            keyboardInput.val(activeInput.val());
        });

        $(document).click(function(event) {
            if (!$(event.target).closest('.kiosk-keyboard, .kiosk-input').length) {
                keyboard.css('bottom', '-100%');
                keyboardInput.val('');
                activeInput = null;
            }
        });

        $('.kiosk-key').not('.confirm-key').click(function() {
            let value = $(this).text();
            if (value === '←') {
                keyboardInput.val(keyboardInput.val().slice(0, -1));
            } else {
                keyboardInput.val(keyboardInput.val() + value);
            }
        });

        $('.confirm-key').click(function() {
            if (activeInput) {
                activeInput.val(keyboardInput.val());
                keyboard.css('bottom', '-100%');
                keyboardInput.val('');
                activeInput = null;
            }
        });
    });
</script>
    <script>
        function settingsSaveAll() {
            if (!$("#parametro_olio").val() || !$("#parametro_spola").val() || !$("#fattore_taratura").val()) {
                Swal.fire({
                    icon: "warning",
                    title: "Attenzione!",
                    text: "Completa tutti i campi prima di procedere!",
                    customClass: {
                        popup: 'zoom-swal-popup'
                    }
                });
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "{{ route('settingsSaveAll') }}",
                data: {
                    parametro_olio_attivo: $("#parametro_olio_attivo").bootstrapSwitch('state'),
                    parametro_olio: parseInt($("#parametro_olio").val()),
                    parametro_spola_attivo: $("#parametro_spola_attivo").bootstrapSwitch('state'),
                    parametro_spola: parseInt($("#parametro_spola").val()),
                    fattore_taratura: parseInt($("#fattore_taratura").val()),
                }
            }).done(function(data) {
                if (data.success) {
                    $('#modal-xl').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "<strong>Parametri salvati</strong>",
                        text: '',
                        showConfirmButton: true,
                        customClass: {
                            popup: 'zoom-swal-popup'
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Salvataggio non effettuato!",
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

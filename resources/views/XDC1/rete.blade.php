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

<div class="kiosk-container">
    <div class="kiosk-input-container">
        <form id="form_rete">
            {{-- <div class="card mt-2 mb-1">
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td class="custom-cell" style="font-size: larger;">
                                    <div class="text-left header-section">Indirizzo IP attuale</div>
                                    <div class="text-left value-section" id="ip_view">
                                        @php $ip = trim(shell_exec('ip addr show eth0 | grep "inet " | cut -d" " -f6 | cut -d"/" -f1')) @endphp
                                        {{ $ip }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> --}}
            {{-- <label for="interfaccia" class="font-lg" style="color: #000;">Interfaccia di rete</label>
            <div class="input-group input-group-lg mb-2">
                <select id="interfaccia" name="interfaccia" class="form-control">
                    <option value="eth0">Ethernet (eth0)</option>
                    <option value="wlan0">Wi-Fi (wlan0)</option>
                </select>
            </div> --}}
            <input type="hidden" id="interfaccia_di_rete" name="interfaccia_di_rete" value="eth0">

            <label for="indirizzo_ip" class="font-lg" style="color: #000;">Indirizzo IP</label>
            <div class="input-group input-group-lg mb-2">
                <input type="text" value="{{ $indirizzo_ip }}" id="indirizzo_ip" name="indirizzo_ip" class="kiosk-input font-lg form-control no-border">
            </div>

            <label for="subnet_mask" class="font-lg" style="color: #000;">Subnet Mask</label>
            <div class="input-group input-group-lg mb-2">
                <input type="text" value="{{ $subnet_mask }}" id="subnet_mask" name="subnet_mask" class="kiosk-input font-lg form-control no-border">
            </div>

            <label for="gateway" class="font-lg" style="color: #000;">Gateway</label>
            <div class="input-group input-group-lg mb-2">
                <input type="text" value="{{ $gateway }}" id="gateway" name="gateway" class="kiosk-input font-lg form-control no-border">
            </div>

            <label for="dns" class="font-lg" style="color: #000;">DNS</label>
            <div class="input-group input-group-lg mb-2">
                <input type="text" value="{{ $dns }}" id="dns" name="dns" class="kiosk-input font-lg form-control no-border">
            </div>

            <div id="wifi_settings" style="display: none;">
                <label for="ssid" class="font-lg" style="color: #000;">SSID Wi-Fi</label>
                <div class="input-group input-group-lg mb-2">
                    <input type="text" value="{{ $ssid }}" id="ssid" name="ssid" class="kiosk-input font-lg form-control no-border">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4 col-md-2">
                    <div class="color-palette-set mt-3 mb-3">
                        <button type="button" class="btn btn-block btn-primary btn-lg custom-button" style="font-weight: bold;"
                            id="salva_rete">SALVA IMPOSTAZIONI</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

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
    $(document).ready(function() {
        $('#interfaccia').change(function() {
            $('#wifi_settings').toggle($(this).val() === "wlan0");
        });
        $('#salva_rete').click(function() {
            let interfaccia_di_rete = $('#interfaccia_di_rete').val();
            let indirizzo_ip = $('#indirizzo_ip').val();
            let subnet_mask = $('#subnet_mask').val();
            let gateway = $('#gateway').val();
            let dns = $('#dns').val();

            queueMessage({action: 'impostaRete', interfaccia_di_rete: interfaccia_di_rete, indirizzo_ip: indirizzo_ip, subnet_mask: subnet_mask, gateway: gateway, dns: dns});
            sendMessage();
            toggleLoader(true); // Avvia il loader
        });
    });
</script>
@endsection

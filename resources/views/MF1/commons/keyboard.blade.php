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

@extends(env('APP_NAME') . '.index')
@section('main')
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

        .dark-mode .custom-control-label::before,
        .dark-mode .custom-file-label,
        .dark-mode .custom-file-label::after,
        .dark-mode .custom-select,
        .dark-mode .form-control:not(.form-control-navbar):not(.form-control-sidebar),
        .dark-mode .input-group-text {
            background-color: #f2f8ff;
            color: #000000;
        }


.kioskboard-wrapper .kioskboard-row {
    border: none !important;
}

.kioskboard-row.kioskboard-row-top {
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

.kioskboard-row.kioskboard-row-bottom {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

.kioskboard-row.kioskboard-row-dynamic,
.kioskboard-key-capslock.capslock-active,
.kioskboard-key.kioskboard-key-space.spacebar-allowed {
    display: none !important;
}


/*
        span.kioskboard-key-enter,
        span.kioskboard-key-backspace,
        span.kioskboard-key {
            font-size: 50px !important;
            width: 25% !important;
            height: 20px;
        }

        #KioskBoard-VirtualKeyboard .kioskboard-row-numpad {
            max-width: 700px;
        }


#KioskBoard-VirtualKeyboard {
    zoom: 1.3;
    height: 50%;
}

#KioskBoard-VirtualKeyboard .kioskboard-row-numpad span[class^=kioskboard-key] {
    width: calc(33.3333% - 25px);
}

@media only screen and (max-width:767px) {

    #KioskBoard-VirtualKeyboard .kioskboard-row-numpad span[class^=kioskboard-key] {
        font-size: 25px !important;
        width: calc(33.3333% - 25px);
    }
} */
    </style>

    <div class="row" id="impostazioni_page" style="display: inline-flex;">
        <div class="col-12"
            style="padding: 0; position: fixed; top: 60px; right: 0; bottom: 0; left: 0; background-color: #343a40; color: #fff;">
            <div style="padding: 37px;">
                <div class="form-group row" style="font-size: 18px;">
                    <label class="col-sm-5 col-form-label">Nuovo PIN</label>
                    <div class="col-sm-7">
                        <input id="pin" type="password" class="form-control" placeholder="Inserire il nuovo PIN..." {{-- data-kioskboard-type="numpad" --}} data-kioskboard-placement="bottom">
                    </div>
                </div>
                <div class="form-group row" style="font-size: 18px;">
                    <label class="col-sm-5 col-form-label">Conferma Nuovo PIN</label>
                    <div class="col-sm-7">
                        <input id="pin_conferma" type="password" class="form-control"
                            placeholder="Confermare il nuovo PIN..." {{-- data-kioskboard-type="numpad" --}} data-kioskboard-placement="bottom">
                    </div>
                </div>
                <div style="display: block; text-align: center; padding-top: 5px; padding-bottom: 45px;">
                    <button type="submit" class="btn btn-primary" style="font-weight: 700; font-size: 18px;">Salva
                        Modifiche</button>
                </div>
                <div class="form-group row" style="font-size: 18px;">
                    <label class="col-sm-5 col-form-label" style="padding-top: 13px;">Sincronizza Dipendenti</label>
                    <div class="col-sm-7">
                        <button class="input-group-text"
                            style="background-color: #3498db; color: #ffffff; border-color: #3498db; padding: 18px 152px; width: 100%;"
                            onclick="sincronizzaDipendenti()">
                            <i class="fas fa-sync-alt fa-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

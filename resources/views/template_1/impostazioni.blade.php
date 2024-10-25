@extends('template_1.index')
@section('main')
    <div class="input-group input-group-lg mb-2 mt-2">
        <input id="parametro_spola" name="parametro_spola" type="text" placeholder="Parametro Spola"
            class="js-kioskboard-input font-lg form-control" data-kioskboard-type="numpad" data-kioskboard-placement="bottom"
            data-kioskboard-specialcharacters="true">
        <div class="input-group-append">
            <span id="parametro_spola_display" class="input-group-text" style="color: #6c757d">{{ $parametro_spola }}</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" id="misurazione_filo" class="font-lg form-control" placeholder="Misurazione Filo" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">{{ $misurazione_filo }}</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" id="impulsi" class="font-lg form-control" placeholder="Impulsi" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">{{ $impulsi }}</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" id="lunghezza_totale" class="font-lg form-control" placeholder="Lunghezza totale" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">{{ $lunghezza_totale }} cm</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" id="velocita" class="font-lg form-control" placeholder="Velocità" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">{{ $velocita }} m/s</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" id="operativita" class="font-lg form-control" placeholder="Operatività" disabled>
        <div class="input-group-append">
            <span class="input-group-text" style="color: #6c757d">{{ $operativita }}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3 mb-3">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button" style="font-weight: bold;"
                    id="salva_impostazioni">SALVA IMPOSTAZIONI</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        KioskBoard.init({
            keysArrayOfObjects: null,
            keysJsonUrl: "build/kioskboard/dist/kioskboard-keys-english.json",
            keysSpecialCharsArrayOfStrings: [
                "#", "€", "%", "+", "-", "*", "@", "!", "$", "&", "(", ")", "=",
                "?", "<", ">", "^", "~", "{", "}", "[", "]", "|", "\\", ";",
                ":", "'", "\"", ".", ",", "_", "`", "£", "¢", "•", "™", "©"
        ],
        keysNumpadArrayOfNumbers: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
        language: 'it',
        theme: 'material',
        autoScroll: false,
        capsLockActive: true,
        allowRealKeyboard: false,
        allowMobileKeyboard: false,
        cssAnimations: true,
        cssAnimationsDuration: 360,
        cssAnimationsStyle: 'slide',
        keysAllowSpacebar: true,
        keysSpacebarText: 'Space',
        keysFontFamily: 'sans-serif',
        keysFontSize: '28px',
        keysFontWeight: 'bold',
        keysIconSize: '31px',
        keysEnterText: '<i class="fas fa-check" style="font-weight: bold;"></i>',
        keysEnterCanClose: true
    });
    KioskBoard.run('.js-kioskboard-input');

    $('#salva_impostazioni').on('click', function() {
        const parametro_spola = $('#parametro_spola').val();

        if (!parametro_spola) {
            Swal.fire({
                icon: "error",
                title: "Errore!",
                text: "Nessun valore inserito!",
                customClass: {
                    popup: 'zoom-swal-popup'
                }
            });
            return;
        }
        settingsSave('parametro_spola', parametro_spola);
    });

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
                    title: "<strong>Parametro spola inferiore</strong>",
                    text: `Nuovo valore: ${value}`,
                        showConfirmButton: true,
                        customClass: {
                            popup: 'zoom-swal-popup'
                        }
                    });
                    $('#parametro_spola_display').text(value);
                    $('#parametro_spola').val('');
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Inserimento non effettuato!",
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
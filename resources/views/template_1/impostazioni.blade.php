@extends('template_1.index')
@section('main')
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" id="misurazione_filo" class="font-lg form-control no-border" placeholder="Misurazione Filo"
            disabled>
        <div class="input-group-append">
            <span class="input-group-text no-border" style="color: #000;">{{ $misurazione_filo }}</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" id="impulsi" class="font-lg form-control no-border" placeholder="Impulsi" disabled>
        <div class="input-group-append">
            <span class="input-group-text no-border" style="color: #000;">{{ $impulsi }}</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" id="lunghezza_totale" class="font-lg form-control no-border" placeholder="Lunghezza totale"
            disabled>
        <div class="input-group-append">
            <span class="input-group-text no-border" style="color: #000;">{{ $lunghezza_totale }} cm</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" id="velocita" class="font-lg form-control no-border" placeholder="Velocità" disabled>
        <div class="input-group-append">
            <span class="input-group-text no-border" style="color: #000;">{{ $velocita }} m/s</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" id="operativita" class="font-lg form-control no-border" placeholder="Operatività" disabled>
        <div class="input-group-append">
            <span class="input-group-text no-border" style="color: #000;">{{ $operativita }}</span>
        </div>
    </div>
    <div class="input-group input-group-lg mb-2 mt-2">
        <input id="parametro_spola" name="parametro_spola" type="text" placeholder="Parametro Spola"
            class="font-lg form-control no-border" data-kioskboard-type="numpad">
        <div class="input-group-append">
            <span id="parametro_spola_display" class="input-group-text no-border"
                style="color: #000;">{{ $parametro_spola }}</span>
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
            // keysNumpadArrayOfNumbers: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0],
            language: 'it',
            theme: 'material',
            autoScroll: true,
            capsLockActive: true,
            cssAnimations: true,
            cssAnimationsDuration: 360,
            cssAnimationsStyle: 'slide',
            keysSpacebarText: 'Space',
            keysFontFamily: 'sans-serif',
            keysFontWeight: 'bold',
            keysEnterText: '<i class="fas fa-check" style="font-weight: bold;"></i>',
            keysEnterCallback: function() {
                window.scrollTo(0, 0);
            },
            keysEnterCanClose: true
        });
        KioskBoard.run('input');

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
                window.scrollTo(0, 0);
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
                    window.scrollTo(0, 0);
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
                    window.scrollTo(0, 0);
                }
            }).fail(function(jqXHR, textStatus) {
                console.log("Errore generico!");
            });
        }
    </script>
@endsection

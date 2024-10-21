@extends('template_1.index')
@section('main')
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
    <div class="input-group input-group-lg mb-2 mt-2">
        <input type="text" id="parametro_spola" class="font-lg form-control" placeholder="Parametro Spola" disabled>
        <div class="input-group-append">
            <span id="parametro_spola_display" class="input-group-text" style="color: #6c757d">{{ $parametro_spola }}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-2">
            <div class="color-palette-set mt-3 mb-3">
                <button type="button" class="btn btn-block btn-primary btn-lg custom-button" style="font-weight: bold;"
                    onclick="openAlertSpola()">PARAMETRO SPOLA INFERIORE</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        async function openAlertSpola() {
            const {
                value: parametro_spola
            } = await Swal.fire({
                title: "<strong>Parametro spola inferiore</strong>:",
                input: "text",
                inputLabel: "Inserisci il valore del parametro",
                inputValue: "",
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return "<strong>Errore: inserisci un valore!</strong>";
                    }
                }
            });

            if (parametro_spola) {
                Swal.fire(`<strong>Parametro spola inferiore</strong>:<br>${parametro_spola}`);
                settingsSave('parametro_spola', parametro_spola);
            }
        }

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
                    console.log("Inserimento effettuato con successo!");
                    $(`#${setting}_display`).text(value);
                } else {
                    console.log("Inserimento non effettuato!");
                }
            }).fail(function(jqXHR, textStatus) {
                console.log("Errore generico!");
            });
        }
    </script>
@endsection
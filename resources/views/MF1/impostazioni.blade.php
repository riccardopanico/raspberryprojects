@extends('MF1.index')
@section('main')
    <form id="form_impostazioni">
        <label for="parametro_olio" class="font-lg" style="color: #000;">Parametro Olio</label>
        <div class="input-group input-group-lg mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text no-border p-0" style="color: #000;">
                    <input type="checkbox" id="parametro_olio_attivo" name="settings[parametro_olio_attivo]" {{ $parametro_olio_attivo ? 'checked' : '' }} data-bootstrap-switch>
                </span>
            </div>
            <input type="text" value="{{ $parametro_olio }}" id="parametro_olio" name="settings[parametro_olio]" id="parametro_olio"
                class="font-lg form-control no-border" style="text-align: right;" data-kioskboard-type="numpad">
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
                class="font-lg form-control no-border" style="text-align: right;" data-kioskboard-type="numpad">
            <div class="input-group-append">
                <span class="input-group-text no-border" style="color: #000;">m</span>
            </div>
        </div>
        <label for="parametro_olio" class="font-lg" style="color: #000;">Fattore Taratura</label>
        <div class="input-group input-group-lg mb-2">
            <input type="text" value="{{ $fattore_taratura }}" id="fattore_taratura" name="settings[fattore_taratura]" id="fattore_taratura"
                class="font-lg form-control no-border" style="text-align: right;" data-kioskboard-type="numpad">
            <div class="input-group-append">
                <span class="input-group-text no-border" style="color: #000;">m</span>
            </div>
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
@endsection

@section('script')
    <script>
        function settingsSaveAll() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "{{ route('settingsSaveAll') }}",
                data: {
                    settings: {
                        "parametro_olio_attivo": $("#parametro_olio_attivo").bootstrapSwitch('state') ? 1 : 0,
                        "parametro_olio": $("#parametro_olio").val(),
                        "parametro_spola_attivo": $("#parametro_spola_attivo").bootstrapSwitch('state') ? 1 : 0,
                        "parametro_spola": $("#parametro_spola").val(),
                        "fattore_taratura": $("#fattore_taratura").val()
                    }
                }
                // data: $("#form_impostazioni").serialize()
            }).done(function(data) {
                if (data.success) {
                    $('#modal-xl').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "<strong>Impostazioni salvate</strong>",
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

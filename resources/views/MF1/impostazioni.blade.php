@extends('MF1.index')
@section('main')
    <form id="form_impostazioni">
        <div class="input-group input-group-lg mb-2 mt-2">
            <input type="checkbox" name="my-checkbox" checked data-bootstrap-switch>
            <div class="input-group-prepend">
                <span class="input-group-text no-border" style="color: #000;">Parametro Olio</span>
            </div>
            <input type="text" value="{{ $parametro_olio }}" name="settings[parametro_olio]" id="parametro_olio"
                class="font-lg form-control no-border" style="text-align: right;" data-kioskboard-type="numpad">
            <div class="input-group-append">
                <span class="input-group-text no-border" style="color: #000;">m</span>
            </div>
        </div>

        <div class="input-group input-group-lg mb-2 mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text no-border" style="color: #000;">Parametro Spola</span>
            </div>
            <input type="text" value="{{ $parametro_spola }}" name="settings[parametro_spola]" id="parametro_spola"
                class="font-lg form-control no-border" style="text-align: right;" data-kioskboard-type="numpad">
            <div class="input-group-append">
                <span class="input-group-text no-border" style="color: #000;">m</span>
            </div>
        </div>

        <div class="input-group input-group-lg mt-2">
            <div class="input-group-prepend">
                <span class="input-group-text no-border" style="color: #000;">Fattore Taratura</span>
            </div>
            <input type="text" value="{{ $fattore_taratura }}" name="settings[fattore_taratura]" id="fattore_taratura"
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
@endsection

@section('script')
    <script>
        function settingsSaveAll() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "{{ route('settingsSaveAll') }}",
                data: $("#form_impostazioni").serialize()
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

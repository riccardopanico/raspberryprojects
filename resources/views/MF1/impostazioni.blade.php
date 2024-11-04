@extends('MF1.index')
@section('main')
    <form id="form_impostazioni">
        <div class="card mt-2">
            <div class="card-body p-0">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td class="custom-cell">
                                <div class="header-section">Consumo Commessa</div>
                                <div class="value-section">{{ $consumo_commessa }} m</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell">
                                <div class="header-section">Tempo Commessa</div>
                                <div class="value-section">{{ $tempo_commessa }} s</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell">
                                <div class="header-section">Consumo Totale</div>
                                <div class="value-section">{{ $consumo_totale }} m</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell">
                                <div class="header-section">Tempo Totale</div>
                                <div class="value-section">{{ $tempo_totale }} s</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
        <input type="hidden" name="id_macchina" value="{{ $id_macchina }}">

        <div class="row">
            <div class="col-sm-4 col-md-2">
                <div class="color-palette-set mt-3 mb-3">
                    <button type="button" class="btn btn-block btn-primary btn-lg custom-button" style="font-weight: bold;"
                        id="salva_impostazioni" onclick="settingsSaveAll()">SALVA
                        IMPOSTAZIONI</button>
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
                data: $("#form_impostazioni").serialize() + '&_token={{ csrf_token() }}'
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
